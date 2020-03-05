<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
    /*
    ** Default constructor
    */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->Auth->allow(['index', 'view', 'display']);
        
    }

    /*
    ** Index all the articles
    */
    public function index()
    {
        $articles = $this->Articles->find('all');
        $this->set([
            'articles' => $articles,
            '_serialize' => ['articles']
        ]);
    }

    /*
    ** View the article
    ** @param: id of article
    */
    public function view($id)
    {
        $article = $this->Articles->get($id);
        $this->set([
            'article' => $article,
            '_serialize' => ['article']
        ]);
    }

    /*
    ** Add a new article
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
        $this->set([
            'article' => $article,
            '_serialize' => ['article']
        ]);
    }
    
    /*
    ** Edit a article
    ** @return: redirects to index page
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

        $this->set([
            'article' => $article,
            '_serialize' => []
        ]);
    }

    /*
    ** Delete an article
    ** @param: id of article
    ** @return: redirects to index page
    */
    public function delete($id)
    {
        $this->request->allowMethod([ 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
            $this->set([
                '_serialize' => []
            ]);
            return $this->redirect(['action' => 'index']);
        }
    }

    /*
    ** Check if the user is authorized for certain actions
    ** @param: id of user
    ** @return: boolean true or false
    */
    public function isAuthorized($user)
    {
        // All registered users can add articles
        if ($this->request->getParam('action') == 'add') {
            return true;
        }

        if ($this->request->getParam('action') == 'view') {
            return true;
        }

        if ($this->request->getParam('action') == 'display') {
            return true;
        }

        if ($this->request->getParam('action') == 'index') {
            return true;
        }

        // The owner of an article can edit and delete it
        if (in_array($this->request->getParam('action'), ['edit', 'delete'])) {
            $articleId = (int)$this->request->getParam('pass.0');
            if ($this->Articles->isOwnedBy($articleId, $user['id'])) {
                return true;
            } else {
                return false;
            }
        }

        return parent::isAuthorized($user);
    }
}
