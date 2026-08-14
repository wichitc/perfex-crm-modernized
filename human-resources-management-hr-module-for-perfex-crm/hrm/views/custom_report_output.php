<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!empty($rows)): ?>
<div class="table-responsive">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <?php foreach (array_keys($rows[0]) as $col): ?>
        <th><?php echo htmlspecialchars($col); ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <?php foreach ($r as $v): ?>
        <td><?php echo htmlspecialchars($v); ?></td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<p class="text-muted"><?php echo _l('no_results'); ?></p>
<?php endif; ?>
