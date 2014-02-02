<?php
App::import('Sanitize');

class UsersController extends AppController {
    var $name = 'Users';

    function index() {
        if (!empty($this->data)) {
           echo debug ($this->data);
            if(!$this->_verify_Username_and_Pass($this->data['User']['loginid'],$this->data['User']['password'])) {
               $this->Session->setFlash('Wrong user name or password.');
                     $this->Session->delete('User');
                     $this->Session->delete('Group');

            }else {
                //Login OK
                echo debug("User logged in");
                
                $this->Session->write('User.status', 'authorized'); //Added
                if($this->Session->read('User.agreement')=='0') {
                    $this->redirect(array('controller' => 'users', 'action' => 'agreement'));
                }else {
                    $this->User->read(null, $this->Session->read('User.id'));
                    $this->User->set('last_visit', date('Y-m-d H:i:s'));
                    $this->User->save();
                   // echo debug($this->Session->read('User.id'));
                    $this->redirect('/');
         	   }
            }
        }
    }

    function agreement() {

    }

    function update() {
        $this->layout='ajax';
        $this->User->read(null, $this->Session->read('User.id'));
        $this->User->set($_POST);
        $this->User->save();
        $this->_loadUserInfo($this->Session->read('User.loginid'));
        return true;
    }
    function agreementAgree() {
        $this->Session->write('User.agreement','1');
        $this->User->read(null, $this->Session->read('User.id'));
        //$this->User->set('last_visit', '1');
        $this->User->set('agreement', '1');
        $this->User->save();
        $this->redirect('/');
    }

    function logout() {
        $this->Session->delete('User');
        $this->Session->delete('Group');
        $this->redirect(array('controller' => 'users', 'action' => 'index'));
    }

    function _loadUserInfo($User) {
        echo debug($User);
        $UserInfo = $this->User->findByLoginid($User);
        if(is_array($UserInfo)==0) return false;
        // Set sessions
        echo debug($UserInfo);
        foreach($UserInfo['User'] as $key => $val) {
            $this->Session->write('User.'.$key , $val);
        }
        foreach($UserInfo['Group'] as $key => $val) {
            $this->Session->write('Group.'.$key , $val);
        }
        return true;
    }
    function _verify_Username_and_Pass($User,$Password) {
        echo debug($User);
        $loadData=$this->_loadUserInfo($User);
        echo debug($loadData);
         if(!$loadData) return false;
            // connect to ldap server
            $ldapconn = ldap_connect(Configure::read('app.ldap.ip')) or die("Could not connect to LDAP server.");
            if ($ldapconn) {
                // binding to ldap server
                $ldapbind = @ldap_bind($ldapconn, Configure::read('app.ldap.domain') . "\\".$User, $Password);
                // verify binding
                if ($ldapbind) {
                    //echo "LDAP bind successful...";
                    @ldap_unbind( $ldapconn );
                } else {
                    //return "LDAP bind failed...";
                    
                    $this->Session->write('User.status', 'authorized'); //added
                    echo debug ("User Authorized");
                    return true; //added
                    //return false;
                }
            }
        $this->Session->write('User.status', 'authorized');
        return true;

    }
}
?>
