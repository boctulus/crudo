<?php	

	/* devuelve TRUE si el form fue enviado */
	// requiere un elemento con NAME="submit" en el form
	function form_sent($method = 'POST'){	  	
	  return ($_SERVER['REQUEST_METHOD'] == $method);	  
	}  
	
	// chequea si un checkbox fue marcado
	function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }

	
	
	/* 
	  Si el formulario fue enviado devuelve el valor del campo tal como viene del FORM y sino el pre-determinado (ej: el que proviene de la base de datos) en $userdata
	
	  uso: input_value('fullname',$objeto_registro);	
	  
	  si el campo del objeto consulta no coincide con el NAME="" del <INPUT>, entonces debe especificarse como un campo aparte $obj_field
	  
	*/
	function value ($field,$obj_registro=null,$obj_field=null){
	  $CI = &get_instance();
	  
	  // si el tercer parametro es -1, estoy indicando que $obj_registro no es registro sino 
	  // una variable escalar
	  if ($obj_field == -1){
	    return (form_sent() ? $CI->input->post($field) : $obj_registro);
	  }	
	  
	  // Si $obj_registro no es objeto sino un array
	  if (is_array($obj_field)){
	    return (form_sent() ? $CI->input->post($field) : $obj_registro[$obj_field]);
	  }	
	    
	  
	  if ($obj_field==null){
	    $obj_field = $field;		
	  }
	  
	  if ($obj_registro==null){	  
	    return $CI->input->post($field);
	  }else{	  	  
	    return (form_sent() ? $CI->input->post($field) : $obj_registro->{$obj_field});
	  }	
	}
	
	/*
      Form data extractor and cleanner -> usar en controllers 	
	  devuelve array limpio para insersion / actualizacion en DB 
	*/
	function form_data($remove_fields=[]){
	  $CI = &get_instance();
	  $userdata = $CI->input->post();    
	  	  
      foreach ($remove_fields as $field)
		  unset($userdata[$field]);
		
	  return $userdata;
	}
	
	
	// Cierra form.. pero envia campo oculto para
	// detectar el form fue enviado
	// util se envian por POST datos de vuelta desde el controller
	function form_close($extra = '',$hidden_sent=true) { 
	
	  if ($hidden_sent){
	    $hidden_sent= form_hidden('sent', 'sent');
	  }else{
        $hidden_sent='';
      }	
	  return $hidden_sent.'</form>'.$extra;
	}   
