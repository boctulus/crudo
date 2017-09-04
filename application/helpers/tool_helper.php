<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('colorize')){	  
	  function colorize($neddle,$neddle_colors=null,$default_color='#2C608F'){	  

        if (is_numeric($neddle)){
		  if ($neddle<=0){
		    return 'red';
		  }else{
		    return 'green';
		  }
		}else{	  
          if (array_key_exists($neddle,$neddle_colors)){
	        return $neddle_colors[$neddle];	
		  }else{		  
		    return $default_color;
		  }
		}  
	  }		  
}  


if ( ! function_exists('colorise')){	  
	  function colorise($status,$default_color='#2C608F'){	  	 
        // hacer con in_array()	  
	    switch ($status){
		  case 'Aprobado':
		    return 'green';
			exit;
		  case 'Activo':
		    return 'green;';
			exit;	
		  case 'Leido':
		    return 'green;';
			exit;
		  case 'Desaprobado':
            return 'red';
            exit;
		  case 'Rechazado':
            return 'red';
            exit;
		  case 'No leido':
            return 'red';
            exit;
          case 'Pendiente':
            return '#d8d84c'; 
            exit;
          default:
            return $default_color;		  
		}	
	  }		  
}  


/* Suma los campos de un array y los devuelve */
if ( ! function_exists('totalize')){	  
	  function totalize($arr,$key){
	  
	    $tot = 0;
	    foreach ($arr as $elem){
	      $tot += $elem[$key]; /* no verifico tipo! */
	    }
		return $tot;
	  }		  
}  

/* Agrego a todos los elementos del array */
if ( ! function_exists('add_property')){	  
	  function add_property(&$arr1,$key,$val){
	
	    foreach ($arr1 as $k1 => $arr2){	
	      $arr1[$k1][$key] = $val;          	  
	    }		
	  }		  
}  

if ( ! function_exists('field_number_check')){
  function field_number_check ($arr,$cant_campos_esperados,$cabeceras=null){
    if (count($arr)!=$cant_campos_esperados)
	{
	    if ($cabeceras!=null){
		  $subfix = ' en '.$cabeceras;
		}else{
		  $subfix='';
		}
	    throw new exception('[[[El numero de campos no es el esperado'.$subfix.']]]');
		return false;
	}else{
	    return true;
	}
  }	
}  

