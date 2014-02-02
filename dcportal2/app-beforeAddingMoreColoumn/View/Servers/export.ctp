<?php
$servers = array();
//echo debug ($data);
foreach($data as $server){
    $servers[] = $server['Server'];
}
//debug($servers);
$this->Excel->generate($servers, 'Servers');
?>