<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />

<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.qtip.js"></script>
<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.popupWindow.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/js/css/jquery.ui.datepicker.css" />


<script type="text/javascript">

$(document).ready(function(){
	$('table.zebra tbody > tr:nth-child(odd)').addClass('alt');
	
	var dates = $( "#txtReportDateStart, #txtReportDateEnd" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: 'm/d/yy', 
			onSelect: function( selectedDate ) {
				var option = this.id == "txtReportDateStart" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
			});

	dates.closest('body').find('#ui-datepicker-div').wrap('<span class="UITheme"></span>');
		
	$('#submitDelete').click(function(){
		return confirm('Are you sure you want to delete this report?\n This action is permanent and cannot be undone!');
	});
	
	$('#submitGenerate').click(function(){
		var genMessage  = 'You chose to generate this report with the following settings:\n\n';
<?php	if ($RosterActive > 0) { ?>
		if (($('#txtReportDateStart').val()=='') || ($('#txtReportDateEnd').val()=='')) {
			alert('You chose to have a Roster, but you did not fill out dates.\n Please go back and choose dates for this report period.');
			return false;
		}
		genMessage += 'Dates: ' + $('#txtReportDateStart').val() + ' to ' + $('#txtReportDateEnd').val() + '\n';
<?php	} ?>		
<?php	if ((empty($email["mailsubject"])) || (empty($email["mailrecipients"]))) { ?>
			alert('The Email settings you have provided are insufficient. Please go to "Settings" and choose an Email Subject and Email Recepients.');
			return false;
<?php	} ?>
		genMessage += 'Email From: <?php print $email['myname'].'<'.$email['myaddress'].'>'; ?>\n';
		genMessage += 'Email Recepients: <?php print implode(', ',$email["mailrecipients"]); ?>\n';
		genMessage += 'Email Subject: <?php print $email["mailsubject"]; ?>\n\n';
		genMessage += 'Are you sure you want to generate and send this report?';
		if (confirm(genMessage)) {
			var loc ='<?php echo site_url('report/awesimreport/generator/'); ?>';
			$('#frmGenerate').attr('target', ''); //open the form in a new window
			$('#frmGenerate').attr('action',loc);
			$('#frmGenerate').get(0).setAttribute('action', loc);
		}
	});
	
	$('#save').click(function(){
	<?php 	if ($this->uri->segment(4) > 0) { ?>
		var locsave ='<?php echo site_url('report/awesimreport/generator').'/'.$this->uri->segment(4) ?>';
	<?php	} else {  ?>
		var locsave ='<?php echo site_url('report/awesimreport/generator/'); ?>';
	<?php	} ?>

			$('#frmGenerate').attr('target', ''); //open the form in a new window
			$('#frmGenerate').attr('action',locsave);
			$('#frmGenerate').get(0).setAttribute('action', locsave);
	});
	
	$('#preview').click(function(){
		$('#frmGenerate').attr('target', '_blank'); //open the form in a new window
		$('#frmGenerate').attr('action','<?php echo site_url('ajax/awe_preview_report_output') ?>');
		$('#frmGenerate').get(0).setAttribute('action', '<?php echo site_url('ajax/awe_preview_report_output') ?>');
	});
	
    // Notice the use of the each method to gain access to each element individually
    $('#savedReports a').each(function()
    {
        // Grab Details:
	var repid = $(this).attr('myid');
	var repauthor = $(this).attr('myauthor');
	var repdates = $(this).attr('mydates');

        // Create image thumbnail using Websnapr thumbnail service
        contitle  = 'Report ID: ' + repid + '';

        conts = '<strong>Report Author:</strong> ' + repauthor + '<br />';
        conts += '<strong>Report Dates:</strong> ' + repdates + '<br />';

        // Setup the tooltip with the content
        $(this).qtip(
        {
            content: {
		title: contitle,
		text: conts },
            position: {
                corner: {
                    tooltip: 'bottomMiddle',
                    target: 'topMiddle'
                }
            },
            style: {
                tip: true, // Give it a speech bubble tip with automatic corner detection
                name: 'dark'
            }
        });
    });
	


});


	
</script>
