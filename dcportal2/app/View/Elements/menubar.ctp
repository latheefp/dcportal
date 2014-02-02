<?php
$i=200;
//echo debug($this->requestAction);
$allData = $this->requestAction('menus/getMenuCode');

if(is_array($allData)){
$mainmenu=$allData[0];
$submenu=$allData[1];
//echo debug ("empty");
}else{
    $mainmenu=array();
    $submenu=array();
}
$myhtml=null;
$myjs=null;
    if(is_array($mainmenu)){
    foreach($mainmenu as $key=>$value){
    if(isset($submenu[$key])){
        //bad bad boy
        if($value['url']=='') $value['url'] = '#main'.$i;
        $myhtml .= '<a href="'.$value['url'].'" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="main'.$i.'">';
        $myhtml .= '<span class="ui-icon ui-icon-triangle-1-s"></span>'.$value['name'].'</a>';
        $myhtml .= '<div class="hidden">';
	$myjs .= "jQuery('#"."main$i"."').menu({content: jQuery('#"."main$i"."').next().html(),backLink: false});";

        $myhtml .= buildSub($submenu[$key],$submenu);
        $myhtml .= '</div>';
    }else{
        if($value['url']=='') $value['url'] = '#main'.$i;
        $myhtml .= '<a href="'.$value['url'].'" class="fg-button ui-widget ui-state-default ui-corner-all" id="main'.$i.'">'.$value['name'].'</a>'."\n";

    }
    $i++;
    }
    }

 
function buildSub($mysubmenu,$submenu){
    global $i;
    $myhtml ='<ul>';
    foreach($mysubmenu as $key=>$value){
            if($value['url']=='') $value['url'] = '#sub'.$i;
            $myhtml .='<li><a href="'.$value['url'].'">'.$value['name'].'</a>';
        if(isset($submenu[$key])){
            $myhtml .= buildSub($submenu[$key],$submenu);
        }else{

        }
        $myhtml .='</li>';
        $i++;
    }
    $myhtml .='</ul>'."\n";
    return $myhtml;
}
print $myhtml;
print '<script type="text/javascript">'.$myjs.'</script>';
?>