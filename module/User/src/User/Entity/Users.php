<?php 
namespace User\Entity;

use Zend\Db\Adapter\Adapter;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use User\Model\Gender;
use User\Model\MaritalStatus;
use User\Model\BodyType;

 /**
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $surname
 * @property string $first_name
 * @property string $username
 * @property string $surname
 * @property string $country
 * @property string $city
 * @property date $birthday
 * @property char $body_type
 * @property char $status
 * @property char $gender
 */
class Users implements InputFilterAwareInterface 
{	
	protected $inputFilter;
	 
	 /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
	protected $id;
	/**
     * @ORM\Column(type="string")
     */
	protected $email;
	/**
     * @ORM\Column(type="string")
     */
	protected $password;
	/**
     * @ORM\Column(type="string")
     */
	protected $surname;
	/**
     * @ORM\Column(type="string")
     */
	protected $first_name;
	/**
     * @ORM\Column(type="string")
     */
	protected $username;
	/**
	 * @ORM\Column(type="string")
	 */
	protected $country;
	/**
     * @ORM\Column(type="string")
     */
	protected $city;
	/**
     * @ORM\Column(type="date")
     */
	protected $birthday;
	/**
     * @ORM\Column(type="string", length=1, options={"fixed" = true})
     */
	protected $status;
	 /**
     * @ORM\Column(type="string", length=1, options={"fixed" = true})
     */
	protected $gender;
	/**
	 * @ORM\Column(type="string", length=1, options={"fixed" = true})
	 */
	protected $body_type;
	
	protected $entityManager;
	
	/**
	 * @ORM\OneToOne(targetEntity="BodyParams", cascade={"all"})
	 * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
	 **/
	private $body_params;
	
	/**
	 * @ORM\OneToOne(targetEntity="Photos", cascade={"all"})
	 * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
	 **/
	private $photos;
	
	public function __construct($entityManager){
		$this->entityManager = $entityManager;	
	}
	
	public function getBodyParams() {
		return $this->body_params;
	}
	
	public function setBodyParams(BodyParams $body_params) {
		$this->body_params = $body_params;
	}
	
	public function setFirstComment(Comment $c) {
		$this->firstComment = $c;
	}
	
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
         $this->id     = (!empty($data['id'])) ? $data['id'] : 0;
         $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
		 $this->password = (!empty($data['password'])) ? md5($data['password']) : null;
         $this->email  = (!empty($data['email'])) ? $data['email'] : null;
		 $this->first_name  = (!empty($data['first_name'])) ? $data['first_name'] : null;
		 $this->surname  = (!empty($data['surname'])) ? $data['surname'] : null;
		 $this->username = (!empty($data['username'])) ? $data['username'] : null;
		 $this->country  = (!empty($data['country'])) ? $data['country'] : null;
		 $this->city  = (!empty($data['city'])) ? $data['city'] : null;
		 $this->birthday  = (!empty($data['birthday'])) ? new \DateTime($data['birthday']) : null;
		 $this->body_type  = (!empty($data['body_type'])) ? $data['body_type'] : null;
		 $this->status  = (!empty($data['status'])) ? $data['status'] : null;		 
     }
	 
	  public function getInputFilter($serviceManager = null){
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
            'name'       => 'gender',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => Gender::returnValues(),
                            'messages' => array(
                                'notInArray' => 'Please select your position !'
                        ),
                    ),
                ),
            )
		));	
			$inputFilter->add(array(
				'name'       => 'email',
				'required'   => true,
				'validators' => array(
					array(
						'name'    => 'EmailAddress',
						'options' => array(
							'domain' => true,
							)
                        ),
	                    array(
	                        'name' => 'DoctrineModule\Validator\NoObjectExists',
	                        'options' => array(
	                            'object_repository' => $this->entityManager->getRepository('User\Entity\Users'),
	                            'fields' => 'email',
	                        	'messages' => array(
	                        			'objectFound' => 'Sorry guy, a user with this email already exists !'
	                        	),
	                        )
	                    )
					)
				)
			);
			
			$inputFilter->add(array(
				'name'       => 'username',
				'required'   => true,
				'filters'    => array(
                	array(
                    	'name'    => 'StripTags',
               		),
            	),
            	'validators' => array(
					array(
						'name' => 'DoctrineModule\Validator\NoObjectExists',
						'options' => array(
							'object_repository' => $this->entityManager->getRepository('User\Entity\Users'),
							'fields' => 'username',
							'messages' => array(
								'objectFound' => 'Sorry guy, a user with this username already exists !'
							),
						)
					)
				)
			));

			$inputFilter->add(array(
            'name'       => 'first_name',
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
				'name'       => 'surname',
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
            'name'       => 'country',
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
            'name'       => 'city',
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
            'name'       => 'birthday',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'Date',
                    'options' => array(
                        'format' => 'Y-d-m'
                    ),
                ),
            ),
        ));
			$inputFilter->add(array(
            'name'       => 'status',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => MaritalStatus::returnValues(),
                            'messages' => array(
                                'notInArray' => 'Please select your position !'
                        ),
                    ),
                ),
            ),
        ));
			$inputFilter->add(array(
            'name'       => 'body_type',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => BodyType::returnValues(),
                            'messages' => array(
                                'notInArray' => 'Please select your position !'
                        ),
                    ),
                ),
            ),
        ));
			$inputFilter->add(array(
            'name'       => 'password',
            'required'   => true,
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
                )
            )
        ));

        $inputFilter->add(array(
            'name'       => 'confirm_password',
            'required'   => true,
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
        			),
        		),
        	),
        ));
			$this->inputFilter = $inputFilter;	
		}
		return $this->inputFilter;
	}
	
	public function getSelect(){
		$qb = $this->entityManager->createQueryBuilder();
		$qb->select(array('u',  'bp', 'p'))
		->from('\User\Entity\Users', 'u')
		->leftJoin('\User\Entity\BodyParams', 'bp', 'WITH', 'u.id = bp.user_id')
		->leftJoin('\User\Entity\Photos', 'p', 'WITH', 'u.id = p.user_id');
		return $qb;
	}
	
	public function getUserByUsername($username){
		$qb = $this->getSelect();
		$qb->where('u.username = :username');
		$qb->setParameter('username', $username);
		$query = $qb->getQuery();
		return $query->getArrayResult();
	}
	public function search($params){
		$qb = $this->getSelect();
		if(!empty($params)){
			foreach($params as $key=>$value){
				$qb->where('u.'.$key. 'LIKE :'.$key);
				$qb->setParameter($key, $value);
			}
		}
		$query = $qb->getQuery();
		return $query->getArrayResult();
	}
}