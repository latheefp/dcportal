<?php
/*
 * TODO: http://dcportal2/servers/getSysaudit for System Security Audit
 */
if(isset($error)){
    print '<font color=red>'.$error.'</font>';
}else{
?>
<table><thead><tr>
<th>*</th><th>Host</th><th>OS</th><th>Access</th><th>Root</th><th>Users without Password</th><th>Weak users</th><th>Last Check</th>
</tr></thead>
<tbody>
<?php
    foreach($data as $item){
        print '<tr>';
        if($item['Sysaudit']['access']=='1'){
            $item['Sysaudit']['access'] = '<img src="/img/1.png">';
        }elseif($item['Sysaudit']['access']=='0'){
            $item['Sysaudit']['access'] = '<img src="/img/0.png">';
        }else{
            $item['Sysaudit']['access'] = '<img src="/img/43.png">';
            $item['Sysaudit']['users_no_pass'] = ' ';
            $item['Sysaudit']['user_weak'] = ' ';
        }
        if($item['Sysaudit']['root']=='1'){
            $item['Sysaudit']['root'] = '<img src="/img/1.png">';
        }elseif($item['Sysaudit']['root']=='0'){
            $item['Sysaudit']['root'] = '<img src="/img/0.png">';
        }else{
            $item['Sysaudit']['root'] = '<img src="/img/43.png">';
        }
        if($item['Sysaudit']['users_no_pass']==''){
            $item['Sysaudit']['users_no_pass'] = '<img src="/img/1.png">';
        }
        if($item['Sysaudit']['user_weak']==''){
            $item['Sysaudit']['user_weak'] = '<img src="/img/1.png">';
        }
        print '<td>'.$item['Sysaudit']['id'].'</td><td>'.$item['Server']['hostname'].'</td><td>'.$item['Server']['os_type'].'</td><td>'.$item['Sysaudit']['access'].'</td><td>'.$item['Sysaudit']['root'].'</td><td><pre>'.$item['Sysaudit']['users_no_pass'].'</pre></td><td><pre>'.$item['Sysaudit']['user_weak'].'</pre></td><td>'.$item['Sysaudit']['check_date'].'</td>';
        print '</tr>';
    }
}
?>
</tbody></table>