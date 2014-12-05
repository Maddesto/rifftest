<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use User\Model\Gender;
use User\Model\MaritalStatus;
use User\Model\BodyType;

class RegisterFilterBasic implements InputFilterAwareInterface 
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
	public function __construct(Adapter $dbAdapter, UserInfoFilterBasic $registerFilter ) {
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
					'name'       => 'username',
					//'required'   => true,
					'filters'    => array(
							array(
									'name'    => 'StripTags',
							),
					),
					'validators' => array(
						array(
							'name' => 'Db\NoRecordExists',
							'options' => array(
								'table' => 'users',
								'field' => 'username',
								'adapter' => $this->getDbAdapter(),
								'messages' => array(
									\Zend\Validator\Db\AbstractDb::ERROR_RECORD_FOUND => 'Sorry guy, a user with this username already exists !'
								)
							)
						),
						array(
							'name' => 'NotEmpty',
							'options' => array(
								'messages' => array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter username'
								)
							)
						)
					)
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
			$inputFilter->add(array(
				'name'       => 'password',
				//'required'   => true,
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
						'name' => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter password'
							)
						)
					)
				)
			));
			$inputFilter->add(array(
					'name'       => 'confirm_password',
					//'required'   => true,
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
									'token' => 'password',
									'messages' => array( 
										\Zend\Validator\Identical::NOT_SAME => "You have entered different passwords",
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
