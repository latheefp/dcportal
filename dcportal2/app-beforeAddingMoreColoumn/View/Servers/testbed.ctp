<?php
echo $this->Html->script('jquery.maskedinput.min');
echo $this->Html->script('jquery.MetaData');
echo $this->Html->script('jquery.form');
echo $this->Html->script('jquery.MultiFile.pack');
echo $this->Html->script('jquery.watermarkinput.js');
?>

<script type="text/javascript">
    $(document).ready(function(){

        $("#radio").buttonset();
        $("#AdvSearch").click(function(){ 
            //alert ("Hai");
            AdvSearch(10)
        });
        //$('button').button()
        $('.rowEdit1').bind("dblclick", function(){
            
            editRow(this);
        });
        $('.serverDetails').click(function(){
            serverDetails($(this).attr('rowid'));
            return false;
        });
        
    });
        
        function AdvSearch(id){
       // alert("AdvanceSearch");
        var title='Advanced Search';
         $.ajax({
            cache: false,
            async: false,
            url: "<?php echo $this->Html->url("/servers/getHostname/"); ?>"+id,
            success: function(msg){
                title = 'Server details - ' + msg;
                }});
        $('#tab2').attr('href', '<?php echo $this->Html->url("/servers/getAudit/"); ?>'+id);
        $("#dialog-serverDetails").dialog({
            autoOpen: false,
            height: 500,
            width: 550,
            title: title,
            modal: true,
            show: 'slide',
            hide: 'explode',
            buttons: {
                'Close': function() {
                    $(this).dialog('close');

                }
            },
            close: function() {
                $("#serverDetailstabs").tabs( "destroy" );
                //alert('width:' +$(this).dialog( "option", "width" )+"\n"+'height:' +$(this).dialog( "option", "height" ));
            }

        });
        };   
    
    

</script>