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
 * @ORM\Table(name="photos")
 * @property int $user_id
 * @property string $filename
 */
class Photos implements InputFilterAwareInterface 
{		 
	 /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="NONE")
     */
	protected $user_id;
	/**
     * @ORM\Column(type="string")
     */
	protected $filename;
	/**
     * @ORM\Column(type="string")
     */
	
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
     	$this->user_id  = (!empty($data['user_id'])) ? $data['user_id'] : 0;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;	 
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
			$this->inputFilter = $inputFilter;	
		}
		return $this->inputFilter;
	}
}