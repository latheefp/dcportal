<?php
/*
 * TODO show the audit information in each server
 */
class Sysaudit extends AppModel {
    var $name = 'Sysaudit';
    var $belongsTo = array(
            'Server' => array(
                            'className' => 'Server',
                            'foreignKey' => 'server_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
            )
    );

}
?>