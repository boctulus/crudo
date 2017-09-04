<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
Carga: $this->load->helper('sql_helper.php');
*/

// devuelve algo como:  "nombre_color = 'violeta'"
// admite que los valores sean representados por un array
function get_statement ($field,$val,$op = '=',$comilla= "'"){
  
  if (is_array($val))
  {
    $out = array();
	
    foreach ($val as $v){
	  $out[] = $field.' '.$op.' '.$comilla.$v.$comilla;
	}
	// devuelve array de statements que pueden ser usados por get_OP()
    return $out;
  }
  return $field.' '.$op.' '.$comilla.$val.$comilla;  	
}


/* 
   @in: elementos del array
   @out: string, elementos contatenados con funcion operador
*/
function get_Op (array $cond,$op) {
  
   $out = null; 
   
   $cant = count ($cond);   
   for ($i=0;$i<$cant-1;$i++){
     $out = $cond[$i]." $op ".$out;
   }
   $out .= $cond[$cant-1];

   return '( '.$out.' )';
}

function get_OR (array $cond) {
  return get_op ($cond,'OR');   
}

function get_AND (array $cond) {
  return get_op ($cond,'AND');   
}


function debug_sql($exit = false){
  $ci_obj = get_instance();
  $ci_obj->output->enable_profiler(TRUE);
  echo '<pre>';
  print_r($ci_obj->db->last_query());
  echo '</pre>';
  
  if ($exit) exit;  
}	