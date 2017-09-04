<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class View
{   
	
    public	$data = [];
	private $CI;

    public function __construct()
	{
	  if (!isset($this->data['content'])){
	    $this->data['content']=null;
	  }
	  
	  $this->CI = & get_instance(); 
	}

    public function _appendAtTop($c,$end="<br>\n"){	  //ok	 
	  $this->data['content'] = $c.$end.$this->data['content'];	
	}
	
  	public function _append($c,$end="<br>\n"){	  //ok	 
	  $this->data['content'] .= $c.$end;	
	}  
	
	/*
		Muestra el template y le inyecta datos (sin vistas)
	*/
	public function show($tpl,$data=null)
	{	
	  if ($data==null)
	      $data = $this->data;	  	
	  
	  $this->CI->load->view($tpl, $data);
    }
	
	/*
		Muestra el template con vistas parciales
		
		@param string template
		@param string vista parcial
		@param array de parametros a pasar a la vista
		@param array de parametros del header
		@param array de parametros del footer (content? js? ..)
		
		Quizas $view podria ser un array de vistas parciales 
	*/
	public function show_view($tpl,$view,$data=null,$header=null,$footer=null)
	{	
	  if ($data==null)
	      $data = $this->data;	  	
	  
	  	// cargo la vista y le inyecto parametros
		$v_output = $this->CI->load->view($view,$data,true);				
				
		// cargo el template y le inyecto la vista		
		$this->show($tpl,['header'=>
								[
									'title'=>(!empty($header['title']) ? $header['title'] : $this->CI->config->item('title')),
									'meta'=> (!empty($header['meta']) ? $header['meta'] : $this->CI->config->item('meta')),
									'css'=>	 (!empty($header['css']) ? $header['css'] : $this->CI->config->item('css')),
									'js'=>	 (!empty($header['js']) ? $header['js'] : $this->CI->config->item('js'))
								],			
								'content'=>$v_output,
								'footer'=>['js'=>[]]
								]); 
    }
	
  
} // end class


