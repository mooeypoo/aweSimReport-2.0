<?php echo text_output($header, 'h2');?>
<?php echo text_output($text);?>


<?php echo form_open('report/awesimreport/generator/saved/');?>
<?php echo form_hidden('formAction',$formAction); ?>
	<table class="table100">
		<tbody>
			
			<tr>
				<td class="cell-label"><?php echo 'Report ID:';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_input($inputs['txtReportID']);?> <span>(Non editable)</span></td>
			</tr>
			
			<?php echo table_row_spacer(3, 10);?>

			<tr>
				<td class="cell-label"><?php echo 'Report Name:';?></td>
				<td class="cell-spacer"></td>
				<td><?php echo form_input($inputs['txtSavedName']);?></td>
			</tr>
			
			<?php echo table_row_spacer(3, 10);?>

			<tr>
				<td colspan="2"></td>
				<td><?php echo form_button($inputs['submit']);?></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close();?>