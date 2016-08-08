<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TesteController extends AbstractActionController
{
	public function indexAction()
	{
		die('aqui');
	}

	public function getUserTableAction()
	{
		if (!$this->userTable) {
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('Application\Model\UserTable');
		}
		return $this->userTable;
	}
}



/**
 * IndexController - The default controller class
 * 
 * @author Enrico Zimuel (enrico@zimuel.it)
 * @version 0.1
 */
/*
require_once Zend\Controller\Action;

class TesteController extends Zend_Controller_Action 
{
	/**
	 * The default action - show the home page
	 */
    public function indexAction() 
    {
        // Redirect all'homepage
        $this->_redirect('/home');
    }
    /**
     * Login
     */
    public function loginAction()
    {
        $flash = $this->_helper->getHelper('flashMessenger');
        if ($flash->hasMessages()) {
            $this->view->message = $flash->getMessages();
        }
        
        $this->view->form= new Forms_Login();
        $this->render('login'); 
    }
    /**
     * Submit 
     */
    public function submitAction()
    {
        $form= new Forms_Login();
        if (!$form->isValid($_POST)) {
        	if (count($form->getErrors('token')) > 0) {
        		return $this->_forward('csrf-forbidden', 'error');
        	} else {
            	$this->view->form = $form;
            	return $this->render('login');
        	}	
        }
        
        $username= $this->getRequest()->getPost('username');
        $password= $this->getRequest()->getPost('password');
		
        $authAdapter = new Zend_Auth_Adapter_DbTable(
                        		Globals::getDbConnection(),
    							'users',
    							'username',
    							'password',
                        		'MD5(CONCAT(salt,?)) AND active=1'
                       		);
        
        $authAdapter->setIdentity($username)
                    ->setCredential($password);
                    
        $result= $authAdapter->authenticate();
        
        Zend_Session::regenerateId();
        
        if (!$result->isValid()) {        
            $this->_helper->flashMessenger->addMessage("Authentication error.");
            $this->_redirect('/index/login');   
        } else {
            Globals::getSession()->username= $result->getIdentity();
            Zend_Loader::loadClass('Users');
            $users= new Users();
            $data= array ('last_access' => date('Y-m-d H:i:s'));
            $where= $users->getAdapter()->quoteInto('username = ?', Globals::getSession()->username);
            if (!$users->update($data,$where)) {
            	throw new Zend_Exception('Error on update last_access');
            }
            $this->_redirect('/home');
        }
    }
}
*/