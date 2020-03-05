<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class UsersController extends AppController
{
    /*
    ** Default constructor
    */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'token']);
    }

    /*
    ** Add a new user through JWT
    ** @return: \Cake\Http\Response - redirects to index page
    */
    public function add()
    {
    $this->Crud->on('afterSave', function(Event $event) {
        if ($event->subject->created) {
            $this->set('data', [
                'id' => $event->subject->entity->id,
                'token' => JWT::encode(
                    [
                        'sub' => $event->subject->entity->id,
                        'exp' =>  time() + 604800
                    ],
                Security::salt())
            ]);
            $this->Crud->action()->config('serialize.data', 'data');
        }
    });
    return $this->Crud->execute();
    }

    /*
    ** Get a token as a user for JWT
    ** @return: tokens
    */
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }

        $this->set([
            'success' => true,
            'data' => [
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
            Security::salt())
            ],
            '_serialize' => ['success', 'data']
        ]);
    }
}