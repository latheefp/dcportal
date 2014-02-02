<?php
App::import('Sanitize');
class JobsController extends AppController {
    var $name = 'Jobs';

    /*
     * TODO Access to job interface is open !!!!
    */
    function AddJob() {
        $this->loadModel('Client');
        $this->loadModel('Task');
        if (!empty($this->data)) {
            if($this->Session->read('User.id')!='') {
                $this->data['Job']['user_id'] = $this->Session->read('User.id');
            }
            $this->Job->create();
            if ($this->Job->saveAll($this->data)) {
                $Job_Path = Configure::read('app.Jobs_Path');
                @mkdir($Job_Path . '/' . $this->Job->id);
                file_put_contents($Job_Path . '/' . $this->Job->id.'/name',$this->data['Job']['name']);
                $Clients = array();
                $i=0;
                foreach($this->data['Job']['clients'] as $ClientID) {
                    $Clients['Task'][$i]['client_id'] = $ClientID;
                    $Clients['Task'][$i]['job_id'] = $this->Job->id;
                    $Clients['Task'][$i]['key'] = uniqid($Clients['Task'][$i]['client_id'] .'' . $Clients['Task'][$i]['job_id']);
                    $i++;
                }
                if ($this->Task->saveAll($Clients['Task'])) {
                    $this->Session->setFlash('The job has been saved');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('The job could not be saved. Please, try again.');
                }
            } else {
                $this->Session->setFlash('The job could not be saved. Please, try again.');
            }
        }
        $this->set('Job_Type',(Configure::read('app.Job_Type')));
        //echo $this->Form->input('status');
        $this->set('clients',$this->Client->find('list',array('fields' => array('host'))));
    }
    function index() {
        $this->paginate = array(
                'limit' => 200,
                'order' => array('Job.created'),
                'fields' => array('Job.id','Job.name','Job.job_type','Job.status','Job.created','User.name','User.id')
        );
        $this->Job->recursive = 0;
        $this->set('Job_Type',Configure::read('app.Job_Type'));
        $this->set('Job_Status',Configure::read('app.Job_Status'));
        $this->set('jobs', $this->paginate('Job')); //,'fields' => array('Post.id', 'Post.created')
    }


    function suspend($id = null) {
        $Job_Status_flip=array_flip(Configure::read('app.Job_Status'));
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for job', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Job->read(null, $id);
        $this->Job->set('status',$Job_Status_flip['Suspended']);
        if ($this->Job->save()) {
            $this->Session->setFlash('Job suspended');
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash('Job was not suspended ' . $id);
        $this->redirect(array('action' => 'index'));
    }
    function unsuspend($id = null) {
        $Job_Status_flip=array_flip(Configure::read('app.Job_Status'));
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for job', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->Job->read(null, $id);
        $this->Job->set('status',$Job_Status_flip['New']);
        if ($this->Job->save()) {
            $this->Session->setFlash('Job suspended');
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash('Job was not suspended');
        $this->redirect(array('action' => 'index'));
    }
}