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
		alert('Coming soon, stay tuned!');
		return false;
	});
	
	$('#save').click(function(){
	<?php 	if ($this->uri->segment(4) !== FALSE) { ?>
		var loc ='<?php echo site_url('report/awesimreport/generator').$this->uri->segment(4) ?>';
	<?php	} else {  ?>
		var loc ='<?php echo site_url('report/awesimreport/generator'); ?>';
	<?php	} ?>

		$('#frmGenerate').attr('target', ''); //open the form in a new window
		$('#frmGenerate').attr('action',loc);
	});
	
	$('#preview').click(function(){
		$('#frmGenerate').attr('target', '_blank'); //open the form in a new window
		$('#frmGenerate').attr('action','<?php echo site_url('ajax/awe_preview_report_output') ?>');
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
                name: 'cream'
            }
        });
    });
	


});


	
</script>
