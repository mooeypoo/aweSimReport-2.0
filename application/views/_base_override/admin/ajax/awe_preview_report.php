<?php echo text_output($header, 'h2');?>

Date sent: <?php echo $report['DateSent']; ?>

<iframe src="<?php echo site_url('ajax/awe_preview_report_output/'.$params['repID'].'/'.$params['templID']);?>" style="width:100%; height: 500px; overflow: scroll; border: 2px solid #000;">
</iframe> 