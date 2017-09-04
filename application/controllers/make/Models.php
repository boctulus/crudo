<?php

/**                                       	
* Crudo v0.11 - Rapid Application Development 
* Bozzolo Pablo (2011)
*
* adapted to CodeIgniter
*/  

class Models extends MY_Controller {
	
    private $filename;
    private $tb_name;	
	private $_singulars=null;
	
	# CRUDO_DIR ... es el folder dentro de modelos si no existe la constante PATH_CRUD_MODELS

	# ej: CI_Model, MY_Model
	private static $_mextends   = 'MY_Model'; 
	# subfijo del nombre de archivo: _m u otro dando lugar a xxxx_m.php, etc
	private static $_cextends   = 'MY_Controller';
	private static $_subfix     = '_B'; 
	# sobre-escribo modelos ?
	private static $_overwrite = TRUE; 
	
	# valores campos:
    private static $_AUTO    = 'auto_increment';
	private static $_PRIMARY = 'PRI';
	private static $_NULL    = 'YES';
	
		
    public function __construct(){ //ok
	  parent::__construct();	
      
	  if (!defined('PATH_CRUD_MODELS')){
	    if (!defined('PATH_MODELS')){
		  throw new Exception ("Defina las constantes PATH_CRUD_MODELS y/o PATH_MODELS");
		}
	    define('PATH_CRUD_MODELS',PATH_MODELS.self::CRUDO_DIR);
	  }
	    
	  $this->load->helper(array('file','var','strings','html'));      
      $this->load->library('view');
	  #$this->load->library('linklist');
      $this->load->model('make/crudo_m','',TRUE);	
      $this->data['content']='';	  
	}
	

	private function noUnderscore($str){
	  return noUnderscore($str);
	}
	
	/* genero arbol con listas enlazadas */
	public function test(){	  
	
	    p($this->crudo_m->showTable('actions'));
	
	    $totalNodes = 10;        
        $theList = new LinkList();
    
        for($i=1; $i <= $totalNodes; $i++)
        {
            $theList->insertLast($i);
        }
		
		//echo $theList->totalNodes();
		//$theList->reverseList();		
		$theList->rewind();	     
		
		echo $n = $theList->current();		
		while (!$theList->isEnd()) {	
		 p($n = $theList->next());		 
		}
		
	}
	
	public function index()
	{	    			    
	    $this->view->_append("Generando modelos base [...]<p/>\n\n"); 
 	
	    $overwrite = self::$_overwrite;
	    $tables = $this->crudo_m->listTables();		
		
		$db = 'Tables_in_'.db_name;  // creo metodo de acceso          
				
		foreach ($tables as $t){		  
		  $this->filename = PATH_CRUD_MODELS.$t->{$db}.strtolower(self::$_subfix).'.php';
		 		  
		  if ($overwrite)
		  {		    
		    $this->generateModel($t->{$db});
            $this->view->_append($this->filename);	
		  }
		  else{
		  
		    if (!file_exists($this->filename)) {		   			  			 
		        $this->generateModel($t->{$db});
                $this->view->_append($this->filename);
			}else{
		      $this->view->_append('Skipping existing model: '.$this->filename."\n");
            }			
		
		  }	
		  
		}
		
		
		$this->view->show('tpl_base.php');	
	}
	
	
	private function getCrudoPath()
	{
	  if (defined('PATH_CRUD_MODELS')){
	    $path = 'PATH_CRUD_MODELS'.".'";
	  }else{  
	    $path = 'PATH_MODELS'.'/'.CRUDO_DIR;
	  }
	  return $path;
	}
	
	private function getModelFile($name,$path)
	{
	$fcname = ucfirst($name);  
	
    return "<?php  
  
require_once $path{$name}_b.php';
  
/* Model extended from byCRUDO */  
class {$fcname}_M extends {$fcname}".self::$_subfix." {  
  
   public function __construct(){  
     parent::__construct(); 	
   }  
   
} // end class      
"; 
    }

    private function getControllerFile($name,$path)
    {
	$fcname = ucfirst($name);  
	 
    return "<?php  
 
/* Controller */  
class {$fcname} extends ".self::$_cextends." {   
  
   public function __construct(){  
     parent::__construct(); 
	 //\$this->load->model('{$name}_m','',TRUE);	 
   }  
   
   public function index(){
     echo \"Into $fcname Index()\";
   }
   
   
} // end class   
";   
    }
	 
	private function writeIncludeFile($tb_name){	
	
	  $file            = PATH_MODELS.$tb_name.'_m.php';			  	  
	  $include         = "require_once PATH_CRUD_MODELS";	
	  
	  # verifico si existe el archivo _m.php
      if (file_exists($file))
	  {
	    if(!is_writable($file)) 
		{
          $this->view->_append("El archivo {$file} NO se puede escribir");
		}		
		
	    $file_content    = file_get_contents ($file);	
		
	    if (strpos($file_content,$include)===false)
		{	 
		   // deberia usar preg_replace() para poder limitar el numero de reemplazos de los tags <?php a 1 solo (el primero)
		   $br  = "\n";	      
           $subfix =  strtolower (self::$_subfix);
		   
           if ( defined(HMVC) AND (HMVC == true) ){
		     // verifico si existe el modulo como tal
             if (dir_exists(PATH_MODULES.$tb_name)){
               $inc = "<?php{$br}require_once PATH_MODULES.'{$tb_name}/models/{$tb_name}{$subfix}.php';"; 	    
			 }else{
			   $this->view->_append("No existe el modulo para cololar el modelo {$tb_name} pero Ud. dice usar el plugin HMVC (constants.php)");
             }			 
	       }else{
		      $inc = "<?php{$br}".
	  "{$br}require_once PATH_CRUD_MODELS.'{$tb_name}{$subfix}.php';";
      	      $data = str_replace ('<?php', $inc, $file_content );
		   }	  
		   
		   $f = fopen ($file,'w+');		
	       $e = fwrite($f,$data); 				      						
	       if (!$e){
	        $this->view->_append("No pude escribir ".$file);
	       }		
	       fclose($f);	
		} 
	  }else
	  {
	    // si el archivo NO existe... lo creo
		$path = $this->getCrudoPath(); 
        $data = $this->getModelFile($tb_name,$path);
		
		$f = fopen ($file,'w+');		
	    $e = fwrite($f,$data); 				      						
	    if (!$e){
	      $this->view->_append("No pude escribir".$file);
	    }		
	    fclose($f);	
        
	  }	 
	}  
	
	private function generateModel($tb_name){	   
	
	    $this->load->helper('var');   
		 
		# verifico si se puede escribir o no el archivo 
        if (file_exists($this->filename)){
		  if(!is_writable($this->filename)) {
            $this->view->_append("El archivo {$this->filename} NO se puede escribir");
			return;
		  }	
        }
		    
	    $this->writeIncludeFile($tb_name);
		 
		$fn_name =  singularize($tb_name); 		  		
							
		/* Archivo para debugging
		$f2 = @fopen (PATH_MODELS.self::CRUDO_DIR."salida.txt",'a');		
		$out = $fn_name."   \n";		
		$e = fwrite($f2,$out); 	
		fclose($f2);
		*/
		
		$fn_name    = rtrim(noUnderscore($fn_name));				
		$fn_name_lo = lcfirst($fn_name);	
		  
		$objects = $this->crudo_m->getInfoFromFields($tb_name);				
		
		/*
		if ($tb_name=='actions'){
		  p($objects);
	    }		
		*/
		
		$set = array(); #todos los campos
		$set_primarias=array(); # PRI
		$set_primarias_auto=array(); #PRI + AUTO (son no-nulas)
		$set_primarias_noauto = array(); # PRI - AUTO
		$set_noauto = array(); # TODOS - PRI_AUTO
		$set_nulas=array(); # campos que pueden ser NULL
		
		$set_primarias_var=array();
		$set_primarias_auto_var = array();
		$set_primarias_noauto_var = array();
		$set_noauto_var = array();
		$set_nulas_var = array();
		
		$vv_primarias=array();
		$vv_primarias_auto=array();
		$vv_primarias_noauto=array();
		$vv_noauto=array();
		//$vv_nulas=array();
		
		# inicializaciones
		$c_pri = 0;	
		
		foreach ($objects as $obj){		  
		  # ej: [Field],[Type],[Null],[Key] => PRI,[Default] =>,[Extra] => auto_increment
		  $set[] = $obj->Field;
		  if ($obj->Key==self::$_PRIMARY){
		    $set_primarias[] = $obj->Field;
			$set_primarias_var[]=  do_var($obj->Field); // $var
			$vv_primarias[] = cupla($obj->Field,no_ending_s($obj->Field)); // 15-abr
			$c_pri++;
			if ($obj->Extra==self::$_AUTO){
			  $set_primarias_auto[] = $obj->Field;
			  $set_primarias_auto_var[]= do_var($obj->Field); // $var
			  $vv_primarias_auto[] = cupla($obj->Field,no_ending_s($obj->Field)); 
			}else{
			  $set_primarias_noauto[] = $obj->Field;
			  $set_primarias_noauto_var[] = do_var($obj->Field); // $var
			  $vv_primarias_noauto[] = cupla($obj->Field,no_ending_s($obj->Field)); 
            }			
          } 
		  if (!$obj->Extra==self::$_AUTO){		    
		    $set_noauto[] = $obj->Field;
            $set_noauto_var[] = do_var($obj->Field).'=null'; // $var
			$vv_noauto[] = cupla($obj->Field,no_ending_s($obj->Field));
		  }
          if ($obj->Null==self::$_NULL){
		    $set_nulas[] = $obj->Field;   
			//$vv_nulas[] = cupla($obj->Field,no_ending_s($obj->Field));
		  } 		  
		}	# end foreach        
				
		# comma-separated vars
        $cm_primarias_auto_var = implode(',',$set_primarias_auto_var);
        $cm_primarias_noauto_var = implode(',',$set_primarias_noauto_var);
        $cm_noauto_var = implode(',',$set_noauto_var);		
		$cm_primarias_var = implode(',',$set_primarias_var);			
		# comma-separated values
		$cm_primarias_auto = implode(',',$set_primarias_auto);
        $cm_primarias_noauto = implode(',',$set_primarias_noauto);
        $cm_noauto = implode(',',$set_noauto);
		$cm_primarias = implode(',',$set_primarias);
        # comma-separated var => val				
		$vv_primarias_auto = implode(',',$vv_primarias_auto);
		$vv_primarias_noauto = implode(',',$vv_primarias_noauto);
		$vv_noauto = implode(',',$vv_noauto);
		$vv_primarias = implode(',',$vv_primarias);		
		  
		  
		$foreach_where = '
		  if (!empty($where))
		  {
		    if (is_array($where)){
		  
              foreach ($where as $key => $value){
	            $this->db->where($key,$value);
		       }					
			   
			}else{
			   $this->db->where($where);
            }			
			   
	      }';	
		  
		  
		$class  = 
		"<?php
		
/**                                       	
* Crudo v0.1 - model generator for CodeIgniter 
* @author: Bozzolo Pablo (2011)
*/       
class ".ucfirst($tb_name)."".self::$_subfix." extends ".self::$_mextends." { /// _M
    
	    const tb = '$tb_name';
	
        public function __construct()
	    {
		  parent::__construct();
        }
		
		"; 		
	
		$class .= "
		/*
        * (C)reate
		*/
	    public function create($cm_noauto_var)
		{
	      \$ay = array($vv_noauto); 
	  	  
	      \$this->db->insert(self::tb,\$ay);
	      return \$this->db->insert_id();
   	    }
		";
		
	  // si hay mas camposs que solo claves primarias			
      if ($c_pri<count($objects) )
	  {
		$class .= "
		// Simple Insert: array \$data -> $tb_name
		public function insert(\$data)
        {      
          \$this->db->insert(self::tb, \$data);
		  return \$this->db->insert_id();
        }
		";
	  }

		$class .= "
        /* 
		* D(elete) generico
		*/
	    public function delete(\$where)
		{		  
		  $foreach_where;					
	   
	      \$this->db->delete(self::tb);	   
	      return \$this->db->affected_rows();
	    }		
        ";
		
		
	  if ($c_pri<count($objects) )
	  {
		
        $class .= "					
		/* 		 
		*  R(read) : get() generico
		*/
	    public function get(\$select=null,\$where=null,\$perpage=null,\$start=0,\$order=null,\$joins=[],\$as=null,\$group_by=null)
		{ 			
		
		  // campos
		  if (!is_null(\$select)){		    
	        \$this->db->select(\$select);  
	      }	          
		  
		  $foreach_where;
		  
		  
          foreach (\$joins as \$key => \$value){
	        \$this->db->join(\$key,\$value);
		  }					   
	      
		  
		  if (!is_null(\$order)){
		    \$order = \"\$order[0] \$order[1]\";
			\$this->db->order_by(\$order);	        
	      }		
		  
		  if (\$perpage!=null){		  		    
	        \$this->db->limit(\$perpage,\$start);			
		  }
		  
		  if (!is_null(\$group_by)){
		    \$query = \$this->db->group_by(\$group_by);
		  }		    
		  
		  if (\$as == null){				 
	        \$query = \$this->db->get(self::tb);
		  }else{
		    \$query = \$this->db->get(self::tb.' AS '.\$as); 
          }	
		  		  
	  
	      return \$query->result();	  
	    }		
		";
		
		//debug ($set_primarias);
		
	    $class .= "					
		/* 		 
		*  R(read) : exists ?
		*/
	    public function exists(\$where,\$returnId=false)
		{	  
	      $foreach_where;
		  
		  \$query = \$this->db->get(self::tb);     		 	
          \$c_rows = (\$query->num_rows());
		  
		  if ((\$returnId) AND (\$c_rows==1)){
		    \$r = \$query->result();
			return \$r[0]->{$set_primarias[0]};
		  }
				
					
          return (\$c_rows>=1);	  	  
	    }
		";
		
		
		$class .= "		
		public function row(&\$o){
		  if (is_array(\$o) && !empty(\$o)){
		    \$o = \$o[0];
		  }          
		}		
		
		";		
	  
	    $class .= "
		/* 		 
		*  U(update) : generico
		*
		*  \$data  = array (clave,valor) de datos a actualizar
		*  \$where = array (clave,valor) de condiciones 
		*/
		public function update(\$data,\$where)
		{		   
		  $foreach_where;
		  
          \$this->db->update(self::tb, \$data); 
		  
		  return \$this->db->affected_rows();
	    }
		";  
	   
	 	
		$class .="\n} // end model class";	
				   
        /*
		  Con HMVC el path hacia los modelos en application/nombre_app/models
		  
		  mientras que...
		  
		  sin HMVC, la ruta es distinta:
		  nombre_app/application/models  (!)

          => esto puede generar errores de escritura de los archivos modelo!!!		  
		*/
				   
		if (!save_file($this->filename,$class,'w+')){
		  throw new Exception("No pude escribir ".$this->filename.' - VERIFIQUE si tiene correcta las constantes HMVC y PATH_CRUD_MODELS');
		}
		
				
	}
}
	
	/* crea archivo modelo */
	public function makeModel($name=null){
	  if (empty($name)){
	    return false;
	  }
	  $this->filename = PATH_CRUD_MODELS.$name.strtolower(self::$_subfix).'.php';
	  $this->generateModel($name);	  	  	  
	  return true;	  
	}
	
	/* crea scaffolding de modulo */	 
    public function makemodule($name=null){
      if($this->input->is_cli_request()){
			
		if (empty($name)){		
		  if (!empty($argv[1])){
		    $name = $argv[1];
		  }	
		}
		
		  if (empty($name)){		
		    fwrite(STDOUT, "Nombre del modulo?\n");
            $name = strtolower(trim(fgets(STDIN)));       
		  }       	    		      
	      
	  }	
	  
	  if (empty($name)){
	    throw new Exception ("No se ha definido nombre para modulo!");
	  }
	  
	  $fcname = ucfirst($name);         
  
      // creo estructura de directorios..
	  $d = PATH_MODULES.$name;	  	
	  if (dir_exists($d)){	    
	    $this->view->_append("$d ya existia");		
      }else{
	    if(!mkdir($d)){
          throw new Exception("Imposible crear directorio base $d");		
		}
	  }		 	
		
	      $d = PATH_MODULES.$name.'/controllers';
		  if (!dir_exists($d)){
            if (mkdir($d)){
			 
            }else{
		      throw new Exception ("Falla al crear directorio $d");
		    }
          }else{
		    $this->view->_append("$d ya existia");
          }		  
		
		  $d = PATH_MODULES.$name.'/models';
		  if (!dir_exists($d)){
            if (mkdir($d)){
			 
            }else{
		      throw new Exception ("Falla al crear directorio $d");
		    }
          }else{
		    $this->view->_append("$d ya existia");
          }	  
		
	      $d = PATH_MODULES.$name.'/config';
		  if (!dir_exists($d)){
            if (mkdir($d)){
			 
            }else{
		      throw new Exception ("Falla al crear directorio $d");
		    }
          }else{
		    $this->view->_append("$d ya existia");
          }	  
		
		
		  $d = PATH_MODULES.$name.'/libraries';
		  if (!dir_exists($d)){
            if (mkdir($d)){
			 
            }else{
		      throw new Exception ("Falla al crear directorio $d");
		    }
          }else{
		    $this->view->_append("$d ya existia");
          }	 
		  
		  $d = PATH_MODULES.$name.'/helpers';
		  if (!dir_exists($d)){
            if (mkdir($d)){
			 
            }else{
		      throw new Exception ("Falla al crear directorio $d");
		    }
          }else{
		    $this->view->_append("$d ya existia");
          }	 
		  
		  $d = PATH_MODULES.$name.'/views';
		  if (!dir_exists($d)){
            if (mkdir($d)){
			 
            }else{
		      throw new Exception ("Falla al crear directorio $d");
		    }
          }else{
		    $this->view->_append("$d ya existia");
          }	        
     
  
    $path  = $this->getCrudoPath(); 
    $model = $this->getModelFile($tb_name,$path);
    $controller = $this->getControllerFile($tb_name,$path);

    // Creo archivo de modelo
	if ($this->crudo_m->tableExists($name)){
	    $this->view->_append("<p/>Generando modelo '$name'");
	    $this->makeModel($name);	  	  
	    save_file(PATH_MODULES."$name/models/{$name}_m.php",$model,'w+');
	}

    save_file(PATH_MODULES."$name/controllers/$name.php",$controller,'w+');    
    $this->writeIncludeFile($name);
	$this->view->_append("Modulo ".anchor($name,$name)." creado!");
			
	$this->view->show();
    //readkey();
  }  
  
	
} // en class
