<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Payslip - <?php echo htmlspecialchars($staff_name); ?> - <?php echo date('F Y', strtotime($payroll_month ?? 'now')); ?></title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .payslip-header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 15px; }
    .payslip-title { font-size: 18px; font-weight: bold; }
    .payslip-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .payslip-table th, .payslip-table td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
    .payslip-table th { background: #f5f5f5; font-weight: bold; }
    .payslip-footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    @media print { body { margin: 0; } .no-print { display: none; } }
  </style>
</head>
<body>
  <div class="no-print" style="margin-bottom: 15px;">
    <button onclick="window.print();" class="btn btn-info"><?php echo _l('print'); ?></button>
    <button onclick="window.close();" class="btn btn-default"><?php echo _l('close'); ?></button>
  </div>
  <div class="payslip-header">
    <div class="payslip-title"><?php echo _l('payslip'); ?></div>
    <div><?php echo htmlspecialchars($staff_name); ?> - <?php echo date('F Y', strtotime($payroll_month ?? 'now')); ?></div>
    <?php if (!empty($payroll_type) && is_object($payroll_type)): ?>
    <div><?php echo htmlspecialchars($payroll_type->payroll_type_name ?? ''); ?></div>
    <?php endif; ?>
  </div>
  <table class="payslip-table">
    <thead>
      <tr><th><?php echo _l('description'); ?></th><th><?php echo _l('value'); ?></th></tr>
    </thead>
    <tbody>
      <?php if (!empty($staff_row) && is_array($staff_row)): ?>
        <?php foreach ($staff_row as $k => $v): if ($k === 'staff_id' || $k === 'staffid') continue; ?>
        <tr>
          <td><?php echo htmlspecialchars($k); ?></td>
          <td><?php echo htmlspecialchars($v); ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="2"><?php echo _l('no_results'); ?></td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <div class="payslip-footer">
    <?php echo _l('payslip'); ?> - <?php echo date('d/m/Y H:i'); ?>
  </div>
  <script>
    if (window.location.search.indexOf('print=1') !== -1) { window.onload = function() { window.print(); }; }
  </script>
</body>
</html>
