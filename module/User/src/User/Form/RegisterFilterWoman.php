<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use User\Model\Gender;
use User\Model\MaritalStatus;
use User\Model\BodyType;

class RegisterFilterWoman implements InputFilterAwareInterface 
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
			$inputFilter =  $this->registerFilter->getInputFilter();


		$inputFilter->add(array(
            'name'       => 'breast',
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
                    ),
                ),
            	array(
					'name' => 'NotEmpty',
					'options' => array(
						'messages' => array(
							\Zend\Validator\NotEmpty::IS_EMPTY => 'Not all body parameters are entered'
						)
					)
				)
            )
        ));
		 $inputFilter->add(array(
            'name'       => 'waist',
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
                    ),
                ),
            	array(
					'name' => 'NotEmpty',
					'options' => array(
						'messages' => array(
							\Zend\Validator\NotEmpty::IS_EMPTY => 'Not all body parameters are entered'
						)
					)
				)
            ),
        ));
		
		$inputFilter->add(array(
            'name'       => 'hips',
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
                    ),
                ),
            	array(
            		'name' => 'NotEmpty',
            		'options' => array(
            			'messages' => array(
            				\Zend\Validator\NotEmpty::IS_EMPTY => 'Not all body parameters are entered'
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
