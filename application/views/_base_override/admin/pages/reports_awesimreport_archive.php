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

<br />
<?php	
    //pagination:
    if ($pages > 1) {
        echo '<table class="table100" align="center">';
        echo '<tr><td colspan=\'5\' align=\'center\'>';
        echo '<table><tr>';
//      echo '<td>Pages: </td>';
        if ($pg > 1) { //not last
            echo '<td>'.anchor('report/awesimreport/archive/'.($pg - 1), '<').'</td>';
        }
        for ($i=1; $i<=$pages; $i++) {
            if ($i == $pg) { 
                echo '<td style="font-size: 110%;"><b>'.$i.'</b></td>';
            } else {
                echo '<td>'.anchor('report/awesimreport/archive/'.$i, $i).'</td>';
            }
        }
        if ($pg != $pages) { //not last
            echo '<td>'.anchor('report/awesimreport/archive/'.($pg + 1), '>').'</td>';
        }
        echo '</tr></table>';
        echo '</td></tr>';
        echo '</table>';
    }
?>
<table class="zebra table100" align="center">
    <thead>
        <tr>
            <th>#</th>
            <th>Date Range</th>
            <th>Reporting Officer</th>
            <th>Display</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php   if (!empty($archive)) {
            $counter=($pg*10 - 10) + 1;
            foreach ($archive as $item) { ?>
            <tr>
                <td><?php echo $counter; ?></td>
	                <td align="center">
<?php /*			<a href="<?php echo site_url('ajax/awe_preview_report_output/'.$item['id'].'/'.$item['template']);?>" target="_blank">*/ ?>
			<a rel='preview' href="#" myID='<?php echo $item['id']; ?>' myTemplID='<?php echo $item['template']; ?>' myDateSent='<?php echo $item['date_sent']; ?>'>
<?php		if ((empty($item['date_start'])) || (empty($item['date_start']))) { ?>
				No date selected.
<?php		} else { ?>
				<?php echo $item['date_start'].' to '.$item['date_end']; ?>
<?php		} ?>
			</a>
			</td>
                <td><?php echo $item['repofficer']; ?><br />
                <span class="fontTiny"><?php echo $item['repofficer_position']; ?></span><br />
                <span class="fontTiny"><?php echo anchor('personnel/user/'.$item['repofficer_userid'], 'User Account'); ?> | <?php echo anchor('personnel/character/'. $item['repofficer_charid'], 'Character Bio'); ?></span>
                </td>
                <td class="col_75" align='center'>
                    <div id='pub<?php echo $item['id'] ?>'><?php /*echo $item['display'].'<br><br>';*/ ?>
<?php           if ($item['display']=='hidden') { ?>
                    <a href="#" rel="publish" myAction="publish" myID="<?php echo $item['id'];?>" class="image" title="<?php echo $item['display']; ?>"><?php echo img($images['icons']['hidden']);?></a> 
<?php           } else { ?>
                    <a href="#" rel="publish" myAction="unpublish" myID="<?php echo $item['id'];?>" class="image" title="<?php echo $item['display']; ?>"><?php echo img($images['icons']['published']);?></a> 
<?php           }   ?>
                    </div>
                    <div id="saving<?php echo $item['id'] ?>" class="hidden"><?php echo img($images['icons']['loading']);?></div>
                </td>
                <td class="col_75" align='center'>
                    <?php echo img($images['icons']['delete']);?>
                </td>
            </tr>
<?php       
                $counter++;
            }
        } else { ?>
            <tr><td colspan="4">No archived reports found.</td></tr>
<?php   } ?>
    </tbody>
</table>

<?php
    //pagination:
    if ($pages > 1) {
        echo '<table class="table100" align="center">';
        echo '<tr><td colspan=\'5\' align=\'center\'>';
        echo '<table><tr>';
//      echo '<td>Pages: </td>';
        if ($pg > 1) { //not last
            echo '<td>'.anchor('report/awesimreport/archive/'.($pg - 1), '<').'</td>';
        }
        for ($i=1; $i<=$pages; $i++) {
            if ($i == $pg) { 
                echo '<td style="font-size: 110%;"><b>'.$i.'</b></td>';
            } else {
                echo '<td>'.anchor('report/awesimreport/archive/'.$i, $i).'</td>';
            }
        }
        if ($pg != $pages) { //not last
            echo '<td>'.anchor('report/awesimreport/archive/'.($pg + 1), '>').'</td>';
        }
        echo '</tr></table>';
        echo '</td></tr>';
        echo '</table>';
    }
?>


<br />

<div id="awe_footer">

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" align="left" /></a><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><a href="http://github.com/mooeypoo/aweSimReport-2.0" target="_blank">aweSimReport for Nova</a></span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName"><a href="mailto:themoocode@gmail.com">Moriel Schottlender</a></span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>
NOVA is an RPG management software by <a href='http://www.anodyne-productions.com/' target='_blank'>Anodyne</a>.
	
</div>
