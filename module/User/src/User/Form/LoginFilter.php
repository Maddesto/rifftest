<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class LoginFilter implements InputFilterAwareInterface
{
	protected $inputFilter;
	
	public function __construct() {
	
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter) {
		throw new \Exception("Not used");
	}
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$inputFilter->add(array(
					'name'       => 'email',
					'required'   => true,
					'validators' => array(
							array(
								'name'    => 'EmailAddress',
								'options' => array(
									'domain' => true,
								),
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
					'required'   => true,
					'validators' => array(
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
			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
}
