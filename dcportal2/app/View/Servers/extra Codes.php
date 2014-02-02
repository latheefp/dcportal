<?php

###Code removed from server list


foreach($fieldUserInfo as $name=>$val) {
  //   if($val['mandate']=='1') {
            $title=$val['title'];
            //echo debug ($title);
            $name=$val['field'];
            if($fieldUserInfo[$name]['type_overwrite']!='') {
                $mytype=$fieldUserInfo[$name]['type_overwrite'];
            }else {
                 $mytype=@$schema[$name]['type'];
            }
            $defvalue=@$schema[$name]['default'];
            $length=@$schema[$name]['length'];
           switch ($mytype) {
                case "string":
                        $type="text";
                        ?>
                        $('#sysdetail tbody').append('<?php print "<tr><td id=label>". $title ."</td><td id=inputdata>";?>  <input type= <?php print $type; ?> id=<?php print $name . "-addnew"; ?> validate=false name=<?php print $name; ?> maxlength=<?php print $length . " field=" .$name. "></td></tr>"; ?>');
                        <?php                                                      
                        break;
              
                case "select":
                        $type="select";
                        $list=explode(",",str_replace(array("\r", "\n"), '', $val['extra']));
                       // $list=explode(",", $val['extra']);
                        //print $list;
                        ?>
                        $('#sysdetail tbody').append('<?php print "<tr><td id=label>". $title ."</td><td id=inputdata> <select id=" . $name."-addnew  validate=false field=" .$name.  " name=" .$name;
                       // $options='<option selected="selected">'. $list[0].'</option>';
                        if( isset($list[0])){$select = $list[0];}
                        //$options=' <option selected="selected"> '. $select .'</option>';
                         $options=' <option selected="selected"> </option>  <option value=""></option>';
                        foreach($list as $key => $value):
                            $options= $options . ' <option value="'.$value.'">'. $value.'</option> '; //close your tags!!
                        endforeach;
                        
                        print $options . "></td></tr>"; ?>');
                        <?php                                                      
                        break;
                 case "multiselect":
                        $type="multiselect";
                        $list=explode(",",str_replace(array("\r", "\n"), '', $val['extra']));
                       // $list=explode(",", $val['extra']);
                        //print $list;
                        ?>
                        $('#sysdetail tbody').append('<?php print "<tr><td id=label>". $title ."</td><td id=inputdata> <select multiple id=" . $name ."-addnew  validate=false field=" .$name.  " name=" .$name."[]";
                        if( isset($list[0])){$select = $list[0];}
                        //$options=' <option selected="selected"> '. $select .'</option>';
                        $options=' <option selected="selected"> '. $select .'</option>';
                        foreach($list as $key => $value):
                            $options= $options . ' <option value="'.$value.'">'. $value.'</option> '; //close your tags!!
                        endforeach;
                        
                        print $options . "></td></tr>"; ?>');
                        <?php                                                      
                        break;
                    }
            //    } //mandate check

        }




?>
