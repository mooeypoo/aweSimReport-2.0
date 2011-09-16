<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />

<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.ui.datepicker.min.js"></script>
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

		
	$('#submitDelete').click(function(){
		return confirm('Are you sure you want to delete this report?\n This action is permanent and cannot be undone!');
	});
	
	$('#submitGenerate').click(function(){
		alert('Coming soon, stay tuned!');
		return false;
	});
	
	$('#submitPreview').click(function(){
		alert('Coming soon, stay tuned!');
		return false;
	});
	
	
/*	$('#saveReport').live('click', function(){
		var info = $('#frmGenerate').serialize().replace(/%5B/g, '[').replace(/%5D/g,']');
		var inf = '1';
		var location = '<?php echo site_url('ajax/awe_save_report/'. $string);?>';
			
		$.facebox(function() {
			$.get(location, function(data) {
				$.facebox(data);
			});
		});
		
		$.ajax({
			beforeSend: function(){
				$('#saving').show();
				alert('blah');
			},
			type: "POST",
			url: "<?php echo site_url('ajax/awe_report_save');?>",
			data: info,
			success: function(data) {
				alert(data);
			},
			complete: function(){
				$('#saving').hide();
			}
		}); 
		return false;
	}); 
*/	
	
});


	
</script>
