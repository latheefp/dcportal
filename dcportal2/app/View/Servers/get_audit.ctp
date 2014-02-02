<small><?php
if(isset($error)){
    print '<font color=red>'.$error.'</font>';
}else{
    foreach($data as $row){
        if($row['User']['name']=='') { $row['User']['name'] ='DCPortal Agent'; }
        print '['.$time->timeAgoInWords($row['Audit']['created']).'] (' . $row['Audit']['action'] .') <b>' . $row['Audit']['data'] .'</b> <u><i>by: '. $row['User']['name'].'</i></u><br />';
    }
}
?></small>