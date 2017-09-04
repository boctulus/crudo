<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function dejar_palabras($frase)
{
    $ban = FALSE;
    
    $i = $cont = $ocont = 0;
    
    $nuevo = null;
    
    $noadmitidos = array(";", ",", "_", "-", " ", ".", "/", "{", "}", "[", "]", ":", "?", "�", "+");
    
    $noadmitidosPal =array("con", "contra", "para", "de", "a", "ante", "y", "o", "u", "e", "i", "end", "como", "la", "el");
    
    $temporal = str_replace($noadmitidos, ' ', $frase);
    
    $palabras = explode(' ', $temporal);
    
    foreach($palabras as $pal)
    {
        if($pal != '') {
            if (in_array($pal, $noadmitidosPal)) {
                $temporal = str_replace($noadmitidosPal, ' ', $frase);
            } else {
                $nuevo[] = $pal;
            }
        }       
    }
    
    return $nuevo;    
}

