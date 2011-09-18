<?php echo text_output($header, 'h2');?>
<?php echo text_output($text);?>

<?php echo form_open('report/awesimreport/sections/add');?>
	<table class="table100">
		<tbody>
			
			<tr>
				<td class="cell-label"><?php echo 'Section Name';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_input($inputs['secName']);?></td>
			</tr>
			
			<?php echo table_row_spacer(3, 10);?>

			<tr>
				<td class="cell-label"><?php echo 'Section Title';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_input($inputs['secTitle']);?></td>
			</tr>
			
			<?php echo table_row_spacer(3, 10);?>
			
			<tr>
				<td class="cell-label"><?php echo 'Section Default Content';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_textarea($inputs['secDefaultContent']);?></td>
			</tr>
			
			<?php echo table_row_spacer(3, 20);?>
			
			<tr>
				<td colspan="2"></td>
				<td><?php echo form_button($inputs['submit']);?></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close();?>