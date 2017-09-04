<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
Carga: $this->load->helper('Array_helper.php');
*/

// sort() no funciona cuando el array es asociativo
function numeric_sort($algo,$reverse = false){

  if ($reverse){
    for ($i=0;$i<count($algo);$i++){ 
     for($j=0;$j<count($algo);$j++){ 	   
          if ($algo[$i]< $algo[$j]){ 
                  $temp = $algo[$i]; 
                  $algo[$i]=$algo[$j]; 
                  $algo[$j]=$temp; 
           } 
     } 
    }    
  }else{  
    for ($i=0;$i<count($algo);$i++){ 
     for($j=0;$j<count($algo);$j++){ 	   
          if ($algo[$i]> $algo[$j]){ 
                  $temp = $algo[$i]; 
                  $algo[$i]=$algo[$j]; 
                  $algo[$j]=$temp; 
           } 
     } 
    }    
  } 
  return $algo;
}

function getProperty($obj,$property)
{
  if (isset($obj->{$property}))	
  {
    return $obj->{$property};
  }else{
    return null;
  }  
}

function first ($array){
  if (isset($array[0])){  
    return $array[0];
  }else{
    return null;
  }  
}

/* 
   Devuelve la propiedad del primer elemento de un arreglo
   
   Uso: 
   getFirst($this->colores_m->colorGet(null,array('id_color'=>$registro->id_color)),'nombre_color')
   
*/   
function getFirst($array,$property=null)
{
  if ($property==null)
  {
    return first($array);
  }	

  if ( (is_array($array)) and (count($array)>0) )
  {
    if (isset($array[0]->{$property}))	
	{
      return $array[0]->{$property};
    }else{
	  if (isset($array->{$property}))
	  {
	    return $array->{$property};
	  }
	}
  }
  return null;  
}

function countRepeat(array $array)
{    
    $output = array();
    
    foreach($array as $key => $value)
    {
        if( array_key_exists($value, $output) )
        {
            ++$output[$value];
        }
        else
        {
            $output[$value] = 1;
        }
    }
    
    return $output;
}
	
	
//(basic locator by someone else - name unknown)
//strnposr() - Find the position of nth needle in haystack.
function strnposr($haystack, $needle, $occurrence, $pos = 0) {
    return ($occurrence<2)?strpos($haystack, $needle, $pos):strnposr($haystack,$needle,$occurrence-1,strpos($haystack, $needle, $pos) + 1);
}

//gjh42
//replace every nth occurrence of $needle with $repl, starting from any position
function str_replace_int($needle, $repl, $haystack, $interval, $first=1, $pos=0) {
  if ($pos >= strlen($haystack) or substr_count($haystack, $needle, $pos) < $first) return $haystack;
  $firstpos = strnposr($haystack, $needle, $first, $pos);
  $nl = strlen($needle);
  $qty = floor(substr_count($haystack, $needle, $firstpos + 1)/$interval);
  do { //in reverse order
    $nextpos = strnposr($haystack, $needle, ($qty * $interval) + 1, $firstpos); 
    $qty--;
    $haystack = substr_replace($haystack, $repl, $nextpos, $nl);
  } while ($nextpos > $firstpos);
  return $haystack;
}
  //$needle = string to find
  //$repl = string to replace needle
  //$haystack = string to do replacing in
  //$interval = number of needles in loop
  //$first=1 = first occurrence of needle to replace (defaults to first) 
  //$pos=0 = position in haystack string to start from (defaults to first) 	