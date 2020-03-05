<?php
// src/Model/Table/ArticlesTable.php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('title')
            ->requirePresence('title')
            ->notEmpty('body')
            ->requirePresence('body');

        return $validator;
    }

    /*
    ** Check is the article is owned by the user
    ** @param: articleId and userId
    ** return: boolean true or false
    */

    public function isOwnedBy($articleId, $userId)
    {
    return $this->exists(['id' => $articleId, 'user_id' => $userId]);
    }
}