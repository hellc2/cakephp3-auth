<?php
// src/Controller/UsersController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{

    public function beforeFilter(Event $event){
	parent::beforeFilter($event);
	// Allow users to register and logout.
	// You should not add the "login" action to allow list. Doing so would
	// cause problems with normal functioning of AuthComponent.
	$this->Auth->allow(['login', 'logout']);
    }

    public function index(){
	return $this->redirect("/dashboard");

        $this->set('users', $this->paginate($this->Users));
	$this->set(compact('users'));
    }

    public function dashboard(){
	$user = $this->Auth->user();
	$this->set(compact('user'));
    }

    public function login(){
	if ($this->request->is('post')) {
	    pr($this->Auth);
	    $user = $this->Auth->identify();
	    if ($user) {
		$this->Auth->setUser($user);
		return $this->redirect($this->Auth->redirectUrl());
	    }
	    $this->Flash->error(__('Invalid username or password, try again'));
	}
    }

    public function logout(){
	return $this->redirect($this->Auth->logout());
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

        /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null){
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }


    public function profile(){
        $id = $user = $this->Auth->user('id');

        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }


    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

}
