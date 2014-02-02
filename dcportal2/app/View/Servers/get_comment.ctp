<?php
if(isset($error)){
    print '<font color=red>'.$error.'</font>';
}else{
 ?>
<table id="commentlist" class="CommentTable">
    <tbody id="commenttbody">
<?php
    foreach($history as $row){
                 if($row['History']['user_name']=='') { $row['History']['user_name'] ='DCPortal Agent'; }
        ?>
        <tr > 
            <td id="heading" class="CommendHead">
                   <?php print '['.$time->timeAgoInWords($row['History']['created']).'] Updated by <u><i> ' . $row['History']['user_name'] . '</u></i>'; ?>
            </td>
        </tr>
        
        <tr>
        <td id="content" class="CommendContend">
                <?php print $row['History']['content'] ?>
            </td>
        </tr>
<?php
        
    }
}
?>
  </tbody>
</table>
