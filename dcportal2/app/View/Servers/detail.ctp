<?php
echo $this->Html->script('jquery.maskedinput.min');
echo $this->Html->script('jquery.MetaData');
echo $this->Html->script('jquery.form');
echo $this->Html->script('jquery.MultiFile.pack');
echo $this->Html->script('jquery.watermarkinput.js');
?>
<script type="text/javascript">
    $(document).ready(function(){
            $(function() {
            $( "#tabs" ).tabs();
            $( "button" ).button();
//             $( "#accordion" ).accordion({
//                 header: "h3",
//               active: 3,
//               collapsible: true,
//               autoHeight: true
//             });
        });
        
//        function UpdateComment(){
//        alert("hi");
//        }
        
        $("#addcomment").click(function () {
            $("#postComment").show("slow");
        });
        
        $.ajax({
            cache: false,
            async: false,
            url: "<?php echo $this->Html->url("/servers/getHostname/".$id); ?>",
            success: function(msg){
                title = 'Server details - ' + msg;
                //alert(msg);
            }
        });

        $('.rowEdit1').bind("dblclick", function(){ 
            editRow(this);
            
        });
        
        
        
        function editRow(myTD){
        var dialogmodal=true;
        var currentValue = $(myTD).text();
        var index=myTD.cellIndex
        var myTH = $(myTD).closest('tr');
        var myType = $(myTH).attr('type');        
        //alert(myType);
        $("#dialog-inlineedit").attr('title','Edit ' + $(myTH).attr('table') + ' - ' + ($('#tblServers th:nth-child(' + (index+1) + ')').text()));
       
        //$("#inline-editor-head").html($(myTH).text());
         $("#inline-editor-head").html($(myTH).find("td:first").text());
         //alert ($(myTH).find("td:first").text());
        $("#inline-editor-form").html('');
        
        var inlinewidth=400;
        var inlineheight=300;
        /*
         * TODO add max and min for integer numbers
         * TODO Use http://keith-wood.name/countdown.html as a format
         * TODO add http://code.google.com/p/jquery-utils/wiki/TimeAgo
         * TODO Support phone, emails, url
         */

        $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "<?php echo $this->Html->url(array("controller" => "servers","action" => "getColumnValue")); ?>",
            data: "name="+$(myTH).attr('name')+"&id="+$(myTD).attr('rowid')+"&table="+$(myTH).attr('table'),
            success: function(msg){
                currentValue = msg;
            }
        });
        //$("#inline-editor-form").append('<b>'+$(myTH).attr('type')+'</b>');
        if((myType=='multiselect') && ($(myTH).attr('extra').split(',').length < 7)){ myType ='checkbox';}
        if((myType=='select') && ($(myTH).attr('extra').split(',').length < 7)){ myType ='radio';}

        $("#inline-editor-form").append('<input type=hidden name=table value="'+$(myTH).attr('table')+'">');
        $("#inline-editor-form").append('<input type=hidden name=name value="'+$(myTH).attr('name')+'">');
        $("#inline-editor-form").append('<input type=hidden name=id value="'+$(myTD).attr('rowid')+'">');
        $("#inline-editor-form").append('<input type=hidden name=type value="'+myType+'">');
        
                switch(myType)
        {
            case 'integer':
                $("#inline-editor-form").append('<input type="text" id=data name=data value="'+currentValue+'"><br /><span style="color:red;" id="errmsg"></span>');
                inlinewidth=250;
                inlineheight=210;
                //called when key is pressed in textbox
                $("#data").keypress(function (e)
                {
                    //if the letter is not digit then display error and don't type anything
                    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
                    {
                        //display error message
                        $("#errmsg").html("Digits Only").show().fadeOut("slow");
                        return false;
                    }
                });
                break;
            case 'date':
                $("#inline-editor-form").append('<div id="datepicker"></div><input type="hidden" id=data name=data value="'+currentValue+'">');
                inlinewidth=318;
                inlineheight=380;
                $("#datepicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(dateText, inst) { $('#data').val(dateText); }
                });
                if(currentValue!='')$("#datepicker").datepicker( "setDate" , currentValue );
                //$("#datepicker").datepicker('show');
                break;
                
            case 'time':
                $("#inline-editor-form").append('<input type="text" id=data name=data value="'+currentValue+'">');
                inlinewidth=250;
                inlineheight=210;
                $.mask.definitions['s']='[0-2]';
                $("#data").mask("s9:99");
                break;
            case 'datetime':
                $("#inline-editor-form").append('<input type="text" id=data name=data value="'+currentValue+'">');
                inlinewidth=250;
                inlineheight=210;
                $("#data").mask("9999-99-99 99:99");
                break;
            case 'string':
                $("#inline-editor-form").append('<input type="text" id=data maxlength='+$(myTH).attr('length')+ ' name=data value="'+currentValue+'">');
                inlinewidth=250;
                inlineheight=210;
                break;
            case 'text':
                $("#inline-editor-form").append('<textarea rows=4 id=data name=data>'+currentValue+'</textarea>');
                inlinewidth=400;
                inlineheight=300;
                break;
            case 'select':
                var options = $(myTH).attr('extra').split(',');
                var len=options.length;
                var Myselect ='';
                if(len>7){
                    inlinewidth=250;
                    inlineheight=210;
                    Myselect ='<select id=data name=data>';
                    var CheckedString='';
                    for(var i=0; i<len; i++) {
                        if(currentValue == options[i]) {CheckedString='selected="selected"'}
                        Myselect = Myselect + ('<option '+CheckedString+'>'+options[i]+'</option>');
                        CheckedString='';
                    }
                    Myselect = Myselect + '</select>';

                }else{
                    inlinewidth=250;
                    inlineheight=150;
                    Myselect ='<div align=left>';
                    var CheckedString='';
                    for(var i=0; i<len; i++) {
                        if(currentValue == options[i]) {CheckedString='checked="checked"'}
                        Myselect = Myselect + ('<label><input type="radio" name="data[]" value="'+options[i]+'" id="'+options[i]+'" '+CheckedString+' /> '+options[i]+'</label><br />');
                        inlineheight += 23;
                        CheckedString='';
                    }
                    Myselect = Myselect + '</div>';

                }

                $("#inline-editor-form").append(Myselect);

                break;
            case 'multiselect':
                var options = $(myTH).attr('extra').split(',');
                var Myselect ='';
                var len=options.length;
                currentValue = ',' + currentValue + ',';
                inlinewidth=450;
                inlineheight=426;
                Myselect ='<select id=data class="multiselect" multiple="multiple" name=data[]>';
                var CheckedString='';
                for(var i=0; i<len; i++) {
                    if(currentValue.search(','+options[i]+',')>=0) {CheckedString='selected="selected"'}
                    Myselect = Myselect + ('<option '+CheckedString+'>'+options[i]+'</option>');
                    CheckedString='';
                }
                Myselect = Myselect + '</select>'
                $("#inline-editor-form").append(Myselect);


                break;
            case 'file': //$(myTH).attr('extra') = max-3 accept-gif|jpg
                /*
                 * TODO make a file size limitation
                 */
                var options = $(myTH).attr('extra');
                //if(options == '') { options='max-1 accept-docx|pdf|xls|xlsx'; }
                var accept = (options.match(/\b(accept\-[\w\|]+)\b/gi)) || '';
                accept = new String(accept).replace(/^(accept|ext)\-/i,'');
                accept = new String(accept).replace(/\|/gi,' ');
                if(accept ==''){accept='Any';}

                var filescount = (String(options.match(/\b(max|limit)\-([0-9]+)\b/gi) || ['']).match(/[0-9]+/gi) || [''])[0];

                if(filescount ==''){filescount='Any';}else{filescount = filescount.match(/[0-9]+/gi)[0];}

                $("#inline-editor-form").append('<div align=left><small>Allowed Files: '+accept+'<br />Limit: '+filescount+'<br /><font color=red><b>Note: Upload file will overwrite the field</b></font></small></div><br /><input type="file" id=data class="multi '+options+'" name=data[]>');
                inlinewidth=364;
                inlineheight=286;
                $('#data').MultiFile();
                break;

            case 'html':
                $('#data').remove();
                $("#inline-editor-form").append('<textarea rows=4 class="tinymce" id=data name=data>'+currentValue+'</textarea>');
                inlinewidth=364;
                inlineheight=286;

                /*
                 * TODO Fix html type and make sure html tags will not be removed
                 *
                 */
                /*tinyMCE.init({
                                mode : "textareas",
                                theme : "simple",

                                setup : function(ed) {
                                        ed.onInit.add(function(ed) {
                                                //alert('Editor is done: ' + ed.id);
                                                tinyMCE.get('data').setContent(currentValue);
                                                tinyMCE.execCommand('mceRepaint');
                                        });

                                }


                        });*/

                break;
            case 'radio':
                var options = $(myTH).attr('extra').split(',');
                var len=options.length;
                var Myselect ='';

                inlinewidth=250;
                inlineheight=150;
                Myselect ='<div align=left>';
                var CheckedString='';
                for(var i=0; i<len; i++) {
                    if(currentValue == options[i]) {CheckedString='checked="checked"'}
                    Myselect = Myselect + ('<label><input type="radio" name="data[]" value="'+options[i]+'" id="'+options[i]+'" '+CheckedString+' /> '+options[i]+'</label><br />');
                    inlineheight += 23;
                    CheckedString='';
                }
                Myselect = Myselect + '</div>';
                $("#inline-editor-form").append(Myselect);

                break;
            case 'checkbox':
                var options = $(myTH).attr('extra').split(',');
                var Myselect ='';
                currentValue = ',' + currentValue + ',';
                var len=options.length;
                inlinewidth=250;
                inlineheight=150;
                Myselect ='<div align=left>';
                var CheckedString='';
                for(var i=0; i<len; i++) {
                    if(currentValue.search(','+options[i]+',')>=0) {CheckedString='checked="checked"'}
                    Myselect = Myselect + ('<label><input type="checkbox" name="data[]" value="'+options[i]+'" id="'+options[i]+'" '+CheckedString+' /> '+options[i]+'</label><br />');
                    inlineheight += 23;
                    CheckedString='';
                }
                Myselect = Myselect + '</div>';

                $("#inline-editor-form").append(Myselect);
                break;

            default:


            } //end of case

             $("#inline-editor-form").append('</form>');
             //alert ($('#inline-editor-form').html());

            $('#inline-editor-form').ajaxForm({
                target:        myTD,   // target element(s) to be updated with server response
                beforeSubmit:  doBeforeSubmit,  // pre-submit callback
                //success:       myTD,  // post-submit callback
                data: $('#inlineEditor').serialize()

                // other available options:
                //url:       url         // override for form's 'action' attribute
                //type:      type        // 'get' or 'post', override for form's 'method' attribute
                //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                //clearForm: true        // clear all form fields after successful submit
                //resetForm: true        // reset the form after successful submit
                //beforeSubmit: validate

                // $.ajax options can be used here too, for example:
                //timeout:   3000

            });

            var dlg = $("#dialog-inlineedit").dialog({
                autoOpen: false,
                height: inlineheight,
                width: inlinewidth,
                modal: dialogmodal,
                show: 'slide',
                hide: 'explode',
                buttons: {
                    'Update': function() {
                        $('#inline-editor-form').submit();
                        $(this).dialog('close');
                    },
                    Cancel: function() {
                        $(this).dialog('close');
                    }
                },
                close: function() {
                    //alert('width:' +$(this).dialog( "option", "width" )+"\n"+'height:' +$(this).dialog( "option", "height" ));
                }


            });
            $('#dialog-inlineedit').dialog('open');

            function doBeforeSubmit(formData, jqForm, options) {
            $('#dialog-inlineedit').dialog('close');
            return true;
            }

            function submitMySettings(){

            $('#showing option').each(function(i) {$(this).attr("selected", "selected");});

            var selectlist = '';
            var serveralertedit = ''
            $('#showing :selected').each(function(i, selected){
                if(selectlist != '') selectlist = selectlist + "\n";
                selectlist = selectlist +  $(selected).val();
            });
            serveralertedit = $('input:radio[name=server_alertedit]:checked').val();
            if((serveralertedit!='1') && (serveralertedit!='0')){
                serveralertedit = '<?php echo $this->Session->read('User.server_alertedit');?>'
            }

            $.ajax({
                type: "POST",
                cache: false,
                url: "<?php echo $this->Html->url(array("controller" => "users","action" => "update")); ?>",
                data: "server_perpage="+$('#server_perpage').val()+"&server_show="+selectlist+"&server_alertedit="+serveralertedit,
                success: function(msg){
                    $("#dialog-confirm").dialog({
                        resizable: false,
                        height:170,
                        modal: true,
                        buttons: {
                            'Reload': function() {
                                $(this).dialog('close');
                                window.location = '/';
                            },
                            Cancel: function() {
                                $(this).dialog('close');
                            }
                        }
                    }); 
                }
            }); //end of ajax

        }//end of submit settings

        
        }//edit of CellEdit
        
//function loadData(){
//        loadAudit();
//        loadComment();
//    }





}); //End of Document.Ready

function UpdateComment(){
                 $.post(
                '/servers/UpdateComment',
                {server_id:<?php echo $id?>, content:$('#comment').val() })
                .done(function( logs ) {
                   // alert (logs);
                   // $('#demo').html(logs);
                    $('#postComment').hide();
                    $('#comment').val("");
                    AppendComment(logs)
                    });
            }
            
function AppendComment(logs){
    
    if (logs != "ERROR"){
        
        $(logs).insertBefore($('#commenttbody tr:first'));
    }else{
        alert (logs);
    }
    
}            
            
function CanellComment(){
    $('#postComment').hide();
    $('#comment').val("");
}
                    
$( window ).load(function() {
    loadAudit();
    loadComment();
    //loadSysdetail();
});


function loadAudit(){
        $.ajax({
        url: '/servers/getAudit/<?php print $id;?>'})
         .done(function( logs ) {
             $('#auditlog').html(logs);
        });
    }
    
function loadComment(){
        $.ajax({
        url: '/servers/getComment/<?php print $id;?>'})
         .done(function( logs ) {
             $('#commenttable').html(logs);
        });
    }

function loadSysdetail(){
        $.ajax({
        url: '/servers/sysDetails/<?php print $id;?>'})
         .done(function( logs ) {
             $('#tabs-1').html(logs);
        });
    }

function deleteSystem(id){
    if(confirm('Delete server?')){
                $.ajax({
                url: '/servers/DeleteServer/'+id})
                .done(function( logs ) {
                $('#debug').html(logs);  
                if (logs != "ERROR"){
                    window.location = '/';
                }
                                   
        });
    }
}


</script>
<?php //echo debug ($data);?>
<div  id ="controlls">
    <button role="button" id="buttonDelete" class="controllbutton" onclick="deleteSystem(<?php echo $id?>);"> Delete  </button>
</div>


<div id="tabs">
  <ul>
    <li><a href="#tabs-1">System Details</a></li>
    <li><a href="#tabs-2">Audit Logs</a></li>
    <li><a href="#tabs-3">Comments</a></li>
    
  </ul>
  <div id="tabs-1">
          <table id="sysdetail" class="listall">
            <tbody>
           <?php
            $count=1;
            foreach($fieldUserInfo as $name=>$val) {
                print "<tr ";
               // print '<td ';
                print ($count % 2 == 0) ? " class='altrow'" : "";
                $count++;
                print 'table="'.$val['table'].'" name="'.$name.'" editable="'.$val['editable'].'" extra="'.$val['extra'].'" ';
                if($fieldUserInfo[$name]['type_overwrite']!='') {
                    print 'type="'.$fieldUserInfo[$name]['type_overwrite'].'" ';
                }else {
                    print 'type="'.@$schema[$name]['type'].'" ';
                }
                print 'null="'.@$schema[$name]['null'].'" ';
                //echo debug (@$schema[$name]);
                print 'default="'.@$schema[$name]['default'].'" ';
                print 'length="'.@$schema[$name]['length'].'" ';
                print 'key="'.@$schema[$name]['key'].'" ';
                print ' nowrap> ';
                print "<td>";
                print $val['title'];
                if($val['editable']=='1') {
                        print '<img title="Double click any field below to edit." style="float:left; padding-right: 2px;" src="'.$this->Html->url('/img/edit16.png').'">';
                    }
                print   "</td>";
                print '<td rowid="'. $id .'" class="rowEdit'.$val['editable'].'" >';
               // echo debug ($val);
                print $FieldModel->showField($data['0']['Server'][$name], $fieldUserInfo[$name]);
                print '</td></tr>';
                
           }
            ?>
                
            </tbody>
        </table>
  </div>
    <div id="tabs-2">
        <div id="auditlog"></div>    
    </div>
    
    
  <div id="tabs-3">
        <div id="addcomment">
            <a href='#' id="addcomment" class="addcomment">Add Comment</a>
        </div>
        <div id='postComment' class='postComment'>
            <textarea name='comment' id='comment' class='comment'></textarea>
            <input type="submit" value="Submit" onclick="UpdateComment()">
            <input type="submit" value="Cancel" onclick="CanellComment()">
        </div>
      <div id="demo"> </div>
      <div id="commenttable"> </div>      
  </div>
</div>

    <div id="dialog-inlineedit"  style="display:none;" title="Inline Editor">
    <b><h3 id='inline-editor-head' align="center"></h3></b>
    <div id='inline-editor-body' align="center"><form id="inline-editor-form" action="<?php echo $this->Html->url(array("controller" => "servers","action" => "editColumn"));?>" method="POST" enctype="multipart/form-data"></form></div>
</div>
<div id="debug"></div>
<?php

//http://jqueryui.com/autocomplete/#remote-jsonp

?>