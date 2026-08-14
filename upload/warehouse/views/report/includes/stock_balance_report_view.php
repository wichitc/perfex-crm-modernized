<?php
if (isset($arr_inventory_manage) && count($arr_inventory_manage) > 0) {
	$get_base_currency =  get_base_currency();
	if($get_base_currency){
		$base_currency_id = $get_base_currency->id;
	}else{
		$base_currency_id = 0;
	}

	$grand_total_qty  = 0;
	$grand_total_cost = 0;
	$order_number = 0;
	foreach ($arr_inventory_manage as $iv_key => $inventory_manages) {
		$iv_key_explode = explode('_', $iv_key);
		$warehouse_id   = isset($iv_key_explode[0]) ? $iv_key_explode[0] : 0;
		$warehouse_name = isset($warehouse_ids[$warehouse_id]) ? $warehouse_ids[$warehouse_id] : '';
		if (isset($inventory_manages) && count($inventory_manages) > 0) {?>

			<tr>
				<th colspan="13" class="text-left bold"><?php echo _l('warehouse_filter'); ?>:<?php echo html_entity_decode($warehouse_name); ?> </th>
			</tr>

			<?php foreach ($inventory_manages as $commodity_id => $commodity_value) { ?>
				<?php 
				$total_qty      = 0;
				$total_cost     = 0;
				?>
				<?php foreach ($commodity_value as $key => $inventory_value) {
					$order_number     ++;
					$total_serial_qty = 0;
					?>
					<?php if($key == 0){ ?>
						<tr>
							<td class="bold"><?php echo html_entity_decode($order_number); ?></td>
							<td class="bold"><?php echo html_entity_decode($inventory_value['commodity_code'] ?? ''); ?></td>
							<td class="bold"><?php echo html_entity_decode($inventory_value['description'] ?? ''); ?></td>
							<td class="bold"><?php echo html_entity_decode($inventory_value['commondity_name'] ?? ''); ?></td>
							<td class="bold"><?php echo html_entity_decode($inventory_value['name'] ?? ''); ?></td>
							<td class="bold"><?php echo html_entity_decode($inventory_value['sub_group_name'] ?? ''); ?></td>
							<td class="bold"></td>
							<td class="bold"></td>
							<td class="bold"></td>
							<td class="bold"><?php echo html_entity_decode($inventory_value['unit_name'] ?? ''); ?></td>
							<td class="bold"></td>
							<td class="bold"></td>
							<td class="bold"></td>
						</tr>
					<?php } ?>

					<?php if (isset($serial_numbers[$inventory_value['id']]) && count($serial_numbers[$inventory_value['id']]) > 0) {?>
						<?php foreach ($serial_numbers[$inventory_value['id']] as $serial_key => $serial_number) {?>
							<?php
							$serial_number_total_cost = (float) $inventory_value['purchase_price'] * 1;
							$total_serial_qty++;
							?>
							<tr>
								<td colspan="1"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?php echo html_entity_decode($inventory_value['lot_number'] ?? ''); ?></td>
								<td><?php echo html_entity_decode($serial_number['serial_number'] ?? ''); ?></td>
								<td><?php echo html_entity_decode($inventory_value['expiry_date'] ?? ''); ?></td>
								<td></td>
								<td>1</td>
								<td><?php echo html_entity_decode(app_format_money((float) $inventory_value['purchase_price'], $base_currency_id)); ?></td>
								<td><?php echo html_entity_decode(app_format_money($serial_number_total_cost, $base_currency_id)); ?></td>
							</tr>
						<?php }?>
					<?php }?>

					<?php if ((float) $inventory_value['inventory_number'] > (float) $total_serial_qty) {?>
						<?php
						$no_serial_number_qty        = (float) $inventory_value['inventory_number'] - (float) $total_serial_qty;
						$no_serial_number_total_cost = (float) $inventory_value['purchase_price'] * (float) $no_serial_number_qty;
						?>
						<tr>
							<td colspan="1"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo html_entity_decode($inventory_value['lot_number'] ?? ''); ?></td>
							<td><?php echo _l('wh_no_serial_number'); ?></td>
							<td><?php echo html_entity_decode($inventory_value['expiry_date'] ?? ''); ?></td>
							<td></td>
							<td><?php echo html_entity_decode(app_format_number($no_serial_number_qty, '')); ?></td>
							<td><?php echo html_entity_decode(app_format_money((float) $inventory_value['purchase_price'], $base_currency_id)); ?></td>
							<td><?php echo html_entity_decode(app_format_money($no_serial_number_total_cost, $base_currency_id)); ?></td>
						</tr>
					<?php }?>

					<?php
					$total_qty += (float) $inventory_value['inventory_number'];
					$total_cost += (float) $inventory_value['purchase_price'] * (float) $inventory_value['inventory_number'];
					
					?>

				<?php } ?>
				<?php 
				$grand_total_qty += (float) $total_qty;
				$grand_total_cost += (float) $total_cost;
				?>
				<tr>
					<th colspan="9" class="text-right bold"><?php echo _l('total') ?> : </th>
					<th colspan="1" class="bold"></th>
					<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($total_qty, '')); ?></th>
					<th colspan="1" class="bold"></th>
					<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_money($total_cost, $base_currency_id)); ?></th>
				</tr>
			<?php } ?>
			
		<?php }?>
	<?php }?>
	<tr>
		<th colspan="9" class="text-right bold"><?php echo _l('wh_grand_total') ?> : </th>
		<th colspan="1" class="bold"></th>
		<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_number($grand_total_qty, '') ?? ''); ?></th>
		<th colspan="1" class="bold"></th>
		<th colspan="1" class="bold"><?php echo html_entity_decode(app_format_money($grand_total_cost, $base_currency_id)); ?></th>
	</tr>
	<?php
}?>
