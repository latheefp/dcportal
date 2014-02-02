<?php
class InformationsController extends AppController {

	var $name = 'Informations';
        var $uses  =array('Server');
        
	function index() {
        $conditions = array();
        //if( (isset($_GET['location'])) && ($_GET['location']!='') ) { $conditions['AND']['location LIKE'] = $_GET['location']; }
        //if( (isset($_GET['os_type']))  && ($_GET['os_type']!='')) { $conditions['AND']['os_type LIKE'] = $_GET['os_type']; }

        $data = array('conditions' =>$conditions,'fields'=>array('hostname','location','os_type','project','applications','criticality','owners','teams','emails','support_level','ethernet_ips'),'recursive' => 0);
        $this->set('servers',$this->Server->find('all',$data));

        //$this->set('location',$this->Server->find('all',array('fields'=>array('distinct location'))));
        //$this->set('os_type',$this->Server->find('all',array('fields'=>array('distinct os_type'))));

	}


}
?>