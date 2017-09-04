<?php

class UploadMulti
{
	private $filenames  = [];
	private $settings	= [];
	private $nameAttr;
	private $location;
	
	
	// @param string atributo Name del o de los INPUT TYPE='file'
	public function setName($name){
		$this->nameAttr = $name;
		return $this;
	}	
	
	// @param string path (sin / al final)
	public function setLocation($path){
		$this->location = $path;
		return $this;
	}	
	
	public function getFileNames(){
		return $this->filenames;
	}
		
	public function doUpload()
	{		
		mkdir_ignore($this->location);			
				
		$i = 0;
		foreach ($_FILES[$this->nameAttr]["error"] as $key => $error)
		{			
			if ($error == UPLOAD_ERR_OK)
			{
				$tmp_name = $_FILES[$this->nameAttr]["tmp_name"][$key];
				$this->filenames[$i] = basename($_FILES[$this->nameAttr]["name"][$key]);
				move_uploaded_file($tmp_name, $this->location."/{$this->filenames[$i]}");
				$i++;				
			}
		}
    }	

	
	
}	