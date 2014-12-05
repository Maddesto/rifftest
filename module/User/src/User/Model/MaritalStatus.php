<?php 
namespace User\Model;

 class MaritalStatus{
	
     const MARRIED = 'm';
     const NOTMARRIED = 'n';

     public static function returnArray(){
         return array(
         		self::MARRIED=>'Maried', 
         		self::NOTMARRIED =>'Not married'); 
     }
     
	 public static function returnValues(){
         return array(self::MARRIED, self::NOTMARRIED); 
     }
 }