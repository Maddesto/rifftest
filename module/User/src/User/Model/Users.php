<?php
namespace User\Model;

class Users
{
    public $id;
	public $email;
	public $password;
	public $surname;
	public $first_name;
	public $username;
	public $country;
	public $city;
	public $birthday;
	public $status;
	public $gender;
	public $body_type;
	
	public $role;
	public $photo;
	public $breast;
	public $waist;
	public $hips;
	
	const USERROLE = 'user';
	const ADMINROLE = 'admin';

    public function setPassword($clear_password)
    {
        $this->password = md5($clear_password);
    }

    public function exchangeArray($data = array()){
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
		$this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email  = (!empty($data['email'])) ? $data['email'] : null;
		$this->first_name  = (!empty($data['first_name'])) ? $data['first_name'] : null;
		$this->surname  = (!empty($data['surname'])) ? $data['surname'] : null;
		$this->username = (!empty($data['username'])) ? $data['username'] : null;
		$this->country  = (!empty($data['country'])) ? $data['country'] : null;
		$this->city  = (!empty($data['city'])) ? $data['city'] : null;
		$this->birthday  = (!empty($data['birthday'])) ? /*new \DateTime*/($data['birthday']) : null;
		$this->body_type  = (!empty($data['body_type'])) ? $data['body_type'] : null;
		$this->status  = (!empty($data['status'])) ? $data['status'] : null;
		$this->photo  = (!empty($data['photo'])) ? $data['photo'] : null;
		$this->breast  = (!empty($data['breast'])) ? $data['breast'] : null;
		$this->waist  = (!empty($data['waist'])) ? $data['waist'] : null;
		$this->hips  = (!empty($data['hips'])) ? $data['hips'] : null;
		$this->role  = (!empty($data['role'])) ? $data['role'] : Users::USERROLE;

    }
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}	
}
