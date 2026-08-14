<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('organizational_chart'); ?></h4>
            <hr class="hr-panel-heading" />
            <p class="text-muted"><?php echo _l('organizational_chart'); ?> - <?php echo _l('departments'); ?> & <?php echo _l('staff'); ?></p>
            <div id="org-chart-container" class="table-responsive" style="overflow-x: auto; min-height: 400px;">
              <table class="table table-bordered">
                <tbody>
                  <?php
                  $tree = json_decode($dep_tree, true);
                  if (!empty($tree)):
                    foreach ($tree as $dept):
                      $staff_in_dept = [];
                      foreach ((array)$staff as $s) {
                        $deps = $this->departments_model->get_staff_departments($s['staffid']);
                        foreach ($deps as $d) { if ($d['departmentid'] == $dept['id']) { $staff_in_dept[] = $s; break; } }
                      }
                  ?>
                  <tr>
                    <td class="info"><strong><?php echo htmlspecialchars($dept['title']); ?></strong>
                      <?php foreach ($staff_in_dept as $st): ?>
                        <br><small><?php echo get_staff_full_name($st['staffid']); ?></small>
                      <?php endforeach; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                  <tr><td><?php echo _l('no_results'); ?></td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
