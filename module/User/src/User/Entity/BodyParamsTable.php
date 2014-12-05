<?php
namespace User\Model;

 use Zend\Db\TableGateway\TableGateway;

 class BodyParamsTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getBodyParams($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('user_id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveBodyParams(BodyParams $body)
     {
         $data = array(
             'user_id' => $body->user_id,
             'password' => $body->breast,
			 'email' => $body->waist,
			 'first_name' => $body->hips
         );

         $id = (int) $body->user_id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getUser($id)) {
                 $this->tableGateway->update($data, array('user_id' => $id));
             } else {
                 throw new \Exception('Album id does not exist');
             }
         }
     }

     public function deleteBodyParams($id)
     {
         $this->tableGateway->delete(array('user_id' => (int) $id));
     }
 }