<?php $string = random_string('alnum', 8);?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/css/awesimreport_general.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url() . APPFOLDER;?>/assets/js/css/jquery.ui.datepicker.css" />
<script type="text/javascript" src="<?php echo base_url() . APPFOLDER;?>/assets/js/jquery.ui.datepicker.min.js"></script>


<script type="text/javascript">

$(document).ready(function(){
 

     $("a[rel*=publish]").click(function() {
        var imgPub = '<?php echo base_url().img_location('user-accept.png', $this->skin, 'admin'); ?>';
        var imgUnpub = '<?php echo base_url().img_location('user-reject.png', $this->skin, 'admin'); ?>';
        var action = $(this).attr('myAction');
            var id = $(this).attr('myID');

//         alert(action + ' > ' + id);

            $.ajax({
                beforeSend: function(){
                    $('#pub' + id).hide();
                    $('#saving' + id).show(); 
                },
                type: "POST",
                url: "<?php echo site_url('ajax/awe_publish_archive');?>",
                data: {
                    act: action ,
                    tid: id },
                success: function(data){
//                  alert(data);
                    if (data=='success') {
//                        alert(data);
                        if (action == 'publish') {
                            $('#pub' + id + ' a').attr('myAction','unpublish');
                            $('#pub' + id + ' img').attr('src',imgPub);
                            $('#pub' + id + ' img').attr('title','Published (Click to make private)');
                        } else {
                            $('#pub' + id + ' a').attr('myAction','publish');
                            $('#pub' + id + ' img').attr('src',imgUnpub);
                            $('#pub' + id + ' img').attr('title','Hidden (Click to make public)');
                        } 
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function(){
                    $('#pub' + id).show();
                    $('#saving' + id).hide(); 
                }
            });
            return false;
            
        });

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
