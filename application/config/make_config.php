<?php
/*
	Make configuration
*/

#
#	Controllers
#	
#

#	El output puede ser JSON o HTML
#	si se elije HTML se incluyen vistas de /views
#

$output  = 'JSON';


#	Roles
#

$roles = ['','admin'];

#	Bloqueo la escritura / modificacion de algunos controllers
#


$lock_ctrls  = [];


#	Actions
#	De no existir el controlador o el método correspondiente get___ list___ new___ delete___ edit___ será creado
#
#   delete = 1 es soft delete
#   delete = 2 es hard delete 

$controllers = [];

$controllers['Shares'] = ['get'=>1,'list'=>1,'new'=>0,'delete'=>0,'edit'=>0];















