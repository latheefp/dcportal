<script type="text/javascript">
                $(function(){
                $("select").multiselect();
                });
 </script>
            <?php

            print '<form >';
            print '<tr';
            print " class=''";
            print '>';
            print '<td></td>'; //this is for skipping multiselect in first colom of table (history colom)
            foreach($fieldUserInfo as $titble=>$value)
                {
                if ($value['show']==0)  continue;
                ?>
                <td>
                <select  id="advsearch" name="advsearch" class="ui-multiselect"  <?php //print 'style="width: '.@$schema[$titble]['length'].'px" '; ?>
                <option value="option1">Option 1</option>

                <option value="option2">Option 2</option>

                <option value="option3">Option 3</option>
                </select>
                </td>
                <?php
                }

            print '</tr>';
            print '</form>';
            ?>

                
                
                
                
                
                
                
                
                <script type="text/javascript">
$(function(){
	$("bselect").multiselect({minWidth:'150',
                                uncheckAllText:'UncheckAll', 
                                height:'auto',
                                multiple:'true'
                                 });
});

</script>

 print 'table="'.$val['table'].'" name="'.$name.'" editable="'.$val['editable'].'" extra="'.$val['extra'].'" ';
  print 'table="'.$val['table'].'" name="'.$name.'" editable="'.$val['editable'].'" extra="'.$val['extra'].'" ';
  
  
  
  </script>


<?php
 foreach($fieldUserInfo as $titble=>$value)
                {
                if ($value['show']==0)  continue;

                
?>
                  <script type="text/javascript">
                                                                 
                $(function(){
                        $(<?php print '"#'.$value[field].'"';?>).multiselect({ minWidth:'150',
                                                uncheckAllText:'UncheckAll', 
                                                height:'auto',
                                                multiple:'true'
                                                });
                             }
                  );

                </script>


<?php
}
?>

                <select  id="<?php echo  $value['field'] ?>" name="bselect" class="ui-multiselect"  >
                <option value="option1">Option 1</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
                </select>
                
                
                <?php
                        foreach($data as $row) {
                            
                            foreach ($row as $table){
                                echo debug ($table);   
                                foreach ($table as $item=>$info){
                                    
                                    if ($item == $value['field']){
                                       
                                    print  ("<option value=" . $info . ">" . $info. "</option>");
                                    }
                                } 
                            }
      
                            
                            
                       ?>

                
                
                
                
                
        //code for loading data to select dropdown menu
       
                    <?php
                  foreach ($selectdata as $item=>$cvalue)
                      {
                       foreach ($cvalue as $cdata=>$boxdata){
                          if ($cdata == $titble){
                              
                              foreach ($boxdata as $id=>$boxval){
                                  
                                  echo debug ();
                                  print "<option > $boxval </option>";
                              }
                             }
                      }
                      }
                      ?>
                      </select>         
                    </td>
               <?php }

            print '</tr>';
            print '</form>';                
                
                
                
                
                
                
                
                