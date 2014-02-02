<?php
class MenusController extends AppController {

    var $name = 'Menus';
    function _mustLoginAction() {
        if($this->Session->read('User.status') != 'authorized') {
            $this->Session->setFlash('You must login');
            $this->redirect(array('controller' => 'servers', 'action' => 'index'));
        }

        if($this->Session->read('Group.menu_fullcontrol')!='1') {
            $this->Session->setFlash('Access denied');
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
    }
    function index() {
        $this->_mustLoginAction();
        $menus = $this->Menu->generatetreelist(null, null,null, '<img src="/img/arrow-180-medium.png">');//' --&raquo; ');
        $this->set('menus',$menus);
        $urls = $this->Menu->find('list',array('fields' => array('Menu.id','Menu.url'),'recursive' => -1,'conditions'=>array('Menu.id'=>array_keys($menus))));
        $this->set('urls',$urls);
    }
    function add($selected_id=null) {
        $this->_mustLoginAction();
        if (!empty($this -> data) ) {
            $this->Menu->save($this -> data);
            $this->Session->setFlash('A new menu has been added');
            $this->redirect(array('action' => 'index'));
        } else {
            $parents[0] = "[All Menus]";
            $menus = $this->Menu->generatetreelist(null,null,null," - ");
            if($menus) {
                foreach ($menus as $key=>$value)
                    $parents[$key] = $value;
            }

            $this->set(compact('selected_id'));
            $this->set(compact('parents'));
        }
    }

    function edit($id=null) {
        $this->_mustLoginAction();
        if (!empty($this->data)) {
            if($this->Menu->save($this->data)==false)
                $this->Session->setFlash('Error saving Node.');
            $this->redirect(array('action'=>'index'));
        } else {
            if($id==null) die("No ID received");
            $this->data = $this->Menu->read(null, $id);
            $parents[0] = "[All Menus]";
            $menus = $this->Menu->generatetreelist(null,null,null," - ");
            if($menus)
                foreach ($menus as $key=>$value)
                    $parents[$key] = $value;
            $this->set(compact('parents'));
        }
    }

    function delete($id=null) {
        $this->_mustLoginAction();
        if($id==null)
            die("No ID received");
        $this->Menu->id=$id;
        if($this->Menu->removeFromTree($id,true)==false)
            $this->Session->setFlash('The Menu could not be deleted.');
        $this->Session->setFlash('Menu has been deleted.');
        $this->redirect(array('action'=>'index'));
    }

    function moveup($id=null) {
        $this->_mustLoginAction();
        if($id==null)
            die("No ID received");
        $this->Menu->id=$id;
        if($this->Menu->moveup()==false)
            $this->Session->setFlash('The Menu could not be moved up.');
        $this->redirect(array('action'=>'index'));
    }

    function movedown($id=null) {
        $this->_mustLoginAction();
        if($id==null)
            die("No ID received");
        $this->Menu->id=$id;
        if($this->Menu->movedown()==false)
            $this->Session->setFlash('The Menu could not be moved down.');
        $this->redirect(array('action'=>'index'));
    }

    function getMenuCode(){
        $menu_id=$this->Session->read('Group.menu_id');
        $data = array();
        if($menu_id != '') {


            /*$list_ids = explode(',', $menu_id);
            $menu = array();
            foreach ($list_ids as $id){
            $menu = array_merge($menu,$this->Menu->children($id));
            }*/

            $menu = $this->Menu->children($menu_id);
            $menulist=array();
            foreach($menu as $submenu) {
                $menulist[$submenu['Menu']['parent_id']][$submenu['Menu']['id']]= array('name'=>$submenu['Menu']['name'],'url'=>$submenu['Menu']['url']);
            }

            if (isset($this->params['requested'])) {
                $alldata = array();
                if(isset($menulist[$menu_id])) $alldata[]=$menulist[$menu_id];
                unset($menulist[$menu_id]);
                $alldata[]=$menulist;
                return $alldata;
            } else {
                $this->set('mainmenu',$menulist[$menu_id]);
                unset($menulist[$menu_id]);
                $this->set('submenu',$menulist);
            }

        }
        return array(array(),array());


    }

}
?>