<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Model */
class Crudo_M extends MY_Model { 
	
    public function __construct()
	{
	  parent::__construct(); 			
    }
	
   /**
   *  verifica si la tabla en la DB, exite
   */
   function tableExists($name){
     $tables = $this->listTables();		
	 $db = 'Tables_in_'.$db['default']['database']; 
				
     $found=false;
	 foreach ($tables as $t){		  
       if ($t->{$db}==$name){
         $found=true;	 
	     break;
	   }
     }	  
	 return $found;
   }	 
  
   function listTables(){
     // $sql = "SHOW TABLES FROM $dbname"
     // $dbname = $db['default']['database'];	 	 
	 $query  = $this->db->query("SHOW TABLES");
	 $result = $query->result(); // array
	 
     if (empty($result)){
       throw new exception('Fallo en SHOW TABLES');    
     }  
	 
	 return $result;
   } 
   
   function showTable($table){
     $query  = $this->db->query("SHOW CREATE TABLE $table");
	 $result = $query->result(); // array   
     return $result;
   }
   
   function listFields($table){     
     $query  = $this->db->query("SHOW COLUMNS FROM $table");
	 $result = $query->result(); // array
	 
     if (empty($result)){
       throw new exception('Fallo en SHOW COLUMNS');    
     }  
	 
	 // retorno tipo "resource" ... mysql_num_rows($result), $row = mysql_fetch_assoc($result), etc..
	 	 
	 return $result; // array de objetos stdclass con info de 'query resource' 
	 
	 // Resource Types: There are two resource types used in the MySQL module. The first one is the link identifier for a database connection, the second a resource which holds the result of a query.	 
   }
  
  
   function oneResult($array){	
	 if (count($array)==0){	  
	    //throw new exception ("SE ESPERABA UN REGISTRO Y HAY CERO (0)");		
		return null;
	 }else{
	    if (count($array)>1){	  
	      throw new exception ("SE ESPERABA UN REGISTRO Y MAS DE UNO (>1)");		
		}  
	  }	  
	  return true;
   }
  
 
	
	function getInfoFromFields($tabla){  
	  $f_ay   = array();	  
	  return $this->listFields($tabla); // array de objetos	  	  
    } 	
		
	 	
} # end class