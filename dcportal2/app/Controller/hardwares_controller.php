<?php
class HardwaresController extends AppController {

	var $name = 'Hardwares';
	var $scaffold;
        var $helpers = array('Session');

     function beforeFilter() {
        if( ($this->Session->read('User.status') != 'authorized') or ($this->Session->read('User.agreement')=='0')) {
            $this->Session->setFlash('You must login');
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
       }
      //echo debug ( $this->Session->read('Group.id'));
        // echo debug ( Configure::read('app.admin'));

        if( ($this->Session->read('Group.id') != '1') ) {
            $this->Session->setFlash(' You Are not Athorised, Please contact <i><a href="mailto:'. (Configure::read('app.admin')) . '">' . (Configure::read('app.admin')). '<i> </a>');
            $this->redirect(array('controller' => 'servers', 'action' => 'index'));
        }
                                 }
}
?>
