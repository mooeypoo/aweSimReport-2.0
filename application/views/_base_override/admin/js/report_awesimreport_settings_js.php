<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />

<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.maskedinput-1.2.2.min.js"></script>


<script type="text/javascript">

$(document).ready(function(){
	$('#tabs').tabs();
		
	$('table.zebra tbody > tr:nth-child(odd)').addClass('alt');

	$('.button-main').live('click', function() {
		var awe_txtDateFormat = $('#txtDateFormat').val();
		var awe_txtReportDuration = $('#txtReportDuration').val();
		var awe_txtStatOccurences = $('#txtStatOccurences').val();
		var awe_txtEmailSubject = $('#txtEmailSubject').val();
		var awe_txtEmailRecipients = $('#txtEmailRecipients').val();
		var awe_txtReportTitle = $('#txtReportTitle').val();
		var awe_txtTemplateFooter = $('#txtTemplateFooter').val();
		
		var awe_chkPresenceTags = (($('#chkPresenceTags').attr('checked'))==true ? 'checked' : '');
		var awe_txtPresenceTag_Present = $('#txtPresenceTag_Present').val();
		var awe_txtPresenceTag_Unexcused = $('#txtPresenceTag_Unexcused').val();
		var awe_txtPresenceTag_Excused = $('#txtPresenceTag_Excused').val();
		var awe_chkShowRankImagesRoster = ($('#chkShowRankImagesRoster').attr('checked')==true ? 'checked' : '');
		var awe_chkShowRankImagesCOC = ($('#chkShowRankImagesCOC').attr('checked')==true ? 'checked' : '');
		$.ajax({
			beforeSend: function(){
				$('#saving').show();
			},
			type: "POST",
			url: "<?php echo site_url('ajax/awe_settings_save');?>",
			data: { 
				txtDateFormat: awe_txtDateFormat,
				txtReportDuration: awe_txtReportDuration,
				txtStatOccurences: awe_txtStatOccurences,
				txtReportTitle: awe_txtReportTitle,
				txtEmailSubject: awe_txtEmailSubject,
				txtEmailRecipients: awe_txtEmailRecipients,
				chkPresenceTags: awe_chkPresenceTags,
				txtPresenceTag_Present: awe_txtPresenceTag_Present,
				txtPresenceTag_Unexcused: awe_txtPresenceTag_Unexcused,
				txtPresenceTag_Excused: awe_txtPresenceTag_Excused,
				chkShowRankImagesRoster: awe_chkShowRankImagesRoster,
				chkShowRankImagesCOC: awe_chkShowRankImagesCOC,
				txtTemplateFooter: awe_txtTemplateFooter,
			},
			success: function(data) {
			if (data=='success') {
				$('#ajaxnotice').removeClass('red');
				$('#ajaxnotice').addClass('green');
				$('#ajaxnotice').html('Data saved successfully.');
				$('#ajaxnotice').show();
			} else {
				$('#ajaxnotice').removeClass('green');
				$('#ajaxnotice').addClass('red');
				$('#ajaxnotice').html(data);
				$('#ajaxnotice').show();
			}
			},
			complete: function(){
				$('#saving').hide();
			}
		});
		return false;
		
	}); /*== end save Settings ==*/

});
	
</script>
