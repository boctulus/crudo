<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
Carga: $this->load->helper('server_helper.php');
*/

function is_home(){
	return (file_exists("C:\\xampp\\htdocs\\router.php"));	
}	