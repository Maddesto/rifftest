<?php 
namespace User\Entity;

use Zend\Db\Adapter\Adapter;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 /**
 *
 * @ORM\Entity
 * @ORM\Table(name="body_params")
 * @property int $user_id
 * @property string $breast
 * @property string $waist
 * @property string $hips
 */
class BodyParams implements InputFilterAwareInterface 
{	
	protected $inputFilter;
	 
	 /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="NONE")
     */
	protected $user_id;
	/**
     * @ORM\Column(type="string")
     */
	protected $breast;
	/**
     * @ORM\Column(type="string")
     */
	protected $waist;
	/**
     * @ORM\Column(type="string")
     */
	protected $hips;
	/**
     * @ORM\Column(type="string")
     */
	
	/**
     *
     * @ORM\OneToOne(targetEntity="Users", inversedBy="authoredComments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
	protected $user;
	
	public function __get($property) 
    {
        return $this->$property;
    }
	/**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
    }
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	/**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
	
	/**
     * Populate from an array.
     *
     * @param array $data
     */
     public function exchangeArray($data = array()){
         $this->breast = (!empty($data['breast'])) ? $data['breast'] : null;
         $this->waist  = (!empty($data['waist'])) ? $data['waist'] : null;
		 $this->hips  = (!empty($data['hips'])) ? $data['hips'] : null;		 
     }
	 
	  public function getInputFilter(){
		if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
 
            /*$inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));*/
			$inputFilter->add(array(
            'name'       => 'breast',
            'required'   => true,
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
            ),
        ));
		 $inputFilter->add(array(
            'name'       => 'waist',
            'required'   => true,
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
            ),
        ));
		
		$inputFilter->add(array(
            'name'       => 'hips',
            'required'   => true,
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
            ),
        ));
			$this->inputFilter = $inputFilter;	
		}
		return $this->inputFilter;
	}
}