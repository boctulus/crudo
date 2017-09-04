<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// funcionamiento: extender a MY_Model en vez de CI_Model

class MY_Model extends CI_Model{

   function __construct(){	
     parent::__construct();	      		     
   }
  

}//  end class MY_Controller