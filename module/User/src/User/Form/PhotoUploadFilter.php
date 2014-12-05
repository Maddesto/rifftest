<?php
namespace User\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class PhotoUploadFilter implements InputFilterAwareInterface 
{
	protected $inputFilter;
	
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
	public function __construct() {
	}
	
	public function getInputFilter() {
		if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
 
            /*$inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));*/
            $fileInput = new \Zend\InputFilter\FileInput('image');
            $fileInput->setRequired(true);
            
            // You only need to define validators and filters
            // as if only one file was being uploaded. All files
            // will be run through the same validators and filters
            // automatically.
            $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png'))
            ->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));
			/*$inputFilter->add(array(
             'name' => 'image',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                            'options' => array(
                                'messages' => array(
                                    'isEmpty' => 'Please select an icon to upload.',
                                ),
                            ),
                        ),
                        array(
                            'name' => '\Zend\Validator\File\IsImage',
                            'options' => array(
                                'messages' => array(
                                    'fileIsImageFalseType' => 'Please select a valid icon image to upload.',
                                    'fileIsImageNotDetected' => 'The icon image is missing mime encoding, please verify you have saved the image with mime encoding.',
                                ),
                            ),
                        ),
                    ),
        ));*/

			$inputFilter->add($fileInput);
			$this->inputFilter = $inputFilter; 
		}
		return $this->inputFilter;
	}
}
