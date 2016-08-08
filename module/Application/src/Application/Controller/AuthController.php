<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Db\Adapter\Adapter;

use Application\Model\Login;
use Application\Model\User;
use Application\Model\Register;

class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authservice;
    protected $userTable;

    public function getUserTable()
     {
         if (!$this->userTable) {
             $sm = $this->getServiceLocator();
             $this->userTable = $sm->get('Application\Model\UserTable');
         }
         return $this->userTable;
     }
     

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                                  ->get('Application\Model\MyAuthStorage');
        }

        return $this->storage;
    }

    public function getForm()
    {
        if (! $this->form) {
            $login       = new Login();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($login);
        }

        return $this->form;
    }

    public function getRegisterForm()
    {
        if (! $this->form) {
            $register    = new Register();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($register);
        }

        // persistencia
    
        $request = $this->getRequest();
        if ($request->isPost()){
            $this->form->bind($register);
            $this->form->setData($request->getPost());
            if ($this->form->isValid()){
                print_r($this->form->getData());
                $u = new User();
        $u->id = 0;
        $u->username = $this->form->getData()->username;
        $u->password =$this->form->getData()->password;
        $u->email = $this->form->getData()->email;
        $u->name = $this->form->getData()->name;
        $this->getUserTable()->saveUser($u);
            }
        }
        

        return $this->form;
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('success');
        }

        $form       = $this->getForm();


        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }

    public function registerAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('success');
        }

        $form       = $this->getRegisterForm();
              

        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        $form       = $this->getForm();
        $redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('username'))
                                       ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach ($result->getMessages() as $message) {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'success';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                             ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }
        }

        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            $this->getSessionStorage()->forgetMe();
            $this->getAuthService()->clearIdentity();
            $this->flashmessenger()->addMessage("You've been logged out");
        }

        return $this->redirect()->toRoute('login');
    }
}
