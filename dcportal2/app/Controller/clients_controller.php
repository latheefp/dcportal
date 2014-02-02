<?php
App::import('Sanitize');
class ClientsController extends AppController {
    var $name = 'Clients';
    /*
    * TODO: 'Waiting' to 'Waiting long time' in Task and Job
    */
    function index() {

    }

    function newClient() {
        $this->layout='ajax';
        $host = trim(@$_GET['hostname']);
        $uuid = trim(@$_GET['uuid']);
        $this->set('HOST',$host);
        $this->set('UUID',$uuid);
        $this->log("[Client][newClient] Host:". $host . " UUID:".$uuid , 'activity');

        if(($host!='') && ($uuid!='')) {
            $this->Client->read(null,  null);
            $this->Client->set(array(
                    'host' => $host,
                    'uuid' => $uuid,
                    'heartbeat' =>  date('Y-m-d H:i:s')
            ));
            $this->Client->save();

        }
    }

    function getOutput() {
        $this->layout='ajax';
        $this->loadModel('Task');
        $this->loadModel('Job');
        $Job_Status_flip = array_flip(Configure::read('app.Job_Status'));
        $Task_Status_flip = array_flip(Configure::read('app.Task_Status'));
        $actionId = trim(@$_POST['actionId']);
        $actionKey = trim(@$_POST['actionKey']);

        if( ($actionId=='') or ($actionKey=='') ) {
            exit;
        }

        //
        $data = $this->Task->find('first',array('fields' => array('Client.id','Client.uuid','Job.id'),'conditions'=>array('Task.id'=>$actionId,'Task.key'=>$actionKey),'recursive' => 0));

        if(sizeof($data)==0) {
            exit;
        }

        $Job_Path = Configure::read('app.Jobs_Path') . '/' . $data['Job']['id'] . '/' . $actionId ;
        mkdir($Job_Path);

        $this->_updateHeartbeat($data['Client']['id']);


        $this->Task->read(null,  $actionId);
        $this->Task->set('status', $Task_Status_flip['Completed']);
        $this->Task->save();

        $Count_All_Tasks = $this->Task->find('count',array('recursive' => 0));
        $Count_Completed_Tasks = $this->Task->find('count',array('conditions'=>array('Task.status'=>$Task_Status_flip['Completed']),'recursive' => 0));
        if($Count_All_Tasks==$Count_Completed_Tasks) {
            $this->Job->read(null,  $data['Job']['id']);
            $this->Job->set('status',$Job_Status_flip['Completed']);
            $this->Job->save();
        }

        $post = 'actionId=' . $actionId . "\n";
        $post .= 'actionKey=' . $actionKey . "\n";
        $post .= 'Client=' . $data['Client']['id'] . "\n";

        file_put_contents($Job_Path . '/'. 'data.log',$post);

        foreach ($_FILES as $key) {
            if ($key['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $key["tmp_name"];
                $name = $key["name"];
                move_uploaded_file($tmp_name,$Job_Path . '/'. $name);
            }
        }
    }

    function getCommands() {
        $this->layout='ajax';
        $this->loadModel('Task');
        $this->loadModel('Job');
        $uuid = trim(@$_GET['uuid']);
        $Job_Status_flip = array_flip(Configure::read('app.Job_Status'));
        $Task_Status_flip = array_flip(Configure::read('app.Task_Status'));
        $Job_Type = (Configure::read('app.Job_Type'));

        $commands = $this->Task->find('all',array('fields' => array('Task.id', 'Task.key','Task.Client_id','Job.id','Job.job_type','Job.job_data'),'conditions'=>array('uuid'=>$uuid,'Job.status !='=>$Job_Status_flip['Suspended'],'Task.status'=>$Task_Status_flip['New']),'recursive' => 1));

        $client_id = $this->Client->find('first',array('fields' => array('id'),'conditions'=>array('uuid'=>$uuid),'recursive' => 0));
        $client_id = $client_id['Client']['id'];

        if ($client_id != '') {
            $this->_updateHeartbeat($client_id);
        }
        foreach($commands as $command) {
            $this->Job->read(null,  $command['Job']['id']);
            $this->Job->set('status',$Job_Status_flip['Waiting']);
            $this->Job->save();
            $this->Task->read(null,  $command['Task']['id']);
            $this->Task->set('status', $Task_Status_flip['Waiting']);
            $this->Task->save();
            $command['Job']['job_data'] = str_replace("\r",'',$command['Job']['job_data']);
            $command['Job']['job_data'] = str_replace("\n",'',$command['Job']['job_data']);
            print $command['Task']['id'] . '|'. $command['Task']['key'] .'|'.  $Job_Type[$command['Job']['job_type']]. '|'. $command['Job']['job_data'] . "\n";
        }
    }

    function _updateHeartbeat($client_ID) {
        if($client_ID=='') return false;
        $this->Client->read(null,  $client_ID);
        $this->Client->set('heartbeat',date('Y-m-d H:i:s'));
        $this->Client->save();
        return true;
    }
}
?>