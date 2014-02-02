<?php
class TeesController extends AppController {

	var $name = 'Tees';

	function index() {
		$this->Tee->recursive = 0;
		$this->set('tees', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid tee', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('tee', $this->Tee->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Tee->create();
			if ($this->Tee->save($this->data)) {
				$this->Session->setFlash(__('The tee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tee could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Tee->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid tee', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Tee->save($this->data)) {
				$this->Session->setFlash(__('The tee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tee could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Tee->read(null, $id);
		}
		$users = $this->Tee->User->find('list');
		$this->set(compact('users'));
	}


}
?>