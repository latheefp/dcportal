<?php
/*
 * TODO show the audit information in each server
 */
class Audit extends AppModel {
    var $name = 'Audit';
    var $belongsTo = array(
            'User' => array(
                            'className' => 'User',
                            'foreignKey' => 'user_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            )
    );

    function auditThis($action,$data,$table,$user_id=null,$rowid=null) {
        $this->read(null,  null);
        $this->set(array(
                'table' => $table,
                'action' => $action,
            'data' => $data,
            'user_id' => $user_id,
            'rowid' => $rowid,
        ));
        $this->save();

    }
    function getAuditbyRowID($rowid){
         return $this->find('all',array('conditions'=>array('rowid'=>$rowid),'limit'=>100));
    }
}
?>