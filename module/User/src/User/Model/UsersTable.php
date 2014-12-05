<?php
namespace User\Model;

use Zend\Text\Table\Row;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use \Zend\File\Transfer\Adapter\Http as FileAdapter;
use Zend\Mail;
use User\Model\Users;

class UsersTable
{
    protected $tableGateway;
    protected $config;

    public function __construct(TableGateway $tableGateway, $config)
    {
        $this->tableGateway = $tableGateway;
		$this->config = $config;
    }
    public function getTableGateway(){
    	return $this->tableGateway;
    }

    public function saveUser(Users $user)
    {
        
    	$data = $this->prepareData($user);
        $id = (int)$user->id;
        if ($id == 0) {
            if($this->tableGateway->insert($data)){
            	$this->sendMailAfterRegistration($data);
            	$this->setPhoto($this->getLastInsertId());
            	return $this->getLastInsertId();
            }
        } else {
            if ($this->getUser($id)) {
            	if (empty($data['password'])) {
            		unset($data['password']);
            	}
            	unset($data['role']);
            	unset($data['username']);
            	if (empty($data['photo'])) {
            		unset($data['photo']);
            	}
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User ID does not exist');
            }
        }
    }
    
    /**
     * Get all users
     * @return ResultSet
     */
    public function fetchAll()
    {
    	$resultSet = $this->tableGateway->select();
    	return $resultSet;
    }
    
    /**
     * Get all users per gender
     * @return ResultSet
     */
    public function search($gender)
    {
    	$resultSet = $this->tableGateway->select(array('gender'=>$gender, 'role'=>Users::USERROLE));
    	return $resultSet;
    }
    
    /**
     * Get User account by UserId
     * @param string $id
     * @throws \Exception
     * @return Row
     */    
    public function getUser($id)
    {
    	$id  = (int) $id;
    	$rowset = $this->tableGateway->select(array('id' => $id));
    	$row = $rowset->current();
    	if (!$row) {
    		throw new \Exception("Could not find row $id");
    	}
    	return $row;
    }
    
    /**
     * Get User account by Email
     * @param string $userEmail
     * @throws \Exception
     * @return Row
     */
    public function getUserByUsername($username)
    {
    	$rowset = $this->tableGateway->select(array('username' => $username));
    	$row = $rowset->current();
    	if (!$row) {
    		throw new \Exception("Could not find row $username");
    	}
    	return $row;
    }
    
    /**
     * Delete User account by UserId
     * @param string $id
     */
    public function deleteUser($id)
    {
    	$this->tableGateway->delete(array('id' => $id));
    	$this->deleteUserPhoto($id);
    }
    
    public function getLastInsertId(){
    	return $this->tableGateway->lastInsertValue;
    }
	
	public function getConfig(){
		return $this->config;
	}
    public function sendMailAfterRegistration($user_info){
    	$mail = new Mail\Message();
    	$mail->setBody('Dear '.$user_info['first_name'].' '.$user_info['surname'].'. You registered on our site. Thank you!')
    	->setFrom('rifftest@example.com', 'Rifftest')
    	->addTo($user_info['email'], $user_info['first_name'].' '.$user_info['surname'])
    	->setSubject('Registration');
    	$transport = new Mail\Transport\Sendmail();
    	$transport->send($mail);
    }
    
	public function deleteUserPhoto($id)
    {	
    	$config  = $this->getConfig();
    	$user = $this->getUser($id);
    	$uploadPath = $config['photos_location'].'/public';
    	unlink($uploadPath . $user->photo);

    	
    }
    public function setPhoto($user_id, $photo = null){
    	if(!user_id){
    		throw new \Exception("Id is empty");
    	}
    	if(!empty($photo)){
    		$this->changePhoto($user_id, $photo);
    	}else{
    		$this->createDefaultPhoto($user_id);
    	}
    	
    }
	
    public function createDefaultPhoto($user_id){
    	$config  = $this->getConfig();
    	$uploadPath = $config['photos_location'].'/public'.$config['photos_public_location'].'/'.$user_id;
    	$oldFilePath = $config['photos_location'].'/public'.$config['photos_public_location'].'/'.$config['default_filename'];
    	$publicFilePath = $config['photos_public_location'].'/'.$user_id.'/'.$config['default_filename'];
    	$newFilePath = $uploadPath.'/'.$config['default_filename'];
    	if(!mkdir($uploadPath,0,true)){
    		throw new \Exception('Cant make file folder');
    	}
    	
    	if (!copy($oldFilePath, $newFilePath)) {
    		throw new \Exception('Cant copy default image file');
    	}
    	$data = array();
    	$data['photo'] = $publicFilePath;
    	return $this->tableGateway->update($data, array('id' => $user_id));
    
    }
    
    public function prepareData($user){
    	$data = array(
    		'gender' => $user->gender,
    		'password' => $user->password,
    		'email'  => $user->email,
    		'first_name'  => $user->first_name,
    		'surname'  => $user->surname,
    		'username' => $user->username,
    		'country' => $user->country,
    		'city' => $user->city,
    		'birthday' => $user->birthday,
    		'status' => $user->status,
    		'photo'  => $user->photo,
    		'role' => $user->role
    	);
    	if($user->gender == Gender::MALE){
    		$data['body_type'] = $user->body_type;
    	}
	    if($user->gender == Gender::FEMALE){
	    	$data['breast']  = $user->breast;
	    	$data['waist']  = $user->waist;
	    	$data['hips']  = $user->hips;
    	}
    	return $data; 
    }
    
    public function changePhoto($user_id, $uploadFile){
    	$config  = $this->getConfig();
    	$publicFilePath = $config['photos_public_location'].'/'.$user_id.'/'. $uploadFile['name'];
    	$uploadPath = $config['photos_location'].'/public'.$config['photos_public_location'].'/'.$user_id;
    	$this->deleteUserPhoto($user_id);
    	$adapter = new FileAdapter();
    	mkdir($uploadPath,0,true);
    	$adapter->setDestination($uploadPath);
    	if ($adapter->receive($uploadFile['name'])) {
    		$data = array();
    		$data['photo'] = $publicFilePath; 
    		return $this->tableGateway->update($data, array('id' => $user_id));
    	}
		return false;
    }   
}
