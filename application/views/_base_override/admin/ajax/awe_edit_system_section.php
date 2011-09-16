<?php echo text_output($header, 'h2');?>
<?php echo text_output($text);?>


<?php echo form_open('report/awesimreport/sections/sysedit');?>
<?php echo form_hidden('secID',$secID);?>
	<table class="table100">
		<tbody>
			
			<tr>
				<td class="cell-label"><?php echo 'Section Name';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_input($inputs['secName']);?> <span>(Non editable)</span></td>
			</tr>
			
			<?php echo table_row_spacer(3, 10);?>

			<tr>
				<td class="cell-label"><?php echo 'Section Title';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_input($inputs['secTitle']);?></td>
			</tr>

			<?php echo table_row_spacer(3, 20);?>
			
			<tr>
				<td colspan="2"></td>
				<td><?php echo form_button($inputs['submit']);?></td>
			</tr>
						
		</tbody>
	</table>
<?php echo form_close();?>