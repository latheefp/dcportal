<?php
/*
 * TODO try to find simething other than anytime, it's doing too many calls to internet
 * TODO compress the java,css,html files
*/
//echo debug($schema);
echo $this->Html->script('jquery.maskedinput.min');
echo $this->Html->script('jquery.MetaData');
echo $this->Html->script('jquery.form');
echo $this->Html->script('jquery.MultiFile.pack');
echo $this->Html->script('jquery.watermarkinput.js');


//echo $this->Html->script('tiny_mce/tiny_mce');
//echo $this->Html->script('tiny_mce/jquery.tinymce');

/*
 * TODO Use http://www.ryancramer.com/journal/entries/select_multiple/ for selecting the columns
 */
if( (isset($qsearch)!='' ) AND (trim($qsearch)!='' )){
?>
<div class="ui-widget searchinfobox fadein"><div class="ui-state-highlight ui-corner-all" style="margin-bottom: 20px; padding: 10pt 0.7em;">
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>
<strong>Search:</strong> <?php echo $qsearch;?> (<a href="/servers/?qsearch=">Clear</a>)</p></div></div>
<?php
}
?>
<table id="tblServers"><thead><tr><th>*</th>
            <?php
            $fieldShowHtmlOptions=null;
            $order =array();
            //echo debug($fieldUserInfo);
            foreach($fieldUserInfo as $name=>$val) {
              //  echo debug($name);
                if($val['show']=='1') {
                    array_push($order,  $name);;
                    print '<th ';
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
                    print ' nowrap>';
                    if($val['editable']=='1') {
                        print '<img title="Double click any field below to edit." style="float:left; padding-right: 2px;" src="'.$this->Html->url('/img/edit16.png').'">';
                    }
                    if($val['sortable']=='1') {
                        print $this->Paginator->sort($val['title'],$name);
                    }else {
                        print $val['title'];
                    }
                    print '</th>';
                    $fieldShowHtmlOptions .='<option value="'.$name.'">'.$val['title'].'</option>';
                }
            }
            ?>
        </tr></thead>
    <tbody>

        <?php
        $count=1;
        //echo debug($data);
        //echo debug ($order);
        foreach($data as $row) {
            print '<tr';
            print ($count % 2 == 0) ? " class='altrow'" : "";
            $count++;
            print '>';
            print '<td><a href="#" class="serverDetails" rowid="'.$row['Server']['id'].'"><img src="'.$this->Html->url('/img/balloon.png').'" border=0></a></td>';
            foreach($row as $table) {
//                foreach($table as $name => $field) {
//                    if($name=='id') continue;
//                    print '<td rowid="'. $table['id'] .'" class="rowEdit'.$fieldUserInfo[$name]['editable'].'" >';
//                    print $FieldModel->showField($field,$fieldUserInfo[$name]);
//                    print '</td>';
//                }
               foreach ($order as $key=>$val){
                    print '<td rowid="'. $table['id'] .'" class="rowEdit'.$fieldUserInfo[$val]['editable'].'" >';
                    print $FieldModel->showField($table[$val],$fieldUserInfo[$val]);
                    print '</td>';
                    
                }
            }
            print '</tr>';
      }

        ?>
          <?php //echo debug($data);?>
    </tbody>
</table>
<div class="floatleft">
<?php
if(($this->Session->read('Group.server_export'))=='1'){
echo $this->Html->link($this->Html->image('spreadsheet.png', array('title'=> 'Export to Excel sheet', 'border' => '0')),'/servers/export',array('escape' => false));
}?>
</div>
<p id="paginator">
    <?php
    echo $this->Paginator->first('« First ',array('class'=>'ui-state-default ui-corner-all'));
    echo '&nbsp;'.$this->Paginator->prev(' Previous ' ,array('class'=>'ui-state-default ui-corner-all'), ' ');
    echo '&nbsp;'.$this->Paginator->numbers(array('class'=>'ui-widget ui-state-default paging','separator'=>'&nbsp;')); // class='ui-widget ui-state-defaultui-state-default'>
    echo '&nbsp;'.$this->Paginator->next(' Next ' ,array('class'=>'ui-state-default ui-corner-all'), ' ');
    echo '&nbsp;'.$this->Paginator->last(' Last »',array('class'=>'ui-state-default ui-corner-all'));

    echo '<br /><br /><i>'.$this->Paginator->counter(array('format' => '%count% servers, Page %page% of %pages%')).'</i>';
?>
</p>
<div id="dialog-form" title="My Settings"  style="display: none;">
    <div id="switcher"  align="right"></div>
    <div align="center"><h4>My Information</h4></div>
    <b>Name:</b> <?php echo $userInfo['name']; ?><br />
    <b>Group:</b> <?php echo $groupInfo['name']; ?><br />
    <?php //echo debug($data);?>
    <div id="radio"><b>Servers eMail Changes:</b>
        <input type="radio" name="server_alertedit" class="server_alertedit" value="1" id="Yes" <?php echo ($this->Session->read('User.server_alertedit')== '1') ? 'checked="checked"' : ''; ?> /><label for="Yes">Yes</label>
        <input type="radio" name="server_alertedit" class="server_alertedit" value="0" id="No" <?php echo ($this->Session->read('User.server_alertedit') != '1') ? 'checked="checked"' : ''; ?> /><label for="No">No</label>
    </div><br />
    <b>Servers per page:</b> <select id="server_perpage" name="server_perpage">
        <?php
        for($i=10;$i<=150;$i+=10) {
            print '<option value="'.$i.'"';
            print ($this->Session->read('User.server_perpage') == $i) ? 'checked="checked"' : '';
            print '>'. $i .' Servers</option>';
        }
        ?>
    </select><br />

    <br />
    <div align="center"><h4>My Fields</h4>
        <table cellpadding="0" cellspacing="0"><thead><tr><th>Showing</th><th></th><th>Available</th></tr></thead><tbody>
                <tr>
                    <td><select id="showing" name="showing" size="7" multiple="multiple">
                            <?php echo $fieldShowHtmlOptions;?>
                        </select></td>
                    <td style="text-align: center;"><button id="user-show-add">&lt;</button>
                        <br><button id="user-show-delete">&gt;</button></td>
                    <td><select id="available" name="available" size="7" multiple="multiple">
                            <?php
                            if( isset($groupShow['*'])) {
                                foreach($fieldInfo as $name=>$val) {
                                    if($val['show']=='1') {
                                        print '<option value="'.$name.'">'. $val['title'].'</option>';
                                    }
                                }
                            }else {
                                foreach($groupShow as $name=>$val) {
                                    print '<option value="'.$fieldInfo[$name]['name'].'">'.$fieldInfo[$name]['title'].'</option>';
                                }
                            }
                            ?>
                        </select></td>
                </tr>
            </tbody></table></div>

</div>
<div id="dialog-confirm" style="display:none;" title="My Settings">
    <p>Your settings has been saved. click OK to reload the page.</p>
</div>


<!--<div id="dialog-add" style="display:none;" title="My Settings">
    <p>Your settings has been saved. click OK to reload the page.</p>
</div>-->

<div id="dialog-add" style="display:none;" title="Add new server">
    <b><h3 align="center">New server</h3></b>
    <div id='add-body' align="center"></div>
</div>

<div id="dialog-inlineedit"  style="display:none;" title="Inline Editor">
    <b><h3 id='inline-editor-head' align="center"></h3></b>

    <div id='inline-editor-body' align="center"><form id="inline-editor-form" action="<?php echo $this->Html->url(array("controller" => "servers","action" => "editColumn"));?>" method="POST" enctype="multipart/form-data"></form></div>
</div>

<div id="dialog-serverDetails"  style="display:none;" title="Server details">
    <div id="serverDetailstabs">
        <ul>
            <li><a id="tab2" href="#tabs-2">Audit</a></li>
        </ul>
        <div id="tabs-1"></div>
        <div id="tabs-2"></div>
        <div id="tabs-3"></div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#radio").buttonset();
        $('.rowEdit1').bind("dblclick", function(){ 
            editRow(this);
        });
        $('.serverDetails').click(function(){
            serverDetails($(this).attr('rowid'));
            return false;
        });

    $('#qsearch').html('');
    $('#qinput').Watermark('Search...');

    $('#ServerIndexForm').submit(function(){
        if($('#qinput').val() == 'Search...') { $('#qinput').val('');}
         return true;
    });

$("#dialog-form").dialog({
                autoOpen: false,
                height: 510,
                width: 550,
                modal: true,
                hide: 'explode',
                buttons: {
                    'Save': function() {
                        $(this).dialog('close');
                        submitMySettings();

                    },
                    Cancel: function() {
                        $(this).dialog('close');
                    }
                },
                close: function() {

                }
            });
            
            $("#dialog-add").dialog({
                autoOpen: false,
                height: 250,
                width: 300,
                modal: true,
                show: 'slide',
                hide: 'explode',
                buttons: {
                    'Add': function() {
                        $.ajax({
                            type: "GET",
                            cache: false,
                            async: false,
                            url: "<?php echo $this->Html->url("/servers/AddNewHost/"); ?>"+$('#hostname').val(),
                            success: function(msg){
                                window.location = '<?php echo '/Servers/'; ?>?qsearch='+ $('#hostname').val();
                            }
                        });
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
 
            $("#add-body").html('Host name: <input type="text" id=hostname name=hostname>');


<?php
//
if($subaction == 'user-settings'){
?>
        $('#dialog-form').dialog('open');
<?php
}
if($subaction == 'dialog-add'){
?>
      $('#dialog-add').dialog('open');
     //  $('#dialog-confirm').dialog('open');
<?php } 
if ($subaction =='AdvanceSearch'){
    ?>
     $('#dialog-advsearch').dialog('open');
    <?php
}
//echo ($subaction);
 ?>
            $('#user-show-delete')
            .button()
            .click(function() {
                return !$('#showing option:selected').remove().appendTo('#available');
            });
            $('#user-show-add')
            .button()
            .click(function() {
                return !$('#available option:selected').remove().appendTo('#showing');

            });
        
    function serverDetails(id){
        var title='Server details';
        $.ajax({
            cache: false,
            async: false,
            url: "<?php echo $this->Html->url("/servers/getHostname/"); ?>"+id,
            success: function(msg){
                title = 'Server details - ' + msg;
            }
        });

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

        $("#serverDetailstabs").tabs({ajaxOptions: {
                error: function(xhr, status, index, anchor) {
                    $(anchor.hash).html("Couldn't load this tab!!.");
                }
            }
        });

        $('#dialog-serverDetails').dialog('open');
    }
    function editRow(myTD){
        //alert ();
        // First TD $(myTD).parent().children("td:first").html()
        //
        var dialogmodal=true;
        var currentValue = $(myTD).text();
        var index = $(myTD).parent().children().index(myTD);
        var myTH = '#tblServers th:nth-child(' + (index+1) + ')';
        var myType = $(myTH).attr('type');
      //  alert(index);
        $("#dialog-inlineedit").attr('title','Edit ' + $(myTH).attr('table') + ' - ' + ($('#tblServers th:nth-child(' + (index+1) + ')').text()));
        $("#inline-editor-head").html($(myTH).text());
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


            }

            $("#inline-editor-form").append('</form>');

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


        }

        // pre-submit callback
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
            });

        }
        });
        
        function submitAdSearch(){
           // echo debug($('#index').value);
           
        }
        
       function addElement(){
           if ($("#queryfield div").length < 10){
                var numi = document.getElementById('index');
                var num = (document.getElementById('index').value -1)+ 2;
                numi.value = num;
                var queryboxid = 'querybox'+num;
                $("#querybox0").clone()
                .attr('id', queryboxid)
                .attr('class', queryboxid)
                .appendTo("#queryfield");
                var rmbutton=document.createElement("span");
                var bottonid='rmbutton'+num;
                    rmbutton.innerHTML='<input type="button" value=\'Remove\' id="'+bottonid+'" style=\'width: 70px; height: 30px; text-align: center; padding=0;  text-decoration: none;    padding: 5px 0 0 0; \' onclick=\'removeElement('+queryboxid+')\'>';
               // bottonid.value="test";
                document.getElementById(queryboxid).appendChild(rmbutton);
                var nodes = document.getElementById(queryboxid).childNodes;
                //alert (nodes);
                for(var i=0; i<nodes.length; i++) {
                    if (nodes[i].name == "data[0][field]"){nodes[i].name="data[" + num + "][field]";}
                    if (nodes[i].name == "data[0][querytype]"){nodes[i].name="data[" + num + "][querytype]";}
                    if (nodes[i].name == "data[0][querytext]"){nodes[i].name="data[" + num + "][querytext]"; nodes[i].value=""; }
                 }
            }else{
                    alert ("Maximum Condition reached");
            }
                
       }
       function removeElement(divNum) {
        divNum.parentNode.removeChild(divNum);
        }

</script>

<!--<div id="dialog-advsearch" title="Advanced Search" style="display:none;">-->
    <form name="dialog-advsearch" id="dialog-advsearch" action="/servers/index/adsearch" method="POST">
    <input type="hidden" value="0" id="index" />
    <?php
    $collist ="<select id='field' name='data[0][field]' class='field' style='width:150px;'  >";
    foreach($order as $key=>$val){
        $collist=$collist  . " " . "<option value=$val>$val</option>";  
    }
    $collist =$collist  . "</select>";
    $condition="<select id='querytype' name ='data[0][querytype]' class='querytype' style='width:150px;'>
	<option value=1>Equals                     </option>
	<option value=2>Does not equal             </option>
	<option value=3>Is greater than            </option>
	<option value=4>Is greater than or equal to</option>
	<option value=5>Is less than               </option>
	<option value=6>Is less than or equal to   </option>
	<option value=7>Begins with                </option>
	<option value=8>Does not begin with        </option>
	<option value=9>Ends with                  </option>
	<option value=10>Does not end with          </option>
	<option value=11>Contains                   </option>
	<option value=12>Does not contain           </option>
    </select>";    
    ?>
<fieldset id="queryfield" class="displayrmbutton" > 

    <div id="querybox0" class="querybox0" >
        <?php
        echo $collist;
        echo $condition;
        ?>
        <input type='text' name="data[0][querytext]"  class="querytext"  id='querytext' style="padding:0; width:150px">
    </div>
</fieldset>
    <p><INPUT type="button" onclick="addElement();" value="+" style="width:60px; padding:0;" ></p>
    </form>



<script type="text/javascript">
                                                        
$("#dialog-advsearch").dialog({
                autoOpen: false,
                height: 500,
                width: 625,
                modal: true,
                hide: 'explode',
                //width:'auto',
                buttons: {
                    'Search': function() {
                      // alert(($('#index').attr('value')));
                       // var index = ($('#index').attr('value'));
                         $.ajax({
                                //type: "POST",
                                //cache: false,
                                //dataType: "json",
                                //url: "<?php echo $this->Html->url(array("controller" => "servers","action" => "aquery")); ?>",
                                //data: qdata,
                                success: function(){
                                       // window.location.href="/servers/aquery/" + qdata;
                                        $("#dialog-advsearch").submit();
                                        }
                            });
                        $(this).dialog('close');
                    },
                    Cancel: function() {
                       // window.location=history.back();
                       window.location="/servers/index";
                        $(this).dialog('close');
                        
                    }
                },
                close: function() {
                  //  window.location=history.back();
                 // window.location="/servers/index";
                }
            });
            
</script>