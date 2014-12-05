<?php
namespace User\Form;

use Zend\Form\Form;
use User\Model\Gender;
use User\Model\BodyType;
use User\Model\MaritalStatus;

class RegisterForm extends Form
{
    public function __construct($serviceManager)
    {
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
        	'name'     => 'id',
            'required' => true,
            'filters'  => array(
             	array('name' => 'Int'),
                 ),
        ));       
        $this->add(array(
        		'name' => 'photo',
        		'type'  => 'text',
        		'options' => array(
        				'label' => 'Photo',
        		),
        ));
        $this->add(array(
           'type' => 'Radio',
            'name' => 'gender',
            'options' => array(
             	'label' => 'Gender',
                'value_options' => Gender::returnArray()
             ),
        	'attributes' => array(
        		'value' => Gender::MALE,
        		'class' => 'gender'
        	)
        ));

        
        $this->add(array(
            'name' => 'email',
            'type'  => 'email',           
            'options' => array(
                'label' => 'Email',
            ),
        )); 
        
		$this->add(array(
            'name' => 'password',
            'type'  => 'password',                               
            'options' => array(
                'label' => 'Password',
            ),
        )); 

        $this->add(array(
            'name' => 'confirm_password',
            'type'  => 'password',
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));
        
        $this->add(array(
        	'name' => 'username',
        	'type'  => 'text',
        	'options' => array(
        		'label' => 'Username',
        	),
        ));

		$this->add(array(
            'name' => 'first_name',
            'type'  => 'text',
            'options' => array(
                'label' => 'First name',
            ),
        ));
		
		$this->add(array(
            'name' => 'surname',
            'type'  => 'text',                               
			'options' => array(
                'label' => 'Second name',
            ),
        ));
		
		$this->add(array(
            'name' => 'country',
            'type'  => 'text',                                 
            'options' => array(
                'label' => 'Country',
            ),
        ));
		
		$this->add(array(
            'name' => 'city',
            'type'  => 'text',                                 
            'options' => array(
                'label' => 'City',
            ),
        ));
		
		$this->add(array(
            'name' => 'birthday',
            'type'  => 'Date',
			'attributes' => array(
				'max' => '2000-01-01',
				'min' => '1940-01-01',
				'step' => '1',
			),
            'options' => array(
                'label' => 'Birthday',
            ),
        ));
		
		$this->add(array(
            'name' => 'status',
            'type'  => 'Select',                                
            'options' => array(
                'label' => 'Marital status',
            	'value_options' => MaritalStatus::returnArray()
            ),
        ));
		$this->add(array(
            'name' => 'body_type',
            'type'  => 'Select',
            'options' => array(
                'label' => 'Body type',
            	'value_options' => BodyType::returnArray()
            ),
        ));
		
		$this->add(array(
            'name' => 'breast',
            'type'  => 'text',
            'options' => array(
                'label' => 'Breast',
            ),
        ));
		
		$this->add(array(
            'name' => 'waist',
            'type'  => 'text',                               
			'options' => array(
                'label' => 'Waist',
            ),
        ));
		
		$this->add(array(
            'name' => 'hips',
            'type'  => 'text',                                 
            'options' => array(
                'label' => 'Hips',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
        	'type'  => 'submit',
            'attributes' => array(
				'value' => 'Register',
            	'class' => 'btn btn-success'
            ),      		
        )); 
    }
}
