<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\RegisterForm;
use User\Model\Gender;
use User\Model\Users;

class IndexController extends AbstractActionController
{
	protected $authservice;
	
	public function getAuthService()
	{
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator()->get('AuthService');
		}
		return $this->authservice;
	}
	/*
	 * url /usermane/edit
	 */
	public function editAction() {
	    $form = $this->getServiceLocator()->get('UserEditForm');
     	$request = $this->getRequest();
    	$username = $this->getEvent()->getRouteMatch()->getParam('username');
    	if(empty($username)){
    		throw new \Exception("Wrong username");
    	}
    	$usersTable = $this->getServiceLocator()->get('UsersTable');
    	$userinfo = $usersTable->getUserByUsername($username);
    	if ($request->isPost()) {
    		$gender = $this->params()->fromPost('gender');
    		if($gender == Gender::MALE){
    			$filter = $this->getServiceLocator()->get('UserEditFilterMan');
    		}else{
    			$filter = $this->getServiceLocator()->get('UserEditFilterWoman');
    		}
    		$filter->setUser($userinfo);
    		$form->setInputFilter($filter->getInputFilter());
    		$user = new Users();
    		$password = $this->params()->fromPost('password');
    		$confirm_password = $this->params()->fromPost('confirm_password');
    		if(!empty($confirm_password)){
    			$request->getPost()->set('confirm_password', md5($confirm_password));
    		}
    		$form->setData($request->getPost());
    		$form->setUseInputFilterDefaults(false);
    		if ($form->isValid()) {
    			$user->exchangeArray($form->getData());
    			if($confirm_password && $password) $user->setPassword($password);
    			if($usersTable->saveUser($user)){
    				$this->flashMessenger()->addMessage('Updated Successfully');
    				return $this->redirect()->toRoute('home/view/edit', array(
    						'username'=>$username));
    			}
    		}
    	}else{
    		$form->bind($userinfo);
    	}
    	return new ViewModel(array('form' => $form, 'username'=>$username));
    }
	/*
	 * url /username
	 */
	public function viewAction()
    {	
    	$username = $this->getEvent()->getRouteMatch()->getParam('username');
    	if(empty($username)){
    		throw new \Exception("User was not selected");
    	}
    	$usersTable = $this->getServiceLocator()->get('UsersTable');
    	$photoForm = $this->getServiceLocator()->get('PhotoUploadForm');
    	$userinfo = $usersTable->getUserByUsername($username);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$message = 'Invalid file';
    		$image = $request->getFiles('image');
    		$validator = new \Zend\Validator\File\IsImage(array('enableHeaderCheck'=>true));
    		if ($validator->isValid($image)) {
    			$usersTable->setPhoto($userinfo->id, $image);
    			$message = 'Updated Successfully';
    		}
    		$this->flashMessenger()->addMessage($message);
    		return $this->redirect()->toRoute('home/view', array(
    				'username'=>$username));
	
    	}
    	return new ViewModel(array('photo_form' => $photoForm,'user_info' => $userinfo, 'username' => $username));
    }
  	/*
  	 * url /login
  	 */
  	public function loginAction()
    {	
    	$form = $this->getServiceLocator()->get('LoginForm');
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$filter = $this->getServiceLocator()->get('LoginFilter');
    		$form->setInputFilter($filter->getInputFilter());
    		$form->setData($request->getPost());
    		if($form->isValid()){
    			$adapter = $this->getAuthService()->getAdapter();
    			$adapter->setIdentity($request->getPost('email'));
    			$adapter->setCredential($request->getPost('password'));
    			$result = $this->getAuthService()->authenticate();
    			if ($result->isValid()) {
    				$resultRow =  $adapter->getResultRowObject();
    				$this->getAuthService()->getStorage()->write($resultRow);

    				return $this->redirect()->toRoute('home/view', array(
    						'username' =>$resultRow->username
    				));
    			}
    			$this->flashMessenger()->addMessage('The username or password you have entered is
incorrect. Please try again');
    			return $this->redirect()->toRoute('login');
    		}
    	}
    	return new ViewModel(array('form' => $form));
    }
    
    public function logoutAction()
    {
    	$this->getAuthService()->clearIdentity();
    	return $this->redirect()->toRoute('home');
    }
    /*
     * url /confirm
     */
    public function confirmAction()
    {
    	return new ViewModel();
    }
    /*
     * url /register
     */
    public function registerAction() {
    	$form = $this->getServiceLocator()->get('RegisterForm');
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$gender = $this->params()->fromPost('gender');
    		if($gender == Gender::MALE){
    			$filter = $this->getServiceLocator()->get('RegisterFilterMan');
    		}elseif($gender == Gender::FEMALE){
    			$filter = $this->getServiceLocator()->get('RegisterFilterWoman');
    		}else throw \Exception("Select gender");
    		$form->setInputFilter($filter->getInputFilter());
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
				$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')->getDriver()->getConnection()->beginTransaction();
				try{
					$user = new Users();
					$usersTable = $this->getServiceLocator()->get('UsersTable'); 
					$user->exchangeArray($form->getData());
					$user->setPassword($this->params()->fromPost('password'));
					$usersTable->saveUser($user);
				}catch(\Exception $e){
					$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')->getDriver()->getConnection()->rollback();
					$this->flashMessenger()->addMessage($e->getMessage());
					return $this->redirect()->toRoute('register');
				} 
    			$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')->getDriver()->getConnection()->commit();
    			return $this->redirect()->toRoute('confirm');
    		}
    	}
    	return new ViewModel(array('form' => $form));
    }
}
