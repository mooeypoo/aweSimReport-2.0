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








<br />

<div id="awe_footer">

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" align="left" /></a><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><a href="http://github.com/mooeypoo/aweSimReport-2.0" target="_blank">aweSimReport for Nova</a></span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName"><a href="mailto:themoocode@gmail.com">Moriel Schottlender</a></span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>
NOVA is an RPG management software by <a href='http://www.anodyne-productions.com/' target='_blank'>Anodyne</a>.
	
</div>
