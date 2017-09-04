RAD for CodeIgniter

Objetivo: crear aplicaciones rápidamente mediante un generador de código MVC para el framework de PHP CodeIgniter 3.x


Cómo comenzar?


La idea es partir de la base de datos la cual pudo ser disenada de forma visual -mediante MySQL Workbench por ejemplo- para generar la los modelos y los controladores correspondientes. Tener la DB disenada es un pre-requisito.


Pasos:

1 - Se configura el acceso a la base de datos modificando el archivo de configuración database.php en application/config 

2 - Como se asume que las tablas tienen nombres en plurar y los IDs de las tablas en singular (convencion), en caso de 	que la app no logre encontrar la forma singular correctamente se pueden configurar las reglas y excepciones para plurarizar / singularizar los nombres editando el archivo singulars.ini en application/controllers/make/config

3 - Tambien en application/controllers/make/config, hay un archivo make_config.php donde se definen las distintas acciones (ver, listar, crear, modificar, borrar) que se deben generar para cada controlador asi como los distintos roles (como 'admin', 'proveedor', 'cliente', etc) que queramos definir para nuestra aplicacion. Por cada rol debe crearse manualmente una carpeta en application/controllers primeramente y dar permisos de escritura.

4 - Luego solo es ejecutar /make/controllers y/o make/models para generar los controladores y modelos correspondientes.


Es todo!  

Futuras versiones podrian generar las vistas correspondientes.



