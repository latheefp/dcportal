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
