<?php
namespace User\Form;

use Zend\Form\Form;

class AdminSortForm extends Form
{
    public function __construct()
    {
        parent::__construct('Sort');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
		
		$this->add(array(
            'name' => 'country',
            'type'  => 'Select',                                
            'options' => array(
                'label' => 'Country',
            	'value_options' => array(
					''=>'Please select',
					'asc' => 'Ascend',
					'desc' => 'Descend'
				)
            ),
        ));
		$this->add(array(
            'name' => 'city',
            'type'  => 'Select',
            'options' => array(
                'label' => 'City',
            	'value_options' => array(
					''=>'Please select',
					'asc' => 'Ascend',
					'desc' => 'Descend'
				)
            ),
        ));
    }
}
