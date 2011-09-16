<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/js/css/jquery.ui.datepicker.css" />
<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.ui.datepicker.min.js"></script>


<script type="text/javascript">

$(document).ready(function(){
	$('#list').sortable({
		forcePlaceholderSize: true,
		placeholder: 'ui-state-highlight'
	});
	$('#list').disableSelection();
		
	if ($('#list li').length > 0) {
		$('.submit-div').show();
	}
	

	$('#addSection').live('click', function(){
		var location = '<?php echo site_url('ajax/awe_add_section/'. $string);?>';
			
		$.facebox(function() {
			$.get(location, function(data) {
				$.facebox(data);
			});
		});
		
		return false;
	});
	
	$('#editsection').live('click', function(){
		var id = $(this).attr('myID');
		var isUserDefined = $(this).attr('isUserDefined');
		var location = '<?php echo site_url('ajax/awe_edit_section');?>/' + id + '/<?php echo $string;?>';
		$.facebox(function() {
			$.get(location, function(data) {
				$.facebox(data);
			});
		});
		
		return false;
	});
	
	
	$('#saveSections').live('click', function(){
		var parent = $(this).parent().parent().attr('class');
		var list = $('#list').sortable('serialize');
		$.ajax({
			beforeSend: function(){
				$('#saving').show();
				$('#saveSections').attr('disabled', 'disabled');
			},
			type: "POST",
			url: "<?php echo site_url('ajax/awe_save_active_sections');?>",
			data: list,
			success: function(data){
				$('.flash_message').remove();
				$('.' + parent).prepend(data);
			},
			complete: function(){
				$('#saving').hide();
				$('#saveSections').attr('disabled', '');
			}
		});
	
		return false;
	});
	
	$('#add').live('click', function(){ 
		var id = $(this).attr('myID');
		var secname = $(this).attr('name');
		var sectitle = $('#sec_' + id + ' > .section_title').html();
		var userdefined = $(this).attr('isUserDefined');

		if (id > 0) {
			$.ajax({
				beforeSend: function(){
					$('#loading').show();
				},
				type: "POST",
				url: "<?php echo site_url('ajax/awe_add_active_section');?>",
				data: { secid: id },
				success: function(data){

					var content = '<li class="ui-state-default" id="sec_' + id + '">';
						content += '<div class="float_right">';
						content += '<a href="#" myID="' + id + '" id="editsection">edit</a> | ';
						content += '<a href="#" class="remove image" name="remove" myID="' + id + '" isUserDefined="' + userdefined + '" myName="' + secname + '" id="remove">remove</a></div>';
						content += '<span class="section_title">' + sectitle + '</span>';
						content += '</li>';
						
					$('.flash_message').remove();
					$(content).hide().appendTo('#list').fadeIn('slow');
					$('#unusedlist > #sec_' + id).remove();
				},
				complete: function(){
					$('#list > #sec_' + id).fadeIn('slow', function(){
						$('#loading').hide();
					});
					$('.sitemsg').show();
				}
			});
			
			$('#list').sortable('refresh');
		}
		return false;
	});
	
	$('#remove').live('click', function(){ 
		var id = $(this).attr('myID');
		var secname = $(this).attr('name');
		var sectitle = $('#sec_' + id + ' > .section_title').html();
		var userdefined = $(this).attr('isUserDefined');

		if (id > 0) {
			$.ajax({
				beforeSend: function(){
					$('#loading').show();
				},
				type: "POST",
				url: "<?php echo site_url('ajax/awe_remove_active_section');?>",
				data: { secid: id },
				success: function(data){
					var content = '<li class="ui-state-default" id="sec_' + id + '">';
						content += '<span class="section_title">' + sectitle + '</span>';
						content += '<div class="float_right">';
						content += '<a href="#" class="remove image" myID="' + id + '"';
						content += 'isUserDefined="' + userdefined + '" myName="' + secname + '" id="add">&laquo; Add to Report</a>';
					if (userdefined==1) {
						content += ' | <a href="#" class="remove image" myID="' + id + '" name="delete" id="delete" myID="' + id + '">delete</a>';
					} else {
						content += ' | <span id="inactivedelete">system</span>';
					}
					content += ' | <a href="#" myID="' + id + '" id="editsection">edit</a>';
					content += '</li>';
					$('.flash_message').remove();
					$(content).hide().appendTo('#unusedlist').fadeIn('slow');
					$('#list > #sec_' + id).remove();
				},
				complete: function(){
					$('#unusedlist > #sec_' + id).fadeIn('slow', function(){
						$('#loading').hide();
					});
					$('.sitemsg').show();
				}
			});
			
			$('#list').sortable('refresh');
		}
		return false;
	});

	$('#delete').live('click', function(){ 
		var id = $(this).attr('myID');

		conf = confirm('Are you sure you want to delete this section? This action is permanent, and cannot be undone!');

		if (conf == true) {
			$.ajax({
				beforeSend: function(){
					$('#loading').show();
				},
				type: "POST",
				url: "<?php echo site_url('ajax/awe_delete_section_permanently');?>",
				data: { secid: id },
				success: function(data){
					$('.flash_message').remove();
					$('#unusedlist > #sec_' + id).fadeOut('fast');
					$('.sitemsg').html(data);
				},
				complete: function(){
					$('#loading').hide();
					$('.sitemsg').show();
				}
			});
			
			$('#list').sortable('refresh');
		} 
		return false;
	});

	
});
	
</script>
