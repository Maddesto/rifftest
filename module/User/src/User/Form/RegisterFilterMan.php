<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use User\Model\Gender;
use User\Model\MaritalStatus;
use User\Model\BodyType;

class RegisterFilterMan implements InputFilterAwareInterface 
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
	public $registerFilter;
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
	public function __construct(Adapter $dbAdapter, RegisterFilterBasic $registerFilter) {
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
					'name'       => 'body_type',
					//'required'   => true,
					'validators' => array(
						array(
							'name' => 'InArray',
							'options' => array(
								'haystack' => BodyType::returnValues(),
									'messages' => array(
										'notInArray' => 'Choose the body type'
									)
							)
						),
						array(
							'name' => 'NotEmpty',
								'options' => array(
									'messages' => array(
										\Zend\Validator\NotEmpty::IS_EMPTY => 'Choose the body type'
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
