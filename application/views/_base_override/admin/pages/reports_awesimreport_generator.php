<?php echo text_output($header, 'h1', 'page-head');?>

<?php /** MENU  **/ ?>
<div id="awe_mainmenu">
	<div id="awe_menuitem">
		<a href="<?php echo site_url('report/awesimreport/generator'); ?>">
		<?php echo img($images['menu']['generator']); ?><br />Generator
		</a>
	</div>
	<div id="awe_menuitem">
		<a href="<?php echo site_url('report/awesimreport/settings'); ?>">
		<?php echo img($images['menu']['settings']); ?><br />Settings
		</a>
	</div>
	<div id="awe_menuitem">
		<a href="<?php echo site_url('report/awesimreport/sections'); ?>">
		<?php echo img($images['menu']['sections']); ?><br />Sections
		</a>
	</div>
	<div id="awe_menuitem">
		<a href="<?php echo site_url('report/awesimreport/templates'); ?>">
		<?php echo img($images['menu']['templates']); ?><br />Templates
		</a>
	</div>
	<div id="awe_menuitem" class='awe_menu_last'>
		<a href="<?php echo site_url('report/awesimreport/archive'); ?>">
		<?php echo img($images['menu']['archive']); ?><br />Archive
		</a>
	</div>
</div>
<?php /** END MENU **/ ?>
<div id="saving" class="hidden"><?php echo img($images['loading']);?><?php echo 'Processing...';?>...</div>
<div id='ajaxnotice' class='hidden'></div>

	<?php print_r($debug['chkShowUsers']); ?><br />
<?php /*
<hr>
	<?php print_r($debug['chkShowUsers']); ?><br />
	<?php //print $debug['action']; ?>
<hr> */ ?>
<br />
	<?php //print_r($savedReports); ?>
	<div id='savedReports'>
<?php	if (count($savedReports)>0) {
		foreach ($savedReports as $key => $val) { ?>
			<div id="report_<?php echo $key; ?>" class='savedreport'><?php echo anchor('report/awesimreport/generator/'. $key, 'Saved Report '.$key);?><br />
			<span style="font-size: 80%;"><?php echo date('n/j/Y',$val['dateStart']).'-'.date('n/j/Y',$val['dateEnd']) ?></span></div>
<?php
		}
	}  ?>
	</div>
<hr>
<br />
<?php //echo form_open('report/awesimreport/generator/'.$reportid, $inputs['formAttributes']);?>
<?php 	if ($this->uri->segment(4) !== FALSE) { 
		echo form_open('report/awesimreport/generator/'.$this->uri->segment(4), $inputs['formAttributes']);
	} else { 
		echo form_open('report/awesimreport/generator/', $inputs['formAttributes']);
	}
?>
	<input type="hidden" name="action" value="generateReport" />
	<input type="hidden" name="id" value="<?php print $reportid; ?>" />

	<div class="indent-left">
	<!--span class="UITheme" -->
		<kbd><?php echo form_label('Report Date Range:', 'txtReportDateStart');?><?php echo form_input($inputs['txtReportDateStart']); ?> to <?php echo form_input($inputs['txtReportDateEnd']); ?> days.</kbd>
	</span>
	<!-- /div -->
	
<?php 	/** ROSTER **/ 
	if (($roster['Enabled']) == 1) { ?>
<!-- START ROSTER -->
	<br />
	<div class="indent-left">

			<table class="zebra" align="center">
				<thead>
					<tr>
						<th>Use</th>
<?php					if (($roster['ShowRankImages']) == 'checked') { ?>
						<th>Rank</th>
<?php					} ?>
						<th colspan="2">Name</th>
<?php	 				if ($roster['UseTags'] == 'checked') { ?>					
							<th colspan="3">Sim Presence</th>
<?php					} ?>
					</tr>
<?php 				if ($roster['UseTags'] == 'checked') { ?>					
					<tr>
						<th class="fontTiny nobold" colspan="4"></th>
						<th class="fontTiny nobold"><?php print $roster['Unexcused']; ?></th>
						<th class="fontTiny nobold"><?php print $roster['Excused']; ?></th>
						<th class="fontTiny nobold"><?php print $roster['Present']; ?></th>
					</tr>
<?php 				} ?>					
				</thead>
				<tbody>
				<?php
					foreach ($characters as $dept) {
						if ((isset($dept['chars'])) && (count($dept['chars'])>0)) {	?>
							<tr><th colspan="5"><?php echo strtoupper($dept['deptname']);?></th></tr>
				<?php		foreach ($dept['chars'] as $char) { ?>

								<tr>
									<td>
									<?php echo form_checkbox($chkRosterShowUsers[$char['id']]); ?>
									</td>
<?php							if (($roster['ShowRankImages']) == 'checked') { ?>
									<td><?php echo img($char['rank_img']); ?></td>
<?php							} ?>
									<td>
										<?php if ($char['loa'] == '[LOA]'): ?>
											<?php echo text_output($char['loa'], 'span', 'red fontSmall bold');?>
										<?php elseif ($char['loa'] == '[ELOA]'): ?>
											<?php echo text_output($char['loa'], 'span', 'orange fontSmall bold');?>
										<?php endif;?>

										<?php echo text_output($char['char_name'], 'span', 'bold');?><br />
										<span class="fontTiny">
											<?php echo anchor('personnel/user/'. $char['id'], 'User Account');?>
											|
											<?php echo anchor('personnel/character/'. $char['charid'], 'Character Bio');?>
										</span>
									</td>
									<td class="fontTiny nobold">(<?php print $char['position']; ?>)</td>
<?php				 				if ($roster['UseTags'] == 'checked') { ?>					
								<?php		if (($char['loa'] == '[LOA]') || ($char['loa'] == '[ELOA]')) { ?>
											<td align="center" valign="middle" colspan="3"><?php print $char['loa']; ?></td>
								<?php		} else { ?>
											<td align="center" valign="middle"><?php echo form_radio($radAttendance[$char['charid']]['U']); ?></td>
											<td align="center" valign="middle"><?php echo form_radio($radAttendance[$char['charid']]['E']); ?></td>
											<td align="center" valign="middle"><?php echo form_radio($radAttendance[$char['charid']]['P']); ?></td>
								<?php		}	?>									
<?php								} ?>						
									</tr>
				<?php		} //end foreach - character per department 
	
							if ((isset($dept['subdept'])) && (count($dept['subdept'])>0)) {
								foreach ($dept['subdept'] as $subdept) {
									if ((isset($subdept['chars'])) && (count($subdept['chars'])>0)) {	?>
										<tr><th colspan="5"><?php echo strtoupper($subdept['deptname']);?></th></tr>
							<?php		foreach ($subdept['chars'] as $char) { ?>
									<tr>
										<td>
											<?php if ($char['loa'] == '[LOA]'): ?>
												<?php echo text_output($char['loa'], 'span', 'red fontSmall bold');?>
											<?php elseif ($char['loa'] == '[ELOA]'): ?>
												<?php echo text_output($char['loa'], 'span', 'orange fontSmall bold');?>
											<?php endif;?>
											
											<?php echo text_output($char['char_name'], 'span', 'bold');?><br />
											<span class="fontTiny">
												<?php echo anchor('personnel/user/'. $char['id'], 'User Account');?>
												|
												<?php echo anchor('personnel/character/'. $char['charid'], 'Character Bio');?>
											</span>
										</td>
										<td class="fontTiny nobold">(<?php print $char['position']; ?>)</td>
								<?php		if (($char['loa'] == '[LOA]') || ($char['loa'] == '[ELOA]')) { ?>
											<td align="center" valign="middle" colspan="3"><?php print $char['loa']; ?></td>
								<?php		} else { ?>
											<td align="center" valign="middle"><?php echo form_radio($radAttendance[$char['charid']]['U']); ?></td>
											<td align="center" valign="middle"><?php echo form_radio($radAttendance[$char['charid']]['E']); ?></td>
											<td align="center" valign="middle"><?php echo form_radio($radAttendance[$char['charid']]['P']); ?></td>
								<?php		} ?>
										</tr>
				<?php 					} //end foreach - character per subdept	?>			
	<?php							} //end if - subdept has characters
								}
							} // end foreach - subdept
					
					
						} //end if - dept has characters
					} //end foreach - dept ?>
				</tbody>
			</table>
	</div>
<?php
	} //end if -- roster enabled
?>
<!-- END ROSTER -->			

<?php 	if (count($sections)>0) { ?>
	<div class="indent-left">
<?php		foreach ($sections as $sec) { ?>
			<h2><?php echo $sec['title']; ?></h2>
			<?php echo form_textarea($sec['input']); ?>
			<br />
<?php 		} ?>
	</div>
<?php	} //end if sections exist ?>







<br />
<hr>
<br />

	
<p>
	<?php echo form_button($inputs['butGenerate']);?>
		&nbsp;

	<?php echo form_button($inputs['preview']);?>
		&nbsp;
	
	<?php if ($this->uri->segment(4) !== FALSE) { ?>
		<?php echo form_button($inputs['update']);?>
		&nbsp;
		<?php echo form_button($inputs['delete']);?>
		&nbsp;
	<?php } else { ?>
		<?php echo form_button($inputs['save']);?>
		&nbsp;
	<?php }  ?>

</p>
	
<?php echo form_close();?>
	

<br />

<div id="awe_footer">

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" align="left" /></a><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><a href="http://github.com/mooeypoo/aweSimReport-2.0" target="_blank">aweSimReport for Nova</a></span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName"><a href="mailto:themoocode@gmail.com">Moriel Schottlender</a></span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>
NOVA is an RPG management software by <a href='http://www.anodyne-productions.com/' target='_blank'>Anodyne</a>.
	
</div>
