<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  // re-arregla convenientemente el nombre del archivo antes de hacer el THUMB
  // no se esta usando
  function sort_uploaded_filename($img_file,$id){	 
    $id_str = (string) $id;
    $img_file = str_replace ($id_str,$id_str.'_',$img_file);     
    $img_file = str_replace ('__','_',$img_file);
	$img_file = str_replace ('_.','.',$img_file);
  
    return $img_file;		 
  }  		 

  /**
  * Procesa un archivo de configuracion .INI y devuelve un arreglo
  *	@param   file
  *	@return  array of type,fields
  *
  * bastante complejos pueden ser los archivos INI, extrae asignaciones y demas
  * en $new_defs se le pueden pasar nuevas "reglas" de procesamiento como array assoc
  * o tambien se pueden redefinir las originales
  */
  
  function process_ini($file,$new_defs=null){
  
    /* 
     * ejemplo de definiciones: 
     
    if (!defined('DEF'))        $define = define ('DEF'  ,'def'  );
	if (!defined('RULE'))   define ('RULE' ,'rule' );
	if (!defined('REGEX'))  define ('REGEX','regex');
	if (!defined('LET'))    define ('LET'  ,'let'  );	# asignaciones
   */ 
	$rows = array();
  
    /* 
	 LET ejemplo:  $a = 567 o  a$ = 700
	 DEF: son definiciones por ejemplo sinonimos, ej: tree,arbol
	 RULE: es usado para determinar como cambia una palabra del singular al plural,
	       ej: (es),(s)	 
	 REGEX: aun no se definio
	 
	considero que las asignaciones (LET) se pueden hacer a numeros y strings y las variables pueden contener un $ delante o detras
	*/
	
	if (!defined('DEF'))
		define ('DEF',1);
	
	if (!defined('LET'))
		define ('LET',2);
	
	if (!defined('REGEX'))
		define ('REGEX',3);
	
	if (!defined('RULE'))
		define ('RULE',4);
	
    $exp = array(RULE =>"#^[(][a-z]{1,}[)],[(][a-z]{1,}[)]#i",
	DEF=>"#^[a-z]{1,},[a-z]{1,}#i",LET=>"#^[$]?[a-z]{1,}[$]?[ ]{0,}[=][ ]{0,}[0-9a-z]{1,}#i",REGEX=>"#__defini_expresion__#");
	
	
	// Nuevas reglas ?
	if ($new_defs!=NULL)
	{
	  foreach ($new_defs as $key => $def){
	    $exp[$key]=$def;
	  }
	}	
	
 
    $handle = @fopen($file, "r");
    if ($handle) {
	  
	  $c =0; $ignore_ln = false;  
      while (($buffer = fgets($handle, 4096)) !== false) {  

	    ### elimino comentarios tipo doble barra (//)
	    $pos = strpos (' '.$buffer,'//');   
	  
	    if ($pos === false) {		 
		}else{		  
		 $buffer = substr($buffer,0,$pos-1);
		}	
		
		### elimino comentarios tipo punto_y_coma (;)
		$len = strlen($buffer);
	    $pos = strpos (' '.$buffer,';');   
	  
	    if (!$pos) {		 
		}else{		  
		  $buffer = substr($buffer,0,$pos-1);
		}	
	  
	    ### comentarios tipo  /* ...  */
		$out = null;	 	    
		$len = strlen($buffer);
	    $pos = strpos (' '.$buffer,'/*');   
		$pos2= strpos ($buffer,'*/');  
	
	
  	    if (($pos !== false) and ($pos2 !== false))
		{
		  // comentarios /* */ en la misma linea (mono)
		  
		  $buffer = substr ($buffer,0,$pos-1).substr ($buffer,$pos2+2,$len-$pos2);	
          $ignore_ln = false;		  
		}		
		// OK.. los mono-linea ya desaparecieron
			

     	// Ahora voy por los MULTIlinea
		$len = strlen($buffer);
	    $pos = strpos (' '.$buffer,'/*');   
		$pos2 = strpos ($buffer,'*/'); 
		
		if ($pos === false) {	
          // busco cierre de commentarios realmente multi-linea 	
          if ($ignore_ln){		  
	        $pos2 = strpos ($buffer,'*/'); 
		    if ($pos2 === false){
		    }else{
		      $buffer = substr ($buffer,$pos2+2,$len-$pos2);
		      $ignore_ln = false;
		    }		
		  }	
		}else{
		  // busco cierre de commentarios multi-linea en la misma linea de apertura	          	 	  
		  $buffer = substr ($buffer,0,$pos-1);		  
	      $pos2 = strpos ($buffer,'*/');  	  
		  
		  if ($pos2 === false){		    
		    // es realmente multi-linea	                  			
			$out =  substr ($buffer,0,$pos-1);
			$ignore_ln = true;
		  }else{		    
		  }
		}	  			
		
		// busco fin de commentarios multi-linea
		if ($ignore_ln){	
		  $pos2 = strpos (' '.$buffer,'*/');   
	  		
		  if ($pos2 === false) {		 
		    }else{
		      // dejo solo lo posterior al fin del comentario multi-linea			  			  			  
		      $buffer = substr ($buffer,$pos2+1,$len-$pos2);
		      $ignore_ln = false;
		    }	  
		}	

        // descarto la linea presente	
        if ($out==NULL){		
		  if ($ignore_ln) continue;
		}else{
		  $buffer = $out;
        }		
		
			    
	    $lt = ltrim($buffer);
	    if ( (strlen(trim($buffer))>0) ){      	 	    
	      
	      $fields     = explode(',',$buffer);		 
		  
	      switch (count($fields)){
	        # definicion o regla
	        case 2:	
			  # definicion: plural, singular
              if (preg_match($exp[DEF],$buffer)){
			     $rows[$c]['fields']= $fields;
				 $rows[$c]['type']= DEF;						    
              }else{
			    # regla: (terminacion plural,terminacion singular)
			    if (preg_match($exp[RULE],$buffer)){
              	  $rows[$c]['fields']= $fields;
				  $rows[$c]['type']= RULE;    		  
				}else{  				  
				  # no es nada... podria generar excepcion, warning o ignorarlo
				}
              }			  
		    break;
		
		    # expresion regular
		    case 1:		
			  # deberia *comprobar* es verdaderamente una expr. regular 
			  if (preg_match($exp[REGEX],$buffer)){
		        $rows[$c]['fields']= $fields;
			    $rows[$c]['type']= REGEX;
			  }else{	
			    if (preg_match($exp[LET],$buffer)){			  
			      # aca procesaria las asignaciones
			      $fields=explode('=',$buffer);
			      $rows[$c]['fields']= $fields;
			      $rows[$c]['type']= LET;
				}
			  }	
		    break;
		
		    default:
			  // CSV: devuelvo array de columnas (campo1,campo2,...campoN)
			  $rows[$c]= $fields;
		    break;  
	    }		
		++$c;
	  }  // while   	  
	 
    }
	
      if (!feof($handle)) {
        throw new Exception("Error: unexpected fgets() fail\n");
      }
      fclose($handle);
    }
	return $rows;
  } // en fn

  
  function dir_exists($dir){
    return (file_exists($dir) && is_dir($dir));
  }	
  
  // ignora la creacion si ya existe el directorio evitando generar warning
  // no considero el CONTEXT de la version nativa
  function mkdir_ignore($path,$mode=0777,$recursive=true,$ignore_if_exists=true){  
    if (!dir_exists($path)){
	  mkdir ($path,$mode,$recursive);
	}else{
	  if (!$ignore_if_exists){
	    mkdir ($path,$mode,$recursive);
      }	  
	}
  }	
    
  function save_file($filename,$data,$mode='w+'){  
    $f = @fopen ($filename,$mode);    	
    $e = @fwrite($f,$data); 
    @fclose($f);	
    return $e;
  }
  
  
  function file_extension($filename)
  {
    $path_info = pathinfo($filename);
    return $path_info['extension'];
  }