<?php
// src/Controller/Admin/UsersController.php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{

    /*
    ** Index all the users
    */
    public function index()
    {
        $this->set('users', $this->Users->find('all'));
    }

    /*
    ** View the user
    ** @param: int $id - id of user
    */
    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    /*
    ** Add a new user
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

    /*
    ** Edit a user
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function edit($id = null) {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }
    
    /*
    ** Delete a user
    ** @param: int $id - id of user
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /*
    ** Checks for authorization before loading
    */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to logout.
        $this->Auth->allow([ 'logout']);
    }

    /*
    ** To log in as a user
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    /*
    ** To log out as a user
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function logout()
    {
        $this->Auth->logout();
        return $this->redirect(['action'=>'login']);
    }

    /*
    ** Check if the user is authorized for certain actions
    ** @param: int $user - id of user
    ** @return: boolean true or false
    */
    public function isAuthorized($user)
    {
        // All registered users can add articles
        if ($this->request->getParam('action') === 'add') {
            return true;
        }

        // The owner of an article can edit and delete it
        if (in_array($this->request->getParam('action'), ['edit', 'delete'])) {
            $userid = (int)$this->request->getParam('pass.0');
            if ($user['id'] == $userid) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}