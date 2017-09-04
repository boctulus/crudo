<?php
    // este helper deberia ser generado por CRUDO / MAKE

    /*
	  Devuelve ID de PRI KEY de cada tabla
  	  deberia ser generado por CRUDO
    */	
	function id_table($table)
	{
	  $ids = array ('productos'=>'id_producto',
	                'colecciones'=>'id_coleccion',
                    'usuarios'=>'id_usuario',
                    'promociones'=>'id_promocion',
					'ordenes'=>'id_orden',
					'imagenes_productos'=>'id_imagenes_productos'
                    /* etc. ... */					
	  );

	  ///echo '$table ';debug_v ($table,1);
	  
	  
      return $ids[$table];	  	
	}
	
	// podria ser MUY ineficiente usar asi.. pues cargo los modelos cada vez que los uso
	// usar la version LIBRARY de este helper!
	function getFromTable($table,$fields=null,$where=null,$perpage=null,$start=null,$order=null,$join=null){
	
	  $CI =  get_instance();
	  $CI->load->model($table.'_m');	 
	
	  switch ($table)
	  {
	    case 'productos': 				  
		  return $CI->productos_m->productoGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'colecciones': 		  
		  return $this->colecciones_m->coleccionGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'marcas': 		  
		  return $this->marcas_m->marcaGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  		
		case 'colores': 		  
		  return $this->colores_m->colorGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'promociones': 		  
		  return $this->promociones_m->promocionGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'usuarios': 		  
		  return $this->usuarios_m->usuarioGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'ordenes': 		  
		  return $this->ordenes_m->ordenGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
	
		case 'carritos': 				  
		  return $this->carritos_m->carritoGet($$fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'comentarios': 		  
		  return $this->comentarios_m->comentarioGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
			
		case 'favoritos': 		  
		  return $this->favoritos_m->favoritoGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		case 'imagenes_productos': 		  
		  return $this->favoritos_m->imagenesProductosGet($fields,$where,$perpage,$start,$order,$join,$as,$group_by); 		  
		
		
		/// ... SEGUIR...
	  
	    default:
		   throw New Exception ("$table no es una tabla definida aqui, definala!");
	  }
	
	}
	