<?php
App::import('Sanitize');
/*
* TODO Create a site to add,delete,change fields
*/
class ServersController extends AppController {
var $name = 'Servers';
var $Sname = 'server';
var $fieldInfo = array();
var $fieldUserInfo = array();
var $paginate = array();
var $components = array('Email');
var $helpers = array('excel');
//var $Search = array();

function beforeFilter() {
    if( ($this->Session->read('User.status') != 'authorized') or ($this->Session->read('User.agreement')=='0')) {
    $this->Session->setFlash('You must login');
    $this->redirect(array('controller' => 'users', 'action' => 'index'));
    }

    $this->loadModel('Field');

    $MyFields = $this->Field->findAllByTable($this->name);
    foreach($MyFields as $name => $val) {
    $this->fieldInfo[$val['Field']['field']] = $val['Field'];
    }
    $this->fieldUserInfo = $this->fieldInfo;
    $this->set('fieldInfo',$this->fieldInfo);
    $this->_setUserSettings();

}

function HardwareLayout() {
    if($this->Session->read('Group.HardwareLayout')!=1){
    $this->Session->setFlash('Access denied');
    $this->redirect(array('controller' => 'users', 'action' => 'index'));
    }
    $conditions = array();
    if( (isset($_GET['location'])) && ($_GET['location']!='') ) { $conditions['AND']['location LIKE'] = $_GET['location']; }
    if( (isset($_GET['os_type'])) && ($_GET['os_type']!='')) { $conditions['AND']['os_type LIKE'] = $_GET['os_type']; }

    $data = array('conditions' =>$conditions,'fields'=>array('location','cputype','hw_type','serial','rack_no','COUNT(serial) as TotalServer'),'recursive' => 0,'order'=>'hw_type','group'=>'serial');
    $this->set('platforms',$this->Server->find('all',$data));
    $this->set('ServerDB',$this->Server);

    $this->set('location',$this->Server->find('all',array('fields'=>array('distinct location'))));
    $this->set('os_type',$this->Server->find('all',array('fields'=>array('distinct os_type'))));

    $this->loadModel('Platform');
    $this->set('PlatformDB',$this->Platform);
}

function getColumnValue() {
    $this->layout='ajax';
    // echo debug (strftime('%c') . "Request Recieved");
    if((trim($_GET['table'])=='') or (trim($_GET['name'])=='') or (trim($_GET['id'])=='')) {
    print 'ERROR!';
    return false;
    }
    // echo debug (strftime('%c') . "checked");
    //Security to check he can see the value
    if($this->fieldUserInfo[$_GET['name']]['show'] != '1' ) {
    print 'ERROR! ' . __LINE__;
    return false;
    }
   // echo debug (strftime('%c') . "permission checked");

    $data = $this->Server->read($_GET['name'], $_GET['id']);
    print $data['Server'][$_GET['name']];
    // echo debug (strftime('%c') . "data Sent");
    //data: "name="+$(myTH).attr('name')+"&id="+$(myTH).attr('rowid')+"&table="+$(myTH).attr('table'),
}

function AddNewHostOld($hostname) {
    $this->layout='ajax';
    $this->loadModel('Audit');

    if($this->Session->read('Group.server_add')!='1') {
    print 'ERROR';
    return false;
    }

    $this->Server->read(null, null);
    $this->Server->set(array(
    'hostname' => $hostname,
    'InsertedBy' => $this->Session->read('User.id')
    ));
    $this->Server->save();
    $this->Audit->auditThis('Add','New server '.$hostname,$this->name,$this->Session->read('User.id'),$this->Server->id);
    $this->_alertChanges($hostname,true);
    print 'Done';
}


function AddNewHost() {
    $this->layout='ajax';
    $this->loadModel('Audit');
    $fielduserinfo=$this->fieldInfo;
    if($this->Session->read('Group.server_add')!='1') {
    print 'ERROR';
    return false;
    }
    $data=$this->request->data;
    foreach ($data as $field =>$value){
        $result=$this->validateData($field, $value);
        $mytype=$fielduserinfo[$field]['type_overwrite'];
        
        if ($mytype == "multiselect"){
            echo debug ($field . "my type is ". $mytype);
            $csvval="";
            foreach($value as $selected){
                echo debug ($selected);
                $csvval=$csvval  .$selected . ",";
            }
            unset ($data[$field]);
            $data[$field]=$csvval;
        }
        if ($result['return']=="fail"){
           print "Update Failed";
           return false;
        }
    
    }
    echo debug($data);
    $this->Server->set($data);
    $this->Server->save();
    $this->Audit->auditThis('Add','New server '.$hostname,$this->name,$this->Session->read('User.id'),$this->Server->id);
    $this->_alertChanges($hostname,true);
    print "System has been Added" ;
}


function editColumn() {
        /*
        * TODO Check null & Uniq also default length
        * Make better validation for the data before we update it
        * We need to check the uploaded files in more advance way (file type, number of file(s) and size
        */
        //echo debug ($_POST);
        $this->layout='ajax';
        if((trim($_POST['table'])=='') or (trim($_POST['name'])=='') or (trim($_POST['id'])=='')) {

        print 'ERROR! ' . __LINE__;
        return false;
        }

        $id=$_POST['id'];
        $table=$_POST['table'];
        $name=$_POST['name'];
        if($this->fieldUserInfo[$name]['type_overwrite'] =='file') {
            $data=$_FILES['data'];
            $File_List =null;
            for($i=0; sizeof($data['name'])>$i; $i++) {
                if ((isset($data['error'][$i]) && $data['error'][$i] == 0) || (!empty( $data['tmp_name'][$i]) && $data['tmp_name'][$i] != 'none')) {
                if(is_uploaded_file($data['tmp_name'][$i])) {
                    $now = date('is');
                    $filename = str_replace(' ', '_', $data['name'][$i]);
                    $filename = $_POST['table'].''.$id.''.$name.''.$now.''.$filename;
                    $url = FILES_DIR .'/'.$filename;
                    $success = move_uploaded_file($data['tmp_name'][$i], $url);
                    if($success) {
                        if($File_List!=null) {
                        $File_List .="\n";
                        }
                        $File_List .= $filename . ','. $data['name'][$i];
                        }
                    }
                }
            }
            $data=$File_List;
        }else {
            if(is_array($_POST['data'])) {
                $data=implode(',', $_POST['data']);
               // echo debug ($data);
            }else {
                $data=$_POST['data'];
               
            }
    }
    $schema=$this->Server->schema();
   if(!isset($schema[$name])) {
        print 'ERROR! ' . __LINE__;
        return false;
    }
    if(($data=='') && ($schema[$name]['null']!='1')) {
        if( ($data=='') && ($schema[$name]['default']=='')) {
            print 'Error: no data!';
            return false;
        }
        $data = $schema[$name]['default'];
    }
    if( (isset($schema[$name]['key'])) && ($schema[$name]['key'] == 'unique')) {
        $row=null;
        $row = $this->Server->find('first', array('conditions' => array('Server.'.$name => $data)));
        if($row!=null) {
            print 'Error: must be a unique value';
            return false;
        }
    }

    //security check to make sure he can edit the value
    if($this->fieldUserInfo[$name]['editable'] != '1' ) {
        print 'ERROR!';
        return false;
    }
    
    $oldData= $this->Server->read(array($name,'hostname'), $id);
    $hostname = $oldData['Server']['hostname'];
    $oldData = $oldData['Server'][$name];
    $this->Server->set(array($name=>$data));
    $this->Server->save();
    //echo debug ($data);
    $output = $this->Field->showField($data,$this->fieldUserInfo[$name]);
 
   if($oldData!=$data) {
    // echo debug("Audit");
        $this->loadModel('Audit');
        $this->Audit->auditThis('Edit',$this->fieldUserInfo[$name]['title'].' from "' . $oldData . '" To "'.$data .'"',$this->name,$this->Session->read('User.id'),$id);
//Enable below line to enable send mail for chnage.        
        
        $this->_alertChanges($hostname,false,$oldData,$data,$this->fieldUserInfo[$name]['title']);
    }
    
    print $output;
    
    if($output=='') print '';
    }

function _alertChanges($hostname,$add=false,$oldData=null,$data=null,$title=null) {
$this->loadModel('User');
$AlertUsers = $this->User->Find('all', array('fields'=>array('name','eMail'),'conditions' => array('server_alertedit' => 1)));
$mailName = Configure::read('app.email.name');
$mailFrom = Configure::read('app.email.from');
$appName = Configure::read('app.name');
$subject = '['.$appName.'] '. $this->name . ' ' . $hostname;
$from = $mailName.' <'.$mailFrom.'>';
if($add) {
$message = 'New server has been inserted to the system by (' . $this->Session->read('User.name'). ")";
}else {
$message = 'Some changes has been done by (' . $this->Session->read('User.name'). ")\n\n" . $title.' changed from "' . $oldData . '" To "'.$data .'"' ;
$message .= "\n\nHost:" . $hostname;
}
if(Configure::read('app.email.delivery')=='smtp') {
/* SMTP Options */
$this->Email->smtpOptions = array(
'port'=>Configure::read('app.email.port'),
'timeout'=>Configure::read('app.email.timeout'),
'host' => Configure::read('app.email.host'),
'username'=>Configure::read('app.email.username'),
'password'=>Configure::read('app.email.password')
);

/* Set delivery method */
$this->Email->delivery = 'smtp';
}

foreach($AlertUsers as $User) {
$this->Email->from = $from;
$this->Email->to = $User['User']['name'].' <'.$User['User']['eMail'].'>';
$this->Email->subject = $subject;
//$this->Email->send($message); //To be enabled in production setup.
$this->Email->reset();
}
}
function getAudit($id) {
    $this->layout='ajax';
    if($this->Session->read('Group.server_showAudit')=='1') {
        $this->loadModel('Audit');
        App::import( 'Helper', 'Time' );
        $time = new TimeHelper(new View(null));
        $this->set('time',$time);
        $this->set('data',$this->Audit->getAuditbyRowID($id));
    }else {
        $this->set('error','You are not authorized to view this page');
    }

}

function getSysaudit() {
if($this->Session->read('Group.server_showSecurity')=='1') {
$this->loadModel('Sysaudit');
$this->set('data',$this->Sysaudit->find('all',array('fields'=>array('Sysaudit.*','Server.hostname','Server.os_type'),'order'=>'Sysaudit.check_date DESC')));
}else {
$this->set('error','You are not authorized to view this page');
}

}

function getHostname($id) {
$this->layout='ajax';
$hostname= $this->Server->read('hostname', $id);
$hostname = $hostname['Server']['hostname'];
$this->set('data',$hostname);
}
/*
* TODO compress CSS, js and html files
*/
function download($field,$id,$internal,$Human) {
$this->loadModel('Audit');
$this->view = 'Media';
$this->autoLayout = true;
/*
* TODO fix this, we can't trust $field
*/
$path_parts = pathinfo($internal);
$params = array(
'id' => $internal,
'name' => $path_parts['filename'],
'extension' => $path_parts['extension'],
'path' => FILES_DIR.'/'.$internal,
'cache'=>true
);
//debug($params);print "($field,$id,$internal,$Human)";
$this->set('download', true);
$this->set($params);

/*
* TODO Audit in download method
*/
//$this->Audit->auditThis('Edit',$this->fieldUserInfo[$name]['title'].' from "' . $oldData . '" To "'.$data .'"',$this->name,$this->Session->read('User.id'),$id);
}

function _setUserSettings() {
    $userInfo = $this->Session->read('User');
    $groupInfo = $this->Session->read('Group');
    $info = $groupInfo;
    // echo debug($groupInfo);
    //echo debug($info);
    $info[$this->Sname.'_edit'] = str_replace("\n",',',$info[$this->Sname.'_edit']);
    $info[$this->Sname.'_show'] = str_replace("\n",',',$info[$this->Sname.'_show']);
    $info[$this->Sname.'_edit'] = str_replace("\r",'',$info[$this->Sname.'_edit']);
    $info[$this->Sname.'_show'] = str_replace("\r",'',$info[$this->Sname.'_show']);
    $edit =array_flip(explode(",",$info[$this->Sname.'_edit']));
    $groupShow =array_flip(explode(",",$info[$this->Sname.'_show']));
    $show=array();
    //echo debug($info);
    $this->set('groupShow',$groupShow);

    // All this just to make sure user will not try to show something not allowed in his group
    if($userInfo[$this->Sname.'_show']!='') {
        $userInfo[$this->Sname.'_show'] = str_replace("\n",',',$userInfo[$this->Sname.'_show']);
        $userInfo[$this->Sname.'_show'] = str_replace("\r",'',$userInfo[$this->Sname.'_show']);
        $userShow =array_flip(explode(",",$userInfo[$this->Sname.'_show']));
        $info[$this->Sname.'_show']=null;
        foreach($userShow as $fi => $tmp) {
            if ( (isset($groupShow[$fi])) or (isset($groupShow['*']))) {
                $show[$fi]=1;
            }
        }
    }else {
        $show = $groupShow;
    }

    /*
    * TODO: Make the user able to set the order of the columns by him self
    * TODO: astrik is not working!!
    */
    if ( ($info[$this->Sname.'_edit'] != '*') or ($info[$this->Sname.'_show'] != '*') ) {
        foreach($this->fieldUserInfo as $name => $val) {
            if($info[$this->Sname.'_edit'] != '*') {
                if (!isset($edit[$name])) {
                    $this->fieldUserInfo[$name]['editable'] = '0';
                }
            }

            if($info[$this->Sname.'_show'] != '*') {
                if (!isset($show[$name])) {
                    $this->fieldUserInfo[$name]['show'] = '0';
                }
            }

        }
    }

    $this->set('userInfo',$userInfo);
    $this->set('groupInfo',$groupInfo);

}

function _setFindParams($group=array(),$limit=null) {
    if( is_null($limit)){
        $limit = $this->Session->read('User.server_perpage');
    }elseif($limit==0){
        $limit = null;
    }
    $data = array('limit' => $limit,'fields'=>array(),'recursive' => 1,'group' =>$group);
    $data['fields'][] = 'id';
    if (($this->Session->check('Search'))) {//or ($this->Session->check('Search'.'location'))){
        $qsearch_conditions=array();
        $Search = $this->Session->read('Search');
        foreach ($Search as $key => $val){
            $qsearch_conditions[] = array('Server.'.$key=> $val);
        }
        if ($this->Session->check('Search.hostname')){ $qsearch_conditions[] = array('Server.hostname'=>$this->Session->read('Search.hostname')); }
        $data['conditions']['OR'] = $qsearch_conditions;
        foreach($this->fieldUserInfo as $name => $val) {
            //echo debug ($name);
            if($this->fieldUserInfo[$name]['show']=='1') $data['fields'][] = $name;
        }

    }else{
        //echo debug("Setting search params");
        $qsearch = $this->Session->read('qsearch');
        if(isset($_GET['qsearch'])) {$qsearch = $_GET['qsearch'];}
        $this->set('qsearch',$qsearch);
        $qsearch_conditions=array();
        $qsearch = str_replace('*','%',$qsearch);
        $qsearch=(preg_replace('/\s+$/m', '', $qsearch));
        
        foreach($this->fieldUserInfo as $name => $val) {
           // echo debug ($name);
            if($this->fieldUserInfo[$name]['show']=='1') $data['fields'][] = $name;
            if(($this->fieldUserInfo[$name]['qsearch']=='1') && ($qsearch != '')) {
                $qsearch_conditions[]=array("Server.$name LIKE" => "%$qsearch%");
                }
               // echo debug ($data);
        }
        if($qsearch != '') $data['conditions']['OR'] = $qsearch_conditions;
    }
    /*
    * TODO: Add control for user show per record
    */
    //echo debug($data);
    return $data;
}

function index($subaction=null) {
    //Configure::write('Session.timeout', '28800');
    //echo debug($this->Session->read());
    //$session=$this->Session->read();
    if(isset($_GET['qsearch'])!='') {
        $this->Session->write('qsearch', (trim($_GET['qsearch'])));
    }
    //echo debug ($this->request);
    if ($subaction == "adsearch" ){
        //echo debug ($subaction);
        if (!empty($this->request->data)){
        //echo debug("Running Ad Search by replacing Cookies");
        $this->Session->write('adsearch', $this->request->data);
        //echo debug ($this->Session->read('adsearch'));
        $this->paginate= $this->aquery($this->request->data);
        
       // echo debug($this->Session->read('adsearch'));
        }else{
            
            $qdata=$this->Session->read('adsearch');
            //echo debug("Running Ad Search using old Cookies");
            //echo debug( $qdata);
            if (!empty($qdata)){
            $this->paginate= $this->aquery($qdata);
            } else{
              //  echo debug("Adv Search -Empty Cookie, Doing normal search");
                $this->paginate = $this->_setFindParams();  //if not post data in request and cookie is deleted, do normal page load.
            }
        }
    }else{// subaction is not adsearch
       // echo debug($this->Session->read('adsearch'));
       // echo debug ("Running normal query. Deleting Cookie");
        $this->Session->delete('adsearch'); //Once the index page is directly access, cookie can be deleted.
        $this->paginate = $this->_setFindParams();
    }
    //echo debug($this->_setFindParams());
    $this->set('subaction',$subaction);
    $this->set('FieldModel',$this->Field);
    $this->set('fieldUserInfo',$this->fieldUserInfo);
    $this->set('schema',$this->Server->schema());
    $this->set('data',$this->paginate());
   // echo debug($this->paginate());
    $this->set('selectdata',($this->_getselectdata()) );
}

function export() {
    $qdata=$this->Session->read('adsearch');
   // echo debug ($qdata);
$this->layout='ajax';
if(($this->Session->read('Group.server_export'))=='1'){
    $qdata=$this->Session->read('adsearch');
    //echo debug ($qdata);
    if (!empty($qdata)){ $data= $this->aquery($qdata, null,0); } else{$data = $this->_setFindParams(null,0);}
    //$data = $this->_setFindParams(null,0);
    
    $this->set('FieldModel',$this->Field);
    $this->set('fieldUserInfo',$this->fieldUserInfo);
    $this->set('schema',$this->Server->schema());
    $this->set('data',$this->Server->find('all',$data));
    }else{ die('!!!'); }
}

function _getselectdata(){
        //empty( $selectdata);
    $selectdata = array('selected'=>array(),'uniq' =>array());
    foreach ($this->fieldInfo as $item=>$value) {
        if ($value['advsearch'] ==1 )
        {
            $mydata = $this->Server->find('list', array(
            'fields'=>''.$value['field'].'',
            'order'=>'Server.'.($value['field']).' ASC',
            // 'conditions'=> array('Server.hostname.advsearch'=>'1'),
            'group' => ''.$value['field'] ));
            //echo debug ($mydata);
            $selectdata['uniq'][$value['field']]=  $mydata;    
        }
    }

    return $selectdata;
}
function _saveSession($urlvar=null, $sesvar=null){

//echo debug ($urlvar,$sesver);
if (isset($this->params['url']['hostname']))
        {
            if  (count($this->params['url']['$hostname']) == 1)
            {
                if (($this->params['url']['$hostname']['0'])=='')
                {
                $this->Session->write ('Search.gethost', $_GET['hostname']);
                }
                else  echo debug ("you have selected null $urlvar");
            }
        }

}
function testbed(){
    $this->layout='ajax';
   }


function setSession(){
//var $Search = array();
$this->Session->delete('Search');


foreach ($this->params['url']as $key=>$val)
        {
            if (($key !== 'url')&& (!empty ($this->params['url'][$key])))
                {
                   // echo debug($this->params['url']['location']);
                    if ((isset($this->params['url'][$key])) && ((count($this->params['url'][$key]) !== 1) || (($this->params['url'][$key]['0'])!=='')))
                    { $this->Session->write ('Search.'.$key, $_GET[$key]); } else { echo debug ("you have selected null $key 1 st");}// $this->Session->delete('Search.gethost'); }

                }
    }
}

public function aquery($query, $group=array(),$limit=null){
    //echo debug ($query);
    $andcondition=array();
    $notcondition=array();
     foreach ($query as $key=>$val){
         //echo debug ($val);
         switch ($val['querytype'])
            {
        case 1: //Equals
            $condition=array("Server.".$val['field']=>$val['querytext']);
            array_push ($andcondition, $condition);
            break;
            
        case 2://Does not equal
            $condition=array("Server.".$val['field']=>$val['querytext']);
            array_push ($notcondition, $condition);
            break;
        case 3://Is greater than     
            $condition=array("Server.".$val['field']." > "=>$val['querytext']);
            array_push ($andcondition, $condition);
            break;
        case 4://Is greater than or equal to
            $condition=array("Server.".$val['field']." >= "=>$val['querytext']);
            array_push ($andcondition, $condition);
            break;
        case 5://Is less than
            $condition=array("Server.".$val['field']." < "=>$val['querytext']);
            array_push ($andcondition, $condition);
            break;
        case 6://Is less than or equal to
            $condition=array("Server.".$val['field']." <= "=>$val['querytext']);
            array_push ($andcondition, $condition);
            break;
        case 7://Begins with
            $condition=array("Server.".$val['field']. " LIKE "=>$val['querytext']."%");
            array_push ($andcondition, $condition);
            break;
        case 8://Does not begin with 
            $condition=array("Server.".$val['field']. " LIKE "=>$val['querytext']."%");
            array_push ($notcondition, $condition);
            break;
        case 9://Ends with
            $condition=array("Server.".$val['field']. " LIKE "=> "%". $val['querytext']);
            array_push ($andcondition, $condition);
            break;
        case 10://Does not end with
            $condition=array("Server.".$val['field']. " LIKE "=> "%". $val['querytext']);
            array_push ($notcondition, $condition);
            break;
        case 11://Contains
            $condition=array("Server.".$val['field']. " LIKE "=> "%". $val['querytext']."%");
            array_push ($andcondition, $condition);
            break;
        case 12://Does not contain
            $condition=array("Server.".$val['field']. " LIKE "=> "%". $val['querytext']."%");
            array_push ($notcondition, $condition);
            break;
       }
     }
    if( is_null($limit)){
        $limit = $this->Session->read('User.server_perpage');
    }elseif($limit==0){
        $limit = null;
    }
    $data = array('limit' => $limit,'fields'=>array(),'recursive' => 1,'group' =>$group);
    $data['conditions']['AND'] = $andcondition;
    $data['conditions']['NOT'] = $notcondition;
    return $data;
     }
    
     public function validate_form(){
         $this->layout='ajax';
         $field=$this->request->data['field'];
         $value=$this->request->data['value'];
         $result=$this->validateData($field, $value);
         
         if ( $result['return']="fail"){
             $this->set('result', $result['error']);
             //echo debug("Fail");
         }else{
             //echo debug("Pass");
             $this->set('result',"");
         }

     }   
     public function validateData($field, $value){
         //$this->layout='ajax';
         $schema=$this->Server->schema();
         $fielduserinfo=$this->fieldInfo;
         $result=array();
         $result['return']="success";
         $result['error']="";
         if ($fielduserinfo[$field]['mandate']==TRUE){
             if (empty($value)){
                 $result['return']="fail";
                 $result['error']="should not be empty";
                 return $result;
                 
             }
             
         }
         if ($fielduserinfo[$field]['uniq']==TRUE){ 
             $duplicate=$this->Server->find('all', array('conditions'=>array($field=>$value)));
             //echo debug($duplicate);
             if(!empty($duplicate)){                 
                 $result['return']="fail";
                 $result['error']=$fielduserinfo[$field]['title'] . " is already exist.";
                 return $result;
             }
             
            
         }
         
         $format = $fielduserinfo[$field]['validation'];
         
         switch ($format) {
            case "email":
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $result['return']="fail";
                    $result['error']=$fielduserinfo[$field]['title'] . " Not an Email format ";
                }
                break;
         }
         return $result;
     }
     
     public function sysDetails(){
         $this->layout='ajax';
         $this->set('FieldModel',$this->Field);
         $id =$this->request->params['pass']['0'];
         $this->set('fieldUserInfo',$this->fieldUserInfo);
         $this->set('id',$this->request->params['pass']['0'] );
         $this->set('schema',$this->Server->schema());
         $this->set('data', $this->Server->find('all', array('conditions'=>array('id'=>$id))));
         
     }
     
     public function detail(){
         $this->set('FieldModel',$this->Field);
         $id =$this->request->params['pass']['0'];
         $this->set('fieldUserInfo',$this->fieldUserInfo);
         $this->set('id',$this->request->params['pass']['0'] );
         $this->set('schema',$this->Server->schema());
         $this->set('data', $this->Server->find('all', array('conditions'=>array('id'=>$id))));
         
     }
     
     public function UpdateComment(){
         $this->layout='ajax';
         $this->loadModel('History');
         //echo debug ($this->Session->read('User'));
         //$groupInfo = $this->Session->read('Group');
         //echo debug($this->request->data);
         //echo debug ($this->Session->read('Group.server_addcomment'));
         if ((trim($this->request->data['content'])=='')|| (trim($this->request->data['server_id'])=='') || ($this->Session->read('Group.server_addcomment')==0)){    
            //print 'ERROR!';
            $data['0']="Error";
            $this->set('data', $data);
            return false;
         }
         $data=$this->request->data;
         $data['user_name']=$this->Session->read('User.name');
         $this->History->save($data);
         $history = $this->History->find('all',array('conditions'=>array('server_id'=>$this->request->data['server_id'])));
         $this->set('data', $data);
    }
    
    public function getComment($server_id){
        $this->layout='ajax';
        if($this->Session->read('Group.server_showcomment')=='1') {
            $this->loadModel('History');
            $this->loadModel('User');
            $this->set('history', $this->History->find('all',array('conditions'=>array('server_id'=>$server_id), 'order' => 'History.id desc')));
            $this->set('users', $this->User->find('all'));
            App::import( 'Helper', 'Time' );
            $time = new TimeHelper(new View(null));
            $this->set('time',$time);
            }else {
                $this->set('error','You are not authorized to view this page');
             }
        }
    public function DeleteServer($id){
        $this->layout='ajax';
        if($this->Session->read('Group.server_delete')=='1') {
            $this->Server->delete($id);
        }else{
        print "ERROR";
        }
    }
        
        
        
}

?>