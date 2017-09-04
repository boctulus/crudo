<?php
/*
	Make configuration
*/

#
#	Controllers
#	
#

#	El output podria ser JSON o HTML
#	(de momento solo puede ser JSON)
#

$output  = 'JSON';


#	Roles
#

$roles = ['','admin'];

#	Bloqueo la escritura / modificacion de algunos controllers
#


$lock_ctrls  = ['Titles'];


#	Actions
#	De no existir el controlador o el método correspondiente get___ list___ new___ delete___ edit___ será creado
#
#   delete = 1 es soft delete
#   delete = 2 es hard delete 

$controllers = [];
$controllers['Titles'] = ['get'=>1,'list'=>1,'new'=>1,'delete'=>1,'edit'=>1];
$controllers['Test'] = []; 













