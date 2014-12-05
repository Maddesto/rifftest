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

class UsersEditFilter implements InputFilterAwareInterface 
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
		$this->user = $user;
	}
	
	public function getUser(){
		return $this->user;
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
	public function __construct(Adapter $dbAdapter, UserInfoFilterBasic $registerFilter) {
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
					'name'     => 'id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
			));
			
			$inputFilter->add(array(
				'name'       => 'email',
				//'required'   => true,
				'validators' => array(
					array(
						'name'    => 'EmailAddress',
							'options' => array(
							'domain' => true,
						)
					),
					array(
						'name' => 'Db\NoRecordExists',
						'options' => array(
							'table' => 'users',
							'field' => 'email',
							'adapter' => $this->getDbAdapter(),
							'messages' => array(
								\Zend\Validator\Db\AbstractDb::ERROR_RECORD_FOUND => 'A profile with this email address already exists.'
							),
							'exclude' => array(
								'field' => 'id',
								'value' => $this->getUser()->id
							)
						)
					),
					array(
						'name' => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter email',
								\Zend\Validator\NotEmpty::INVALID => 'Please Check Your Email'
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
