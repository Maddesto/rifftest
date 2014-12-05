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

class AdminController extends AbstractActionController
{
	protected $authservice;
	
	public function getAuthService()
	{
		if (! $this->authservice) {
			$this->authservice = $this->getServiceLocator()->get('AuthAdminService');
		}
		return $this->authservice;
	}
	/*
	 * url /admin/user_type/usermane/edit
	 */
	public function editAction() {
	    $form = $this->getServiceLocator()->get('AdminEditForm');
     	$request = $this->getRequest();
    	$username = $this->getEvent()->getRouteMatch()->getParam('user_name');
    	$type = $this->getEvent()->getRouteMatch()->getParam('type');
    	if(empty($username)){
    		throw new \Exception("Wrong username");
    	}
    	if(empty($type)){
    		throw new \Exception("Wrong type");
    	}
    	$usersTable = $this->getServiceLocator()->get('UsersTable');
    	$userinfo = $usersTable->getUserByUsername($username);
    	if ($request->isPost()) {
    		$gender = $this->params()->fromPost('gender');
    		if($gender == Gender::MALE){
    			$filter = $this->getServiceLocator()->get('AdminEditFilterMan');
    		}else{
    			$filter = $this->getServiceLocator()->get('AdminEditFilterWoman');
    		}
    		$filter->setUser($userinfo);
    		$form->setInputFilter($filter->getInputFilter());
    		$form->setData($request->getPost());
    		$user = new Users();
    		
    		$form->setUseInputFilterDefaults(false);
    		if ($form->isValid()) {
    			$user->exchangeArray($form->getData());
    			if($usersTable->saveUser($user)){
    				$this->flashMessenger()->addMessage('Updated Successfully');
    				return $this->redirect()->toRoute('admin/user_list/admin_edit', array(
    						'type'=>$type,'user_name'=>$username));
    			}
    		}
    	}else{
    		$form->bind($userinfo);
    	}
    	return new ViewModel(array('form' => $form, 'type'=>$type, 'user_name'=>$username));
    }
	/*
	 * url /admin/user_type
	 */
	public function viewAction()
    {	
    	//$sort_form = $this->getServiceLocator()->get('AdminSortForm');
    	$genderType = $this->getEvent()->getRouteMatch()->getParam('type');
    	$page = $this->getEvent()->getRouteMatch()->getParam('page');
    	$genders = Gender::returnArrayForRote();
    	$gender = $genders[$genderType];
    	if(empty($gender)){
    		throw new \Exception("Wrong user gender");
    	}
    	$usersTable = $this->getServiceLocator()->get('UsersTable');
    	//$users = $usersTable->search($gender);
    	$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\DbTableGateway($usersTable->getTableGateway(), array('gender'=>$gender, 'role'=>Users::USERROLE)));
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(4);
    	return new ViewModel(array('sort_form' => $sort_form,'paginator' => $paginator, 'genderType'=>$genderType));
    }
  	
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

    				return $this->redirect()->toRoute('admin/user_list', array(
    						'type' =>Gender::MALESTR
    				));
    			}
    			$this->flashMessenger()->addMessage('The username or password you have entered is
incorrect. Please try again');
    			return $this->redirect()->toRoute('admin');
    		}
    	}
    	return new ViewModel(array('form' => $form));
    }
    
    public function logoutAction()
    {
    	$this->getAuthService()->clearIdentity();
    	return $this->redirect()->toRoute('admin');
    }
}
