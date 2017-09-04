<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	Thumb maker for CodeIgniter
	
	Author: Pablo Bozzolo
*/

class Images
{
	private $CI;
	
	private $settings =	['image_library' =>'gd2',
						 'create_thumb'=>TRUE,
						 'maintain_ratio'=>FALSE
						];

						
	public function __construct(){		
		$this->CI = &get_instance();
		$this->CI->load->library(['image_lib']); 		
	}	
						
	// @param bool keep aspect ratio
	// si es TRUE, entonces la altura es $height y el ancho es <= $width
	public function setFixedRatio($ratio){
		$this->settings['maintain_ratio'] = $ratio; 
		return $this;
	}
	
	public function setWidth($width){
		$this->settings['width'] = $width;
		return $this;
	}
		
	public function setHeight($height){
		$this->settings['height']= $height;    
		return $this;
	}
	
		// @param string path (sin / al final)
	public function setLocation($path){
		$this->location = $path;
		return $this;
	}	
	
	/*
		Thumb de una o varias imagenes
		@param array de nombres de archivos a ser redimensionados ubicados en location
		@param integer
		@param integer
	*/		
	public function makeThumb(array $files,$width=NULL,$height=NULL)
	{   			
		if (!empty($width))  $this->settings['width']  = $width;
		if (!empty($height)) $this->settings['height'] = $height;
		
		if (empty($width) || empty($height))
			throw new RuntimeException("width and height are required!");
	
		$ori_path = $this->location;
		$des_path = $ori_path.'/'.$width.'x'.$height;	 
		
		mkdir_ignore($des_path);		
	
		foreach ($files as $file)
		{			
			$new_file = str_replace ('.','_thumb.',$file);
			
			$this->settings['source_image'] = $ori_path.'/'.$file;	  
			$this->settings['new_image'] = $des_path.'/'.$new_file;	
						
			$this->CI->image_lib->initialize($this->settings); // sino se mama
			$this->CI->image_lib->resize();
			$this->CI->image_lib->clear();	  				  	
		}
	}		
	
}	