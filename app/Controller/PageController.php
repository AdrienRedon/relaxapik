<?php 

namespace App\Controller;

use App\Core\Controller\Controller;
use App\Validator\LoginValidator;
use App\Validator\SigninValidator;
use App\Core\DependencyInjection\ContainerInterface;

class PageController extends Controller
{
    protected $auth;

    public function __construct(ContainerInterface $container = null)
    {
        parent::__construct($container);
        $this->auth = $this->container->resolve('Auth');
    }

    public function index()
    {
        $this->view->render('Page/index');
    }

    public function admin()
    {
        if ($this->auth->check()) { // toto : admin
            $content = 'vous êtes bien connecté';
        } else {
            $content = 'vous n\'êtes pas connecté';
        }
        $this->view->render('Page/admin', compact('content'));
    }

    public function login()
    {
        $validation = new LoginValidator();
        if ($validation->fails()) {
            $this->flash->set($validation->getErrors());
            $this->redirect->backWithInput($_POST);
        }
        if ($this->auth->attempt($_POST['mail'], $_POST['password'])) {
            $this->index();
        } else {
            $this->flash->set('login incorrect');
            $this->redirect->backWithInput($_POST);
        }
    }

    public function signin()
    {
        $input = array(
            'mail'    => $_POST['mail'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        );
        $validation = new SigninValidator();
        if ($validation->fails()) {

            $this->flash->set($validation->getErrors());
            $this->redirect->backWithInput($_POST);
        } else {
            $user = $this->modelResolver->get('User');
            $user->save($input);
            $this->redirect->home();
        }
    }

    public function pathologies()
    {
        $typePatho = $this->model->get('TypePatho');
        $meridien = $this->model->get('Meridien');

        $typesPatho = $typePatho->all()->toArray();
        $meridiens = $meridien->all()->toArray();

        $this->view->render('Page/patho', compact('typesPatho', 'meridiens'));
    }

    public function salons()
    {
        $this->view->render('Page/salons');
    }

    public function membres()
    {
        $this->view->render('Page/membres');
    }
}
