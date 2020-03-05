<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /*
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    /*public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');}
    
        use \Crud\Controller\ControllerTrait;

        public $components = [
            'RequestHandler',
            'Crud.Crud' => [
                'actions' => [
                    'Crud.Index',
                    'Crud.View',
                    'Crud.Add',
                    'Crud.Edit',
                    'Crud.Delete'
                ],
                'listeners' => [
                    'Crud.Api',
                    'Crud.ApiPagination',
                    'Crud.ApiQueryLog'
                ]
            ]
        ];
     
    /*
    ** Default constructor
    */
    public function initialize()
    {
        
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
		'authorize' => 'Controller',
		'loginRedirect' => [
                    'controller' => 'Articles',
                    'action' => 'index',
                    'prefix' => false
                ],
                'logoutRedirect' => [
                    'controller' => 'Pages',
                    'action' => 'display',
                    'home'
                ]
            ]);
    }
        
    /*
    ** Check if the user is authorized for certain actions
    ** @param: int user - id of user
    ** @return: boolean true or false
    */
    public function isAuthorized($user)
    {
        // Admin can access every action
        if ($this->request->getParam('prefix') == "admin"){
            return ($this->Auth->user('role') == "admin");
        } else {
            return true;
        }
        return true;
    }

    /*
    ** Checks for authorization before loading
    */ 
    public function beforeFilter(Event $event)
    {
       $User =  \Cake\ORM\TableRegistry::get('Users');
       $loggedId = $User->find()->where(['id' => $this->Auth->user('id')])->first(); 
       $this->set("loggeduser",$loggedId);
    }
}
