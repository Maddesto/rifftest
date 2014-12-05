<?php 
namespace User\Model;

 class Gender{
	
     const MALE = 'm';
     const FEMALE = 'f';
     const MALESTR = 'man';
     const FEMALESTR = 'woman';

     public static function returnArray(){
         return array(
         		self::MALE=>'Male', 
         		self::FEMALE =>'Female'); 
     }
     
	 public static function returnValues(){
         return array(self::MALE, self::FEMALE); 
     }
     
     public static function returnArrayForRote(){
     	return array(
     			Gender::MALESTR=>Gender::MALE,
     			Gender::FEMALESTR =>Gender::FEMALE);
     }
 }