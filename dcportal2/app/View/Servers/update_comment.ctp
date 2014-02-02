<?php
if (isset($data['0'])){
    print "ERROR";
}else{
    //echo debug($data);
    print '<tr><td id="heading" class="CommendHead">[0 Second ago] Updated by <u><i>' . $data['user_name'] . '</i></u></td></tr>';
    print '<tr><td id="content" class="CommendContend">' . $data['content']. '</td></tr>';
}
?>
