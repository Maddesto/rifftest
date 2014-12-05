<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use User\Model\Gender;
use User\Model\MaritalStatus;
use User\Model\BodyType;
use User\Model\Users;

class UserEditFilterMan implements InputFilterAwareInterface 
{	
	protected $inputFilter;
	/**
	 * @var inputFilter
	 */
	
	/**
	 * @var Database Adapter
	 */
	protected $dbAdapter;
	
	/**
	 * @var Basic register filter
	 */
	protected $registerFilter;
	
	protected $user;
	
	public function setUser(Users $user){
		$this->registerFilter->setUser($user);
	}
	
	public function getUser(){
		return $this->registerFilter->getUser();
	}
	
	/**
	 * @param \Zend\InputFilter\InputFilterInterface $inputFilter
	 * @throws \Exception
	 */
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception("Not used");
	}
	
	/**
	 * @param \Zend\Db\Adapter $dbAdapter
	 */
	public function __construct(Adapter $dbAdapter, AdminEditFilterMan $registerFilter) {
		$this->dbAdapter = $dbAdapter;
		$this->registerFilter = $registerFilter;
	}
	/**
	 *
	 * @return Zend\Db\Adapter
	 */
	public function getDbAdapter() {
		return $this->dbAdapter;
	}
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$inputFilter = $this->registerFilter->getInputFilter();
			
			$inputFilter->add(array(
				'name'       => 'password',
				'required'   => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min' => 3,
								'max' => 32
							)
						)/*,
						array(
							'name' => 'NotEmpty',
							'options' => array(
								'messages' => array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter password'
								)
							)
					)*/
				)
			));
			$inputFilter->add(array(
				'name'       => 'confirm_password',
				'required'   => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
								'min' => 3,
								'max' => 32
							)
						),
						array(
							'name' => 'Identical',
							'options' => array(
								'token' => $this->getUser()->password,
								'strict' => FALSE,
								'messages' => array(
										\Zend\Validator\Identical::NOT_SAME => "Incorrect current password",
								)
							)
						)
				)
			));
			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
}
