<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />


<script type="text/javascript">
$(document).ready(function(){
	
	$('#templatecontainer a').live('click',function(){
		var id = $(this).attr('myID');
		var activeid = $('.activetmpl').attr('myID');
		$.ajax({
			beforeSend: function(){
				$('#saving').show();
			},
			type: "POST",
			url: "<?php echo site_url('ajax/awe_switch_templates');?>",
			data: { tmplid: id },
			success: function(data){
				$('#ajaxnotice').html(data);
				$('#ajaxnotice').show();
			},
			complete: function(){
				$('#saving').hide();
/*				$(this).addClass('activetmpl'); */
				$('#tid_' + id).addClass('activetmpl');
				$('#tid_' + activeid).removeClass('activetmpl');
			}
		});
		
		return false;
	});
	
	
});
	
</script>
