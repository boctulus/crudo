<?php

/**                                       	
* Crudo v0.11 - Rapid Application Development 
* @author: Bozzolo Pablo (2011 - 2017)
*
*
*/  
class Controllers extends CI_Controller
{
   
	private $_base_controller = 'CI_Controller';

    private $folders = [];
	private $controllers = [];
		  
    public function __construct()
	{
	  parent::__construct();	          
		  
      $this->load->helper('tool');
	  $this->load->helper('file');
	  $this->load->helper('strings');
	  
	  $this->load->library('view');
	}
	
	public function index()
	{		
		include "config/make_config.php";
		
		$ctrls = [];
		foreach ($controllers as $folder_controller => $actions)
		{
			if (in_array($folder_controller,$lock_ctrls))
				continue;
			
			$tmp = explode('/',$folder_controller);
			if (isset($tmp[1])){
				$controller = $tmp[1];
				$folder = $tmp[0].DIRECTORY_SEPARATOR;				
			}else{
				$controller = $tmp[0];
				$folder = '';	
			}					
						
			$ctrls["$controller-$folder"] =  ['name'=>$controller, 'folder'=>$folder, 'actions'=>$actions];			
		}

				
		foreach ($ctrls as $ctrl_name => $ctrl)			
			$this->makeController($ctrl['name'], $ctrl['actions'], $ctrl['folder']);			
		    
			
		$this->view->show('tpl_base.php');	
	} 
	
		
	public function makeController($name,$actions=[],$folder)
	{	
		$base_controller = $this->_base_controller;
		$name_lo = lcfirst($name);		
		$name_singular = singularize($name);
		$name_singular_lo = lcfirst($name_singular);
		$index = ($name =='index') ? '' : '/index';
		$folder = str_replace('\\','/',$folder);
		
		$content =  "<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class $name extends $base_controller
{
    public function __construct()
    {
        parent::__construct();  
		\$this->load->model('{$name_lo}_m','',TRUE);		
		\$this->load->helper(['form', 'url', 'html','file', 'strings','array']);
		\$this->load->library('view');
    }

    
    public function index()
    {           
        \$this->list$name();
    }
	";
	
	$this->view->_append("<b>Genero controller :</b> $folder $name"); 		
			
	foreach ($actions as $action => $status)
	{		
		if ($status==0 || $action == 'index')
		  continue;			
		
		$action_fn = $action=='list' ? $action.$name : $action.$name_singular;
		$params = [	'new'=>'',
					'get'=>'$id',
					'list'=>'',
					'edit'=>'$id',
					'delete'=>'$id',
					'softdelete'=>'$id'					
				  ];
				
		$body['get'] = "
		\$where = ['id{$name_singular} ='=>\$id ];
		
		\$r = \$this->{$name_lo}_m->{$name_singular_lo}Get(NULL,\$where,NULL,NULL,NULL,[],NULL);
		echo json_encode(\$r);";
			
		$body['list'] = "
		\$where = [ ];
		
		\$r = \$this->{$name_lo}_m->{$name_singular_lo}Get(NULL,\$where,NULL,NULL,NULL,[],NULL);
		echo json_encode(\$r);";
		
		$body['new'] = "
		\$this->view->show_view('tpl.php','$folder"."new_{$name_lo}');
		";
	
		$body['process'] = "				
		if (!form_sent()){
			echo 'Error: No se recibio ningun dato';	
			exit;
		}	
				
		\$data = form_data();
		//\$data['Estado'] = 1;
		\$id_insert = \$this->{$name_lo}_m->insert{$name_singular}(\$data);		
		echo \$id_insert; 
		";
		
		$body['hardDelete'] = "	
		echo \$this->{$name_lo}_m->delete{$name_singular}(\$id);
		";
		
		$body['delete'] = "
		echo \$this->{$name_lo}_m->updateEstado(\$id,-1);
		";
		
		
		$body['edit'] = "		
		if (!form_sent()){
			echo 'Error: No se recibio ningun dato';	
			exit;
		}	
		
		\$data = form_data();
		\$result = \$this->{$name_lo}_m->update{$name_singular}(\$data,['id'=>\$id]);		
		echo \$result; 
		";				

		// elimino caracteres de control`
		foreach ($body as $key => $b)
			$body[$key] = trim($b,"\x00..\x1F");
						
		$new_action = "	
	public function $action_fn($params[$action])
	{          
        $body[$action]
	}
	";	

	if ($action=='new' && $status)
	{
		$new_action .= "
	public function process()
	{          
		{$body['process']}
	}
	";	
	}	
	
	if ($action=='delete' && $status==2)
	{
		$new_action .= "
	public function hardDelete{$name_singular}(\$id)
	{          
		{$body['hardDelete']}
	}
	";	
	}
	
		$content .= $new_action;		
		$this->view->_append("&nbsp;&nbsp;&nbsp;Creando accion '$action'");	  
				
	  }	  
	
	$content .= "
	
	
} // end class ";	
	
	
	$file = PATH_CONTROLLERS.$folder.$name.'.php';
	
	//if (!file_exists($file))
		save_file ($file,$content);
	
  } // 
  
  

} // en class
