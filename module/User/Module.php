<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use User\Model\Users;
use User\Model\UsersTable;
use User\Model\BodyParams;
use User\Model\BodyParamsTable;
use User\Form\UserInfoFilterBasic;
use User\Form\RegisterFilterBasic;
use User\Form\AdminEditFilterMan;
use User\Form\AdminEditFilterWoman;
use User\Form\RegisterFilterMan;
use User\Form\RegisterFilterWoman;
use User\Form\UsersEditFilter;
use User\Form\UserEditFilterMan;
use User\Form\UserEditFilterWoman;
use User\Model\Photos;
use User\Model\PhotosTable;
use User\Form\PhotoUploadFilter;


class Module
{	

	public function onBootstrap(MvcEvent $e) {
		$this->initAcl($e);
		$e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
	}
	
	public function initAcl(MvcEvent $e) {
 
		$acl = new \Zend\Permissions\Acl\Acl();
		$roles = include __DIR__ . '/config/module.acl.roles.php';
		$allResources = array();
		foreach ($roles as $role => $resources) {
	 
			$role = new \Zend\Permissions\Acl\Role\GenericRole($role);
			$acl -> addRole($role);
	 
			//$allResources = array_merge($resources, $allResources);
	 
			//adding resources
			foreach ($resources as $resource) {
				 // Edit 4
				 if(!$acl ->hasResource($resource))
					$acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
			}
			//adding restrictions
			foreach ($resources as $resource) {
				$acl -> allow($role, $resource);
			}
		}
	 
		//setting to view
		$e -> getViewModel() -> acl = $acl;
 
	}
	
	public function checkAcl(MvcEvent $e) {
		$route = $e->getRouteMatch()->getMatchedRouteName();
		$app = $e->getApplication();
		$locator = $app->getServiceManager();
		$authAdapter = $locator->get('AuthService');
		if($authAdapter->hasIdentity() === true){
			$authAdapter->getIdentity();
			$userRole = $authAdapter->getIdentity()->role;
		}else{
			$userRole = 'guest';
		}
		if($userRole == 'user'){
			$username = $e->getRouteMatch()->getParam('username');
			if(!empty($username) && $authAdapter->getIdentity()->username != $username){
				$url = $router->assemble(array('username'=>$authAdapter->getIdentity()->username), array('name' => 'home/view'));
				$response = $e->getResponse();
				$response->setStatusCode(302);
				$response->getHeaders()->addHeaderLine('Location', $url);
				$e->stopPropagation();
			}
		}
	 
		if (/*$e->getViewModel()->acl->hasResource($route) && */!$e->getViewModel()->acl->isAllowed($userRole, $route)) {
			$router = $e->getRouter();
			if($userRole == 'user'){
				$url = $router->assemble(array('username'=>$authAdapter->getIdentity()->username), array('name' => 'home/view'));
			}elseif($userRole == 'admin'){
				$url    = $router->assemble(array('type'=>\User\Model\Gender::MALESTR), array('name' => 'admin/user_list'));
			}else $url = $router->assemble(array(), array('name' => 'login'));
         
            $response = $e->getResponse();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();            
		}
	}
	
	public function getServiceConfig()
     {
         return array(
             'factories' => array(
             	'AuthService' => function($sm) {
             		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             		$dbTableAuthAdapter = new AuthAdapter($dbAdapter, 'users','email','password', 'MD5(?) AND  role = "'.Users::USERROLE.'"');
             		$authService = new AuthenticationService();
             		$authService->setAdapter($dbTableAuthAdapter);
             		return $authService;
             	 },
             	 'AuthAdminService' => function($sm) {
             	 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             	 	$dbTableAuthAdapter = new AuthAdapter($dbAdapter, 'users','email','password', 'MD5(?) AND role = "'.Users::ADMINROLE.'"');
             	 	$authService = new AuthenticationService();
             	 	$authService->setAdapter($dbTableAuthAdapter);
             	 	return $authService;
             	 },
             	 'AdminEditFilterMan' => function($sm) {
             	 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             	 	$basicFilter = $sm->get('UsersEditFilter');
             	 	return new AdminEditFilterMan($dbAdapter, $basicFilter);
             	 },
             	 'AdminEditFilterWoman' => function($sm) {
             	 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             	 	$basicFilter = $sm->get('UsersEditFilter');
             	 	return new AdminEditFilterWoman($dbAdapter, $basicFilter);
             	 },
             	 'AdminEditForm' => function ($sm) {
             	 	$form = new \User\Form\AdminEditForm();
             	 	return $form;
             	 },
             	 'UsersEditFilter' => function($sm) {
             	 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             	 	$basic = $sm->get('UserInfoFilterBasic');
             	 	return new UsersEditFilter($dbAdapter, $basic);
             	 },
             	 'UserEditFilterMan' => function($sm) {
             	 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             	 	$basicFilter = $sm->get('AdminEditFilterMan');
             	 	return new UserEditFilterMan($dbAdapter, $basicFilter);
             	 },
             	 'UserEditFilterWoman' => function($sm) {
             	 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
             	 	$basicFilter = $sm->get('AdminEditFilterWoman');
             	 	return new UserEditFilterWoman($dbAdapter, $basicFilter);
             	 },
                 'UsersTableGateway' => function ($sm) {
                 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                 	$resultSetPrototype = new ResultSet();
                 	$resultSetPrototype->setArrayObjectPrototype(new Users());
                 	return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                 },
   
                 'UsersTable' =>  function($sm) {
                 	$tableGateway = $sm->get('UsersTableGateway');
                 	$config = $sm->get('config');
                 	$table = new UsersTable($tableGateway, $config);
                 	return $table;
                 },
                 'AdminSortForm' => function ($sm) {
                 	$form = new \User\Form\AdminSortForm();
                 	return $form;
                 },
				 'LoginForm' => function ($sm) {
    				 $form = new \User\Form\LoginForm();
    				 return $form;
    			 },
				 'LoginFilter' => function ($sm) {
    				 return new \User\Form\LoginFilter();
    			 },
    			 'BodyParamsFilter' => function ($sm) {
    			 	return new \User\Form\BodyParamsFilter();
    			 },
				 'RegisterForm' => function ($sm) {
    				 $form = new \User\Form\RegisterForm($sm);
    				 return $form;
    			 },
    			  'UserEditForm' => function ($sm) {
    			 	$form = new \User\Form\UserEditForm();
    			 	return $form;
    			 },
    			 'PhotoUploadForm' => function ($sm) {
    			 	$form = new \User\Form\PhotoUploadForm();
    			 	return $form;
    			 },
    			 'UserInfoFilterBasic' => function($sm) {
    			 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 	return new UserInfoFilterBasic($dbAdapter);
    			 },
    			 'RegisterFilterBasic' => function($sm) {
    			 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 	$basicFilter = $sm->get('UserInfoFilterBasic');
    			 	return new RegisterFilterBasic($dbAdapter, $basicFilter);
    			 },
    			 'RegisterFilterMan' => function($sm) {
    			 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 	$rf = $sm->get('RegisterFilterBasic');
    			 	return new RegisterFilterMan($dbAdapter, $rf);
    			 },
    			 'RegisterFilterWoman' => function($sm) {
    			 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 	$rf = $sm->get('RegisterFilterBasic');
    			 	return new RegisterFilterWoman($dbAdapter, $rf);
    			 },
    			 'UpdateFilter' => function($sm) {
    			 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 	return new RegisterFilter($dbAdapter, 'update');
    			 },
    			 'PhotoUploadFilter' => function($sm) {
    			 	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 	return new PhotoUploadFilter($dbAdapter, 'update');
    			 },
             )
         );
     }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
