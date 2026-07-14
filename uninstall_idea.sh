#!/bin/bash
set -e

echo "======================================"
echo "  IntelliJ IDEA Uninstaller"
echo "======================================"
echo ""

# --- Find installation directories ---
echo "[1/5] Finding IntelliJ IDEA installations..."

IDEA_DIRS=$(find /home /opt -maxdepth 3 -name "idea-*" -type d 2>/dev/null || true)
TOOLBOX_DIR="$HOME/.local/share/JetBrains/Toolbox"

if [ -z "$IDEA_DIRS" ] && [ ! -d "$TOOLBOX_DIR" ]; then
    echo "  No IntelliJ IDEA installation found."
    echo "  (Checked ~/idea-* directories and Toolbox)"
    echo ""
    echo "  Try manually: which idea"
    exit 1
fi

echo "  Found:"
[ -n "$IDEA_DIRS" ] && echo "    $IDEA_DIRS" | sed 's/ /\n    /g'
[ -d "$TOOLBOX_DIR" ] && echo "    $TOOLBOX_DIR"

echo ""

# --- Remove installation directories ---
echo "[2/5] Removing installation directories..."
for dir in $IDEA_DIRS; do
    echo "  Deleting $dir ..."
    rm -rf "$dir"
done

# --- Remove config/cache/plugins ---
echo "[3/5] Removing config, cache, and plugin data..."
for d in "JetBrains/IntelliJIdea*" "JetBrains/IdeaIC*"; do
    for base in ".config" ".cache" ".local/share"; do
        path="$HOME/$base/$d"
        eval ls -d $path 2>/dev/null | while read p; do
            echo "  Deleting $p ..."
            rm -rf "$p"
        done
    done
done

# Also remove JetBrains global config if IntelliJ was the only product
[ -d "$HOME/.config/JetBrains" ] && rmdir "$HOME/.config/JetBrains" 2>/dev/null && echo "  Removed empty .config/JetBrains"

# --- Remove Java preferences ---
echo "[4/5] Removing Java user preferences..."
rm -rf "$HOME/.java/.userPrefs/jetbrains"

# --- Remove Toolbox app (optional) ---
if [ -d "$TOOLBOX_DIR" ]; then
    echo ""
    echo "  Toolbox App detected at $TOOLBOX_DIR"
    echo -n "  Remove Toolbox too? [y/N]: "
    read -r ans
    if [ "$ans" = "y" ] || [ "$ans" = "Y" ]; then
        echo "  Removing Toolbox ..."
        rm -rf "$TOOLBOX_DIR"
        rm -f "$HOME/.local/share/applications/jetbrains-toolbox.desktop"
    fi
fi

# --- Remove desktop entries ---
echo "[5/5] Removing desktop entries..."
rm -f "$HOME/.local/share/applications/jetbrains-idea*.desktop" 2>/dev/null
rm -f "$HOME/.local/share/applications/intellij-*.desktop" 2>/dev/null
rm -f "$HOME/.local/share/applications/idea*.desktop" 2>/dev/null
# Also remove the system-level one if it exists
sudo rm -f /usr/share/applications/jetbrains-idea.desktop 2>/dev/null || true

echo ""
echo "======================================"
echo "  IntelliJ IDEA has been uninstalled."
echo "======================================"
echo ""
echo "Note: If you reinstall later, you may want to remove leftover config:"
echo "  ls ~/.config/JetBrains/  ~/.cache/JetBrains/"
echo ""
