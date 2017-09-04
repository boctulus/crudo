<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

	 function __construct()
    {
        parent::__construct();
		 			
        //sesión nativa de php
        session_start();
		
		$this->load->helper('server_helper.php');
	    
		// ...
    }
	
	/*
	function _output($content,$template='tpl.php')
    {		
        // Load the base template with output content available as $content
        $data['content'] = &$content;
        $this->load->view($template, $data);
    }
	*/

}
//
////  end class MY_Controller