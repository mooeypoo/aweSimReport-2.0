<?php echo text_output($header, 'h1', 'page-head');?>

<?php /** MENU **/ ?>
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

<div id='awe_settings'>
<?php echo form_button($inputs['saveSettings']);?>
</div>
<hr>
<br />

<?php echo form_open('report/awesimreport/settings');?>
	<input type="hidden" name="action" value="saveSettings" />


	<?php echo text_output('General Settings', 'h2', 'page-subhead');?>
	<div class="indent-left">
		<kbd><?php echo form_label('Date Format:', 'txtDateFormat');?><?php echo form_input($inputs['txtDateFormat']); ?></kbd>
		<kbd><?php echo form_label('Report Title:', 'txtReportTitle');?><?php echo form_input($inputs['txtReportTitle']); ?></kbd>
		<kbd><?php echo form_label('Template Footer:', 'txtTemplateFooter');?><br /><?php echo form_textarea($inputs['txtTemplateFooter']); ?></kbd>
	</div>

	<?php echo text_output('Email Settings', 'h2', 'page-subhead');?>
	<div class="indent-left">
		<kbd><?php echo form_label('Email Subject:', 'txtEmailSubject');?>
		<?php echo form_input($inputs['txtEmailSubject']); ?></kbd>
		<kbd><?php echo form_label('Email Recipients:', 'txtEmailSubject');?>
		<?php echo form_input($inputs['txtEmailRecipients']); ?><br /><span style='font-size: 9px;'>(Separate emails with commas.)</span></kbd>
	</div>

	<?php echo text_output('Sections Settings', 'h2', 'page-subhead');?>

	<div id="tabs">

		<ul>
			<li><a href="#roster"><span>Roster</span></a></li>
			<li><a href="#coc"><span>Chain of Command</span></a></li>
	<?php /*	<li><a href="#simtime"><span>Sim Time</span></a></li> */ ?>
			<li><a href="#stats"><span>Statistics</span></a></li>
		</ul>
		
		<div id="roster">
			<?php echo text_output('Roster Settings', 'h3', 'page-subhead');?>
			<div class="indent-left">
				<kbd><?php echo form_checkbox($inputs['chkPresenceTags']); ?>
				<?php echo form_label('Display Presence Tags', 'chkPresenceTags');?></kbd>
				<div style="margin-left: 25px;">
					<kbd><?php echo form_label('Present:', 'txtPresenceTag_Present');?>
					<?php echo form_input($inputs['txtPresenceTag_Present']); ?></kbd>
					<kbd><?php echo form_label('Excused Absence:', 'txtPresenceTag_Excused');?>
					<?php echo form_input($inputs['txtPresenceTag_Excused']); ?></kbd>
					<kbd><?php echo form_label('Unexcused Absence:', 'txtPresenceTag_Unexcused');?>
					<?php echo form_input($inputs['txtPresenceTag_Unexcused']); ?></kbd>
				</div>
				<kbd><?php echo form_checkbox($inputs['chkShowRankImagesRoster']); ?>
				<?php echo form_label('Display Rank Images in Roster', 'chkShowRankImagesRoster');?></kbd>
			</div>
		</div><!-- roster -->
		
		<div id="coc">
			<?php echo text_output('Chain of Command Settings', 'h3', 'page-subhead');?>
				<kbd><?php echo form_checkbox($inputs['chkShowRankImagesCOC']); ?>
				<?php echo form_label('Display Rank Images in COC', 'chkShowRankImagesCOC');?></kbd>
		</div><!-- coc -->

<?php /*	<div id='simtime'>
			<?php echo text_output('Sim Time and Duration Settings', 'h3', 'page-subhead');?>
				<kbd><?php echo form_label('Start Time:', 'txtSimStart');?>
				<?php echo form_input($inputs['txtSimStart']); ?></kbd>
				<kbd><?php echo form_label('End Time:', 'txtSimEnd');?>
				<?php echo form_input($inputs['txtSimEnd']); ?></kbd>
		
		</div> <!-- simtime -->
*/ ?>
		<div id="stats">
			<?php echo text_output('Statistics Settings', 'h3', 'page-subhead');?>

			<kbd><?php echo form_label('Report Duration:', 'txtReportDuration');?><?php echo form_input($inputs['txtReportDuration']); ?> days.</kbd>
			
			<kbd><?php echo form_label('Number of Occurences to Display:', 'txtStatOccurences');?><?php echo form_input($inputs['txtStatOccurences']); ?></kbd>
			
		</div><!-- coc -->
		
	</div> <!-- tabs -->
	
<br />
<hr>	

<div id='awe_settings'>
<?php echo form_button($inputs['saveSettings2']);?>
</div>
<?php echo form_close();?>


<br />

<div id="awe_footer">

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" align="left" /></a><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><a href="http://github.com/mooeypoo/aweSimReport-2.0" target="_blank">aweSimReport for Nova</a></span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName"><a href="mailto:themoocode@gmail.com">Moriel Schottlender</a></span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>
NOVA is an RPG management software by <a href='http://www.anodyne-productions.com/' target='_blank'>Anodyne</a>.
	
</div>
