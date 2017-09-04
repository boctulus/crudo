<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
Carga: $this->load->helper('var');
*/

function is_var($s){
    $pos = strpos ($s,'$');
	return ($s===0);
}
  
function do_var($nombre){
    return '$'.$nombre;
}

// pasa de $algo .. a.. 'algo'  
function do_key($var){
      
	// si es variable, le quito el peso y la dej como Key  
    if ($this->is_var($var)){
	  return str_replace('$','',$var);
	}
	return null;
}  
   
// crea cupla ( 'key'=>$var ) 
function cupla($var,$val)
{
    if (!is_var($var)){
	  $var = do_var($var);
	}  
    return "'$val'".'=>'.$var;
}