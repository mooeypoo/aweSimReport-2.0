<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />



<script type="text/javascript">

$(document).ready(function(){
 
    $("a[rel*=preview]").click(function() {
        var myID = $(this).attr('myID');
        var myTemplID = $(this).attr('myTemplID');
        var myDateSent = $(this).attr('myDateSent');

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('ajax/awe_preview_report');?>",
                data: {
                    repID: myID ,
                    templID: myTemplID ,
                    dateSent: myDateSent },
                    success: function(data){
			$.facebox(data);
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                },
            });

        return false;
    });


});
	
</script>
