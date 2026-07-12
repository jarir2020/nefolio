<div class="col-md-8">
  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $successText; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $errorText; ?></div>
  <?php endif; ?>

  <div class="panel panel-default">
    <div class="panel-body">
      <h3 class="set-currency b-blue">Rates Settings</h3>
      <hr>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading"><strong>Dollar Rate</strong></div>
            <div class="panel-body">
              <div class="form-group">
                <label class="control-label">Current Dollar Rate</label>
                <div class="form-control" style="height:auto;background:#f7f7f7;">
                  1 USD = <?php echo number_format((float)$currentDollarRate, 2, '.', ''); ?> BDT
                </div>
              </div>
              <form action="<?php echo site_url('admin/settings/rates/dollar'); ?>" method="post">
                <div class="form-group">
                  <label class="control-label">Update Dollar Rate</label>
                  <input type="number" step="0.01" min="0" class="form-control" name="dollar_rate" value="<?php echo htmlspecialchars($currentDollarRate); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save Dollar Rate</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="panel panel-default">
            <div class="panel-heading"><strong>Bonus Rate Rules</strong></div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Range From</th>
                      <th>Range To</th>
                      <th>Bonus %</th>
                      <th>Status</th>
                      <th class="text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($bonusRates)): ?>
                      <?php foreach ($bonusRates as $rule): ?>
                        <tr>
                          <td><?php echo number_format((float)$rule["range_from"], 2, '.', ''); ?></td>
                          <td><?php echo $rule["range_to"] === null || $rule["range_to"] === "" ? '∞' : number_format((float)$rule["range_to"], 2, '.', ''); ?></td>
                          <td><?php echo number_format((float)$rule["bonus_percent"], 2, '.', ''); ?>%</td>
                          <td><?php echo !empty($rule["is_active"]) ? 'Active' : 'Inactive'; ?></td>
                          <td class="text-right">
                            <a class="btn btn-default btn-xs" href="<?php echo site_url('admin/settings/rates/edit/'.$rule['id']); ?>">Edit</a>
                            <a class="btn btn-danger btn-xs" href="<?php echo site_url('admin/settings/rates/delete/'.$rule['id']); ?>" onclick="return confirm('Delete this bonus rule?');">Delete</a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">No bonus rules configured yet.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading">
              <strong><?php echo (route(3) == "edit" && !empty($bonusRate)) ? 'Edit Bonus Rule' : 'Add Bonus Rule'; ?></strong>
            </div>
            <div class="panel-body">
              <?php if (route(3) == "edit" && !empty($bonusRate)): ?>
                <form action="<?php echo site_url('admin/settings/rates/bonus-edit/' . $bonusRate["id"]); ?>" method="post">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Range From</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="range_from" value="<?php echo htmlspecialchars($bonusRate["range_from"]); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Range To</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="range_to" value="<?php echo $bonusRate["range_to"] === null ? '' : htmlspecialchars($bonusRate["range_to"]); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Bonus %</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="bonus_percent" value="<?php echo htmlspecialchars($bonusRate["bonus_percent"]); ?>">
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Update Rule</button>
                  <a href="<?php echo site_url('admin/settings/rates'); ?>" class="btn btn-link">Cancel</a>
                </form>
              <?php else: ?>
                <form action="<?php echo site_url('admin/settings/rates/bonus-new'); ?>" method="post">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Range From</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="range_from" placeholder="0">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Range To</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="range_to" placeholder="Leave blank for no upper limit">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Bonus %</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="bonus_percent" placeholder="0">
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Add Rule</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
