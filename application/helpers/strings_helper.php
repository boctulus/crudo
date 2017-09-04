<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
Carga: $this->load->helper('string_helper.php');
*/

// corta string a deterinada longitud sin corar palabras
function tokenTruncate($string, $your_desired_width) {
  $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
  $parts_count = count($parts);

  $length = 0;
  $last_part = 0;
  for (; $last_part < $parts_count; ++$last_part) {
    $length += strlen($parts[$last_part]);
    if ($length > $your_desired_width) { break; }
  }

  return implode(array_slice($parts, 0, $last_part));
}


if ( ! function_exists('one_p'))
{
  // devuelve el primer parrafo
  function one_p($string) { 
    $article = explode("\n", $string); 
	$article = explode('<br/>', $article[0]); 
    $parrafo = $article[0]; 
    return( $parrafo );  
  }   
}
    
	function right($valor,$cant) {
      $longitud = strlen($valor);
      $part = $longitud - $cant;
      $fitxa = substr($valor, $part);
      return($fitxa);
    }
	
	/*      
	  
	  Retorna palabra singularizada (version simple, sin diccionario)
	  
	  @param      string
      @return     string	  
	 		  
	*/
    function no_ending_s($str){ 	     
	
	  if ( right($str,1) =='s' ){	    
	    // patron : orden(es) -> orden
	    if ( right($str,2) =='es' ){
	      $str = substr($str,0,-2);
		}else{  
		  // patron : usuario(s) -> usuario
	      $str = substr($str,0,-1);
		}  
	  }
	  return $str;
	}
	
	/*
		Singulariza un término en base a un diccionario
	
	*/
	function singularize($word,$path_dict = PATH_CONTROLLERS.'make/config/singulars.ini')
	{
		static $_singulars = null;
				
		if ($_singulars==null)
          $_singulars = process_ini($path_dict);		  	
	  
			  
		$word = strtolower($word);
				 			 
        $found = false;		 
		foreach ($_singulars as $row)
		{			
		    if ($word == $row['fields'][0]){
			  $name= ucfirst($row['fields'][1]);
			  $found = true;			 			  
			  break;
			}
		  
	       if (($pos= @stripos($word, $row['fields'][0],strlen($word) -strlen($row['fields'][0])))){			   		 
			 $tmp  = ucfirst($word);
			 $name =  substr_replace($tmp,$row['fields'][1],$pos);
			 $found = true;		
			 break;
	      }
		}  
        
        if (!$found){		  
		  $name =  no_ending_s(ucfirst($word)); 		  
		}
		
		
		//debug ([$word,$found*1]);
		
		return trim($name);		
	}	
	
	/*     
	  @param      string
      @return     string	  
	*/
	function noUnderscore($str){ 
	  $new=''; $capitalize = False;
	  for ($p=0;$p<strlen($str);$p++){
	    if ($str[$p]=='_'){
		  $capitalize = True;
		}else{
		  $char= $str[$p];
		  if ($capitalize){
		    $char=strtoupper($char); 
			$capitalize = False;
		  }
	      $new .=$char;
		}  
	  }
	  return $new;
	}
	
	/**
	  @param      string
      @return     string	  
	* 
    * si el parametro es un string lo encierra entre '' 
	*/
	function escape_string($x){
	  if (is_string($x)){
	    return "'$x'";
	  }
	    return $x;	  
	}

	// remueve tambien <script>...<script>
	function striptags($str){
	  $str = strip_tags($str);
	  return preg_replace('/<style\b[^>]*>(.*)<\/style>/is', '', $str);
	}
	
	if (!function_exists('lcfirst')) {
      function lcfirst($string) {
        return substr_replace($string, strtolower(substr($string, 0, 1)), 0, 1);
      }
	}
	
	// elimina retornos de carro que no son validos en strings en JS
	function bye_enter ($str){
      return trim( preg_replace( '/\s+/', ' ', $str ) );   
    }

		
    if ( ! function_exists('debug'))
	{	
      function debug ($a,$exit=false)
      {
        echo '<pre>';
        print_r($a);
        echo '</pre>';
		if ($exit) exit;
      }
    }

    function debug_bool($a)
    {
      echo '<pre>';
      var_dump($a);
      echo '</pre>';
	}

	