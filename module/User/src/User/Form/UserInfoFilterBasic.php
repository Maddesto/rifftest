<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use User\Model\Gender;
use User\Model\MaritalStatus;
use User\Model\BodyType;

class UserInfoFilterBasic implements InputFilterAwareInterface 
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
	 * @param \Zend\InputFilter\InputFilterInterface $inputFilter
	 * @throws \Exception
	 */
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception("Not used");
	}
	
	/**
	 * @param \Zend\Db\Adapter $dbAdapter
	 */
	public function __construct(Adapter $dbAdapter) {
		$this->dbAdapter = $dbAdapter;
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
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'       => 'gender',
				'required'   => true,
				'validators' => array(
					array(
						'name' => 'InArray',
						'options' => array(
							'haystack' => Gender::returnValues(),
							'messages' => array(
								'notInArray' => 'Please select your gender'
							)
						)
					)
				)
			));
			 
			$inputFilter->add(array(
					'name'       => 'first_name',
					//'required'   => true,
					'filters'    => array(
						array(
							'name'    => 'StripTags',
						),
					),
					'validators' => array(
						array(
							'name'    => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min'      => 2,
								'max'      => 50,
							)
						),
						array(
							'name' => 'NotEmpty',
							'options' => array(
								'messages' => array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'You don\'t enter the First Name'
								)
							)
						)
					)
			));
			$inputFilter->add(array(
					'name'       => 'surname',
					//'required'   => true,
					'filters'    => array(
						array(
							'name'    => 'StripTags',
						)
					),
					'validators' => array(
						array(
							'name'    => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min'      => 2,
								'max'      => 50,
								)
						),
						array(
							'name' => 'NotEmpty',
							'options' => array(
								'messages' => array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'You don\'t enter Last Name'
								)
							)
						)
					)
			));
			$inputFilter->add(array(
					'name'       => 'country',
					//'required'   => true,
					'filters'    => array(
							array(
									'name'    => 'StripTags',
							)
					),
					'validators' => array(
						array(
							'name'    => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min'      => 2,
								'max'      => 50,
								)
						),
						array(
							'name' => 'NotEmpty',
							'options' => array(
								'messages' => array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'You don\'t enter Country'
								)
							)
						)
					)
			));
			$inputFilter->add(array(
				'name'       => 'city',
				//'required'   => true,
				'filters'    => array(
					array(
						'name'    => 'StripTags',
					)
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 2,
							'max'      => 50,
						)
					),
					array(
						'name' => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'You don\'t enter City'
							)
						)
					)
					
				)
			));
			$inputFilter->add(array(
					'name'       => 'birthday',
					//'required'   => true,
					'validators' => array(
						array(
							'name'    => 'Date',
							'options' => array(
								'format' => 'Y-m-d'
							)
						),
						array(
							'name' => 'NotEmpty',
							'options' => array(
								'messages' => array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'Enter the birth date'
								)
							)
						)
					)
			));
			$inputFilter->add(array(
				'name'       => 'status',
				//'required'   => true,
				'validators' => array(
					array(
						'name' => 'InArray',
						'options' => array(
							'haystack' => MaritalStatus::returnValues(),
							'messages' => array(
								'notInArray' => 'Choose the marital status'
							)
						)
					),
					array(
						'name' => 'NotEmpty',
						'options' => array(
							'messages' => array(
								\Zend\Validator\NotEmpty::IS_EMPTY => 'Choose the marital status'
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
