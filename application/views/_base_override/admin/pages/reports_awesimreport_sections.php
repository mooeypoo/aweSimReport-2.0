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
<div id='sitemsg' class='hidden'></div>


<br />
<br />

<div id="addSection">
	<?php echo form_button($inputs['addSection']);?>
</div>

<br />
<hr /><br />



<?php echo text_output('Unused/Saved Sections', 'h3', 'page-subhead');?>
<?php echo text_output('These are saved sections. These sections are <strong>not</strong> used in the report itself. If you want to add any of those to the report, click "ADD TO REPORT".');?>

	<div class="UITheme">
		<?php
		if (isset($sections[0])) { ?>
		<ul id="unusedlist">
<?php		
			foreach ($sections[0] as $sect) { ?>
				<li class="ui-state-default" id="sec_<?php echo $sect['id'];?>">
					<div class="float_right">
					<a href="#" class="remove image" myID="<?php echo $sect['id'] ?>" myName="<?php echo $sect['name']; ?>" isUserDefined="<?php echo $sect['userdefined'] ?>" id="add">&laquo; Add to Report</a>
<?php			if ($sect['userdefined']==1) {			?>
					 | <a href="#" class="remove image" name="delete" id='delete' myID="<?php echo $sect['id'];?>">delete</a>
<?php			} else { ?>
					 | <span id='inactivedelete'>system</span>
<?php 			} ?>
					 | <a href="#" myID="<?php echo $sect['id'];?>" isUserDefined="<?php echo $sect['userdefined'] ?>" id="editsection">edit</a>					
					</div>
					<span class="section_title"><?php echo $sect['title']; ?></span>
				</li>
<?php
			} ?>
		</ul>
<?php			
		} else {
			print "<strong>No inactive sections.</strong>";
		}
		?>
	</div>


<br />
<hr /><br />
	
	
<?php echo text_output('Organize Sections', 'h3', 'page-subhead');?>
<?php echo text_output('Drag the sections to reorganize. The final report will be produced according to the order below.', 'p','fontSmall');?>


<?php /* print "<hr>".print_r($reorganize)."<hr>"; */ ?>

	
	<div class="UITheme">
		<?php
		if ((isset($sections[1])) && (isset($sections['order']))) { ?>
		<ul id="list">
<?php		
			foreach ($sections['order'] as $sec_ord => $sec_id) { ?>
				<li class="ui-state-default" id="sec_<?php echo $sec_id;?>">
					<div class="float_right"><a href="#" myID="<?php echo $sec_id;?>" isUserDefined="<?php echo $sections[1][$sec_id]['userdefined'] ?>" id="editsection">edit</a> | <a href="#" class="remove image" name="remove" myID="<?php echo $sec_id;?>" isUserDefined="<?php echo $sections[1][$sec_id]['userdefined'] ?>" myName="<?php echo $sections[1][$sec_id]['name']; ?>" id="remove">remove</a></div>
					<span class="section_title"><?php echo $sections[1][$sec_id]['title']; ?></span>
				</li>
<?php		} ?>
		</ul>
<?php			
		} else {
			print "<strong>No active sections.</strong>";
		}
		?>
<br />
	
<?php echo form_button($inputs['saveSections']);?>



<br />

<div id="awe_footer">

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" align="left" /></a><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><a href="http://github.com/mooeypoo/aweSimReport-2.0" target="_blank">aweSimReport for Nova</a></span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName"><a href="mailto:themoocode@gmail.com">Moriel Schottlender</a></span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>
NOVA is an RPG management software by <a href='http://www.anodyne-productions.com/' target='_blank'>Anodyne</a>.
	
</div>
