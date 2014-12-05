<?php 
namespace User\Model;

 class BodyType{
	
     const THIN = 't';
     const MUSCULAR = 'm';
	 const THICK = 'th';

     public static function returnArray(){
         return array(
					self::THIN=>'Thin', 
					self::MUSCULAR =>'Muscular', 
					self::THICK=>'Thick'
				); 
     }
	 public static function returnValues(){
         return array(self::THIN, self::MUSCULAR, self::THICK); 
     }
 }