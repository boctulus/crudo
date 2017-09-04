<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
Carga: $this->load->helper('system_helper.php');
*/

date_default_timezone_set('America/Bogota');

function is_local(){
  return ($_SERVER[ 'REMOTE_ADDR' ] == '127.0.0.1');
}

function my_base(){
  if ($_SERVER[ 'REMOTE_ADDR' ] == '127.0.0.1' ){
    return '';  
  }else{
    #return 'http://metaphysic.co/Tropical/';
  }  
}

function get_state_msg ($state,$modulo=null){
   
   switch ($state)
   {
       case -1: return "borrado/a";
       case  0: return "pausado/a";	   
	   case  1: return "activo/a";  
	   case  2: return "nuevo/a";
	   default : return 'indefinido';
   }  
}

function getOS(){
  return (string)(PHP_OS);
}

function isWindowsOS(){
  $SO = (string)(PHP_OS);  
  return (stripos($SO,'WIN')!==false); 
}

function slash_replace($path){
  
  if (isWindowsOS()){
    return str_replace('/','\\',$path);
  }else{
    return $path;
 }	
}

/* 
  Convierte path absoluto (en Windows) en una URL
*/
function absolute_to_url($abs_path , $htdocs_absolute_path = 'C:/xampp/htdocs/'){			              
  if (isWindowsOS()){		    
    return str_replace ($htdocs_absolute_path,'http://',$abs_path);
  }else{
    return $abs_path;
  }  
}

function current_file_path(){
   return dirname(__FILE__);   
}   

// devuelve algo como: /admin/productos/borrar/48
function current_uri(){
  return str_replace('/index.php/','/',$_SERVER['PHP_SELF']);
}  

function full_url($protocol='http') {  	  
  return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // Se devuelve la URL completa
}


function getlast_segment(){  
  $CI=&get_instance();
  return $CI->uri->segment(count($CI->uri->segment_array()));
}

// retorna mensaje almacenado en flashdata de campo $field
// requiere:  $this->load->library('session');
function get_flash_msg($field='message') {
    $CI=&get_instance();
    return $CI->session->flashdata($field);
} 


// coloca mensaje flashdata y re-direccion a $loc 
// requiere:  $this->load->library('session');
function flash_msg_redirect($message="", $loc=null, $field="message") {
    if ($loc == null){
	  $loc = current_uri();  
	}
	$loc = site_url($loc);
	
    $CI=&get_instance();
    $CI->session->set_flashdata($field, $message);    	
	redirect ($loc);
}

function put_flash_msg ($message="", $field="message") {	
    $CI=&get_instance();
    $CI->session->set_flashdata($field, $message);    	
  return;
}


/*
  Expresa el tiempo que hace de una ocurrencia en lenguaje coloquial
  @author: Pablo Bozzolo (2010)
  
  ej de uso:
  
  $lastloggin = time_ago (time()-$row['lastlogin']) ;
  
*/
function time_ago ($secs){

  $mins  = ceil(($secs/60));
  $horas = ceil(($mins/60));
  $dias  = ceil($horas/24);
  $meses = ceil($dias/30.5);

  $out = 'hace meses';

  if ($meses<12)
    $out = 'hace '.$meses.' meses'; 

  if ($horas<720)
    $out = 'hace '.$dias.' dias'; 

  if ($horas<96)
    $out = 'hace '.$horas.' hs.';  
    
  if ($mins <60)
    $out = 'hace '.$mins.' min.';  


  if ($mins==2) 
    $out = 'hace instantes';
  
  return $out;
}

// devuelve la fecha/hora en formato 'datetime' de sql
function datetime(){
  return date("Y-m-d h:i:s");
}  
  
// devuelve solo la fecha de un datetime
function extract_date ($datetime,$discard_year=false){
  
  list ($date,$time) = explode(' ',$datetime);
  
  if ($discard_year){
    $_date = explode('-',$date,2);
	$date = $_date[1];
  }
 
  return $date;
}  

// devuelve solo la hora de un datetime
function extract_time ($datetime,$discard_seconds=false){

  #if (strpos(trim($datetime),' ')){
  #  return $datetime;
  #}
  
  list ($date,$time) = explode(' ',$datetime);
  
  $time = null;
  if ($discard_seconds){
    $_time = explode('-',$time,2);
	if (isset($_time[1])){
	  $time = $_time[1];
	}  
  }
 
  return $time;
}  

function getMonthName($month){
   switch ((integer) $month){
     case 1: return 'Enero'; 
	 case 2: return 'Febrero';
	 case 3: return 'Marzo';
	 case 4: return 'Abril';
	 case 5: return 'Mayo';	 
	 case 6: return 'Junio';
	 case 7: return 'Julio';
	 case 8: return 'Agosto';
	 case 9: return 'Setiembre';
	 case 10: return 'Octubre';
	 case 11: return 'Noviembre';
	 case 12: return 'Diciembre';  
   }
   return null;
}

// en formato 2010-01-01
function getDaysFromToday($date)
{
     $now = time(); // or your date as well
     $date = strtotime($date);
     $datediff = $now - $date;
     return floor($datediff/(60*60*24));
}



function esBisiesto($year=NULL) {
    return checkdate(2, 29, ($year==NULL)? date('Y'):$year); // devolvemos true si es bisiesto
}
  
function getDaysFromMonth ($month,$year=null)
{
      switch ((integer) $month)
	  {
	        case 1: $end_day = 31; break;
			case 2: $end_day = 28;  break;
	        case 3: $end_day = 31; break; 
			case 4: $end_day = 30; break;
			case 5: $end_day = 31; break; 
			case 6: $end_day = 30; break; 
			case 7: $end_day = 31; break; 
			case 8: $end_day = 31; break; 
			case 9: $end_day = 30;  break;
			case 10: $end_day = 31;  break;
			case 11: $end_day = 30; break;
			case 12: $end_day = 31; break;
            default: echo 'Error!'; return false;			
	  }
	  if ( (((integer) $month) == 2) AND (!is_null($year)) )
	  {
	    // aplico correccion de biciestos
		if (esBisiesto($year)) {
	       $end_day++;
		}
	  }
	  
	  return $end_day;
} 
  


function horaLegible($timestamp,$ajuste_server=1){

$date=getDate($timestamp);

$hh = $date['hours']+$ajuste_server;
$mm = $date['minutes'];
$ss = $date['seconds'];

if ($hh>23){
  $hh -= 24;
}

if ($mm<10){
  $mm = '0'.$mm;
}

if ($ss<10){
  $ss = '0'.$ss;
}

return ($hh).":".$mm.":".$ss;
//18:39:27 ejemplo
}


function fechaLegible($timestamp){
  $date=getDate($timestamp);

  $dia=$date['mday'];
  $mes=$date['mon'];

  if (strlen($date['mday'])==1){
    $dia="0".$date['mday'];
  }

  if (strlen($date['mon'])==1){
   $mes="0".$date['mon'];
  }

  return $dia."-".$mes."-".$date['year']; //02-01-2009 ejemplo
}  
  
  
  
// para CLI .. espera a que se presione una tecla  
function readkey(){
  if (defined('STDIN')) return fgets(STDIN);
  return;
}  