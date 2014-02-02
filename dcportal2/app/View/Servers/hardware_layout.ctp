<form method=get action=''>
<table border=0 cellpadding="10" align=center>
<tr><td>
<select name=location>
<option value=''>All</option>
<?php
foreach($location as $loc){
    if($_GET['location'] == $loc['Server']['location']) { $selected=' selected';}else{$selected='';}
    print '<option'.$selected.'>'.$loc['Server']['location'].'</option>';
}
?>
</select>
</td>
<td>
<select name=os_type>
<option value=''>All</option>
<?php
foreach($os_type as $os){
    if($_GET['os_type'] == $os['Server']['os_type']) { $selected=' selected';}else{$selected='';}
    print '<option'.$selected.'>'.$os['Server']['os_type'].'</option>';
}
?>
</select>
</td>
<td><input type="submit" value="View"></td>
</tr>
</table>
</form>
<table>
    <tbody>
<?php
$i=0;
$x=0;
foreach($platforms as $platform){
$data = array('conditions'=>array('serial LIKE'=>$platform['Server']['serial']));

$MyPlatform = $PlatformDB->find('first',$data);



echo '<tr>';
$img = str_replace(' ','',$MyPlatform['Platform']['model']);
$img = str_replace(array('9119-','9133-','9131-','9117-','9119-','9115-','9110-'),'',$img);
$img = str_replace('p595','595',$img);
$img = str_replace('Sun','',$img);
$img = str_replace('(sun4v)','',$img);
$img = str_replace('(sun4v)','',$img);
$img = str_replace('[GlobalZone]','',$img);
$img = str_replace('Fire','',$img);
$img = str_replace('[Zone]','',$img);
$img = str_replace('[PrimaryLDomain]','',$img);
$img = str_replace('p570','570',$img);
$img = '<img title="'.$img.'" src="/img/hw/'.$img.'.jpg">';
//<img src="/img/hw/'.str_replace(' ','',$platform['Server']['hw_type']) . '.jpg">
echo '<td style="vertical-align: middle;" width=10%>'.$img.'</td>';

$data = array('fields'=>array('hostname','project','applications','owners','support_level','rack_no','pcpus','lcpus','memory','cluster'),'recursive' => 0,'conditions'=>array('serial'=>$platform['Server']['serial']));
$servers = $ServerDB->find('all',$data);

$Other = explode("\n", $MyPlatform['Platform']['Other']);
$url = $MyPlatform['Platform']['model'];
if($MyPlatform['Platform']['ip']!='') {$url ='<a href="'.$MyPlatform['Platform']['ip']. '" target=_new>'.$MyPlatform['Platform']['model'].'</a>';}
echo '<td style="text-align: left;vertical-align: middle;"><small><b>'.$url.'</b><br />';
echo '<b>Servers:</b>&nbsp;' . sizeof($Other) .'<br />';
echo '<b>Serial:</b>&nbsp;<ax href="/servers/?serial=' . $MyPlatform['Platform']['serial'] .'">' . $MyPlatform['Platform']['serial'] .'</a><br />';
echo '<b>Location:</b>&nbsp;<ax href="/servers/?location=' . $MyPlatform['Platform']['location'] .'">' . $MyPlatform['Platform']['location'] .'</a><br />';
echo '<b>Rack:</b>&nbsp;<ax href="/servers/?rack_no=' . $MyPlatform['Platform']['rack_no'] .'">' . $MyPlatform['Platform']['rack_no'] .'</a><br />';
echo '<b>CPU:</b>&nbsp;' . $MyPlatform['Platform']['cpu_type'].'<br />';
echo '<b>Installed CPU:</b>&nbsp;' . $MyPlatform['Platform']['InstalledCPU'] .'<br />';
echo '<b>Available CPU:</b>&nbsp;' . $MyPlatform['Platform']['AvailableCPU'] .'<br />';
echo '<b>Installed Memory:</b>&nbsp;' . $MyPlatform['Platform']['InstalledMem'] .'<br />';
echo '<b>Available Memory:</b>&nbsp;' . $MyPlatform['Platform']['AvailableMem'] .'<br /><br />';
$ErrorStart=null;
$ErrorEnd=null;

if(sizeof($Other) != sizeof($servers)){
    $ErrorStart='<font color=red>';
    $ErrorEnd='</font>';
}else{
    foreach($servers as $server){
        if(strpos( strtoupper($MyPlatform['Platform']['Other']) ,strtoupper($server['Server']['hostname']). ',') === false  ){
            $ErrorStart='<font color=red>';
            $ErrorEnd='</font>';
        }
    }
}
echo $ErrorStart . nl2br($MyPlatform['Platform']['Other']) . $ErrorEnd;
echo '</small></td>';
echo '<td style="vertical-align: middle;">';
echo '<table width="100%"><tbody>';
foreach($servers as $server){
echo '<tr><td width=10%>'.$server['Server']['hostname'].'</td>';
if($server['Server']['lcpus']=='') $server['Server']['lcpus'] = $server['Server']['pcpus'];
if(($server['Server']['pcpus']!='') and ($server['Server']['pcpus']!='0')) $pccpus = '<small>(P:'.$server['Server']['pcpus'].')</small>'; else $pccpus = '';

echo '<td width=15%>'.$server['Server']['lcpus'].' ' .$pccpus .'</td>';
echo '<td width=15%>'.$server['Server']['memory'].'</td>';
echo '<td width=15%>'.$server['Server']['support_level'].'</td>';
echo '<td width=25%>'.$server['Server']['project'].' <small>'.$server['Server']['applications'].'</small></td><td>'.$server['Server']['owners'].'</td></tr>';
}
print '</tbody></table>';
echo '</td></tr>';
}
?>
    </tbody>
</table>