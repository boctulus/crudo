<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
Carga: $this->load->helper('currency_helper.php');
*/

define ('DINERO',1);
define ('PORCENTAJE',2);

// devuelve  $type
function discount ($cant,$cant_discount,$type){
  switch ($type){
    case DINERO: return ($cant_discount);
	case PORCENTAJE: return (0.01 * $cant * $cant_discount);
    default: return 0;	
	//throw new Exception ("TIPO DE DESCUENTO DESCONOCIDO");       
  }
}

// formatea como dinero o porcentaje
function cash_format ($number,$type=null,$separator=','){

 if (is_string($number)){
   $number = (float) $number;
 } 

 if  ($type==DINERO){
   return '$ '.number_format($number, 0, '', $separator);
 }  
 
 if  (($type==PORCENTAJE) OR (strlen((string) $number)<=2)){
   return $number.'%';
 }

 return '$ '.number_format($number, 0, '', $separator); // .' pesos'
}