<?php
// src/Controller/ArticlesController.php

namespace App\Controller\Admin;

use App\Controller\AppController;

class ArticlesController extends AppController
{
    /*
    ** Default constructor
    */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
    }

    /*
    ** Index all the articles
    */
    public function index()
    {
        $this->set('articles', $this->Articles->find('all'));
    }

    /*
    ** View the article
    ** @param: int $id - id of article
    */
    public function view($id)
    {
        $article = $this->Articles->get($id);
        $this->set(compact('article'));
    }

    /*
    ** Add a new article
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->user_id = $this->Auth->user('id');
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }
    
    /*
    ** Edit an article
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id);
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
    }

        $this->set('article', $article);
    }

    /*
    ** Delete an article
    ** @param int $id - id of article
    ** @return \Cake\Http\Response - redirect to index page
    */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
            return $this->redirect(['action' => 'index']);
        }
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
            $articleId = (int)$this->request->getParam('pass.0');
            if ($this->Articles->isOwnedBy($articleId, $user['id'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}