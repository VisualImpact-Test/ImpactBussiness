<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
defined('RUTA_MOVIL_FOTOS')    OR define('RUTA_MOVIL_FOTOS','http://movil.visualimpact.com.pe/fotos/impactTrade_android/');
defined('GC_TRADICIONALES')    OR define('GC_TRADICIONALES',['Tradicional','TRADICIONAL','HFS']);
defined('GC_MODERNOS')         OR define('GC_MODERNOS',['Moderno','MODERNO','HSM']);
defined('GC_MAYORISTAS')       OR define('GC_MAYORISTAS',['WHLS','whls']);
defined('ID_TIPOUSUARIO_TI')   OR define('ID_TIPOUSUARIO_TI',4);
// defined('FOTOS_CONTROLADOR')   OR define('FOTOS_CONTROLADOR',base_url()+ 'ControlFoto/');
// defined('RUTA_BAT')            OR define('RUTA_BAT','C:\apache24\PHP\php7\php.exe -f "C:\apache24\htdocs\impactTrade\index.php" ');
defined('RUTA_BAT')            OR define('RUTA_BAT','C:\wamp64\bin\php\php7.1.33\php-win.exe -f "C:\wamp64\www\pruebas\w7impactTrade\index.php" ');

//WASABI
defined('RUTA_WASABI')         OR define('RUTA_WASABI','https://s3.us-central-1.wasabisys.com/impact.business/');

//Tablas
defined('TABLA_HISTORICO_ESTADO_COTIZACION')   OR define('TABLA_HISTORICO_ESTADO_COTIZACION','compras.cotizacionEstadoHistorico');


//Compras
defined('IGV')       OR define('IGV',0.18);
defined('LIMITE_COMPRAS') OR define('LIMITE_COMPRAS',1000);
defined('RUC_VISUAL') OR define('RUC_VISUAL','20467225155');

//Operaciones
defined('GAP') OR define('GAP',15);
defined('MONTOGAP') OR define('MONTOGAP',1500);


//Tipos de Item
defined('COD_ARTICULO')       OR define('COD_ARTICULO',['id' => 1, 'nombre' => 'ARTICULO']);
defined('COD_SERVICIO')       OR define('COD_SERVICIO',['id' => 2, 'nombre' => 'SERVICIO']);
defined('COD_COMPUTO')        OR define('COD_COMPUTO',['id' => 3, 'nombre' => 'COMPUTO']);
defined('COD_MOVIL')          OR define('COD_MOVIL',['id' => 4, 'nombre' => 'MOVIL']);
defined('COD_PERSONAL')       OR define('COD_PERSONAL',['id' => 5, 'nombre' => 'PERSONAL']);
defined('COD_EVENTO')         OR define('COD_EVENTO',['id' => 6, 'nombre' => 'EVENTO']);
defined('COD_DISTRIBUCION')   OR define('COD_DISTRIBUCION',['id' => 7, 'nombre' => 'DISTRIBUCION']);
defined('COD_TEXTILES')       OR define('COD_TEXTILES',['id' => 9, 'nombre' => 'TEXTILES']);
defined('COD_TARJETAS_VALES') OR define('COD_TARJETAS_VALES',['id' => 10, 'nombre' => 'TARJETAS_VALES']);
defined('COD_TRANSPORTE')     OR define('COD_TRANSPORTE',['id' => 12, 'nombre' => 'TRANSPORTE']);

//Wireframe

defined('RUTA_WIREFRAME') OR define('RUTA_WIREFRAME','../public/assets/images/wireframe/');
defined('IMG_WIREFRAME') OR define('IMG_WIREFRAME','../public/assets/images/wireframe/image.png');
defined('IMG_WIREFRAME2') OR define('IMG_WIREFRAME2','../public/assets/images/wireframe/clip.png');

//Tipo Archivo

defined('TIPO_ORDEN_COMPRA') OR define('TIPO_ORDEN_COMPRA',1);
defined('TIPO_IMAGEN') OR define('TIPO_IMAGEN',2);
defined('TIPO_PDF') OR define('TIPO_PDF',3);
defined('TIPO_LINK') OR define('TIPO_LINK',4);
defined('TIPO_OTROS') OR define('TIPO_OTROS',5);

//ESTADO COTIZACION

defined("ESTADO_REGISTRADO") OR define("ESTADO_REGISTRADO",1);
defined("ESTADO_ENVIADO_COMPRAS") OR define("ESTADO_ENVIADO_COMPRAS",2);
defined("ESTADO_CONFIRMADO_COMPRAS") OR define("ESTADO_CONFIRMADO_COMPRAS",3);
defined("ESTADO_ENVIADO_CLIENTE") OR define("ESTADO_ENVIADO_CLIENTE",4);
defined("ESTADO_COTIZACION_APROBADA") OR define("ESTADO_COTIZACION_APROBADA",5);
defined("ESTADO_OPER_GENERADO") OR define("ESTADO_OPER_GENERADO",6);
defined("ESTADO_OPER_ENVIADO") OR define("ESTADO_OPER_ENVIADO",7);
defined("ESTADO_OC_GENERADA") OR define("ESTADO_OC_GENERADA",8);
defined("ESTADO_OC_ENVIADA") OR define("ESTADO_OC_ENVIADA",9);
defined("ESTADO_OC_CONFIRMADA") OR define("ESTADO_OC_CONFIRMADA",10);
defined("ESTADO_FINALIZADA") OR define("ESTADO_FINALIZADA",11);

//ESTADO COTIZACION INTERNA
defined("INTERNA_ENVIADA") OR define("INTERNA_ENVIADO",1);
defined("INTERNA_PRECIO_RECIBIDO") OR define("INTERNA_PRECIO_RECIBIDO",2);
defined("INTERNA_CONFIRMADA") OR define("INTERNA_CONFIRMADA",3);
defined("INTERNA_FINALIZADA") OR define("INTERNA_FINALIZADA",4);

//ARCHIVOS PERMITIDOS
defined("ARCHIVOS_PERMITIDOS") OR define("ARCHIVOS_PERMITIDOS","image/*,.pdf,.xls,.xlsx,.ppt,.pptx");
defined("KB_MAXIMO_ARCHIVO") OR define("KB_MAXIMO_ARCHIVO",7168); //7MB
defined("MAX_ARCHIVOS") OR define("MAX_ARCHIVOS",10); 

//TIPO AUTORIZACION

defined("AUTH_CAMBIO_COSTO") OR define("AUTH_CAMBIO_COSTO",1);

//AUTORIZACION ESTADO
defined("AUTH_ESTADO_PENDIENTE") OR define("AUTH_ESTADO_PENDIENTE",1);
defined("AUTH_ESTADO_ACEPTADO") OR define("AUTH_ESTADO_ACEPTADO",2);
defined("AUTH_ESTADO_RECHAZADO") OR define("AUTH_ESTADO_RECHAZADO",3);

//TIPOS DE USUARIO 
defined("USER_ADMIN") OR define("USER_ADMIN",1);
defined("USER_COORDINADOR_OPERACIONES") OR define("USER_COORDINADOR_OPERACIONES",2);
defined("USER_COORDINADOR_COMPRAS") OR define("USER_COORDINADOR_COMPRAS",3);
defined("USER_GERENTE_OPERACIONES") OR define("USER_GERENTE_OPERACIONES",4);

//CORREOS 
defined("MAIL_DESARROLLO") OR define("MAIL_DESARROLLO",['eder.alata@visualimpact.com.pe']);
defined("MAIL_COORDINADORA_OPERACIONES") OR define("MAIL_COORDINADORA_OPERACIONES",['andrivette.tavara@visualimpact.com.pe','margarita.bailon@visualimpact.com.pe','tamar.roque@visualimpact.com.pe']);
defined("MAIL_COORDINADORA_COMPRAS") OR define("MAIL_COORDINADORA_COMPRAS",['anghy.vega@visualimpact.com.pe','diana.zuniga@visualimpact.com.pe']);
defined("MAIL_GERENCIA_OPERACIONES") OR define("MAIL_GERENCIA_OPERACIONES",['milenka.gargurevich@visualimpact.com.pe']);

//Acceso
defined("SECRET_KEY_GET") OR define("SECRET_KEY_GET",'CLAVESUPERSECRETA');
defined("DIAS_MAX_ACCESO") OR define("DIAS_MAX_ACCESO",30);
defined("URL_WASABI_ITEM_PROPUESTA") OR define("URL_WASABI_ITEM_PROPUESTA",'https://s3.us-central-1.wasabisys.com/impact.business/itemPropuesta/');

// Genero
defined('LIST_GENERO') OR define('LIST_GENERO',[['id' => 1, 'value' => 'Hombre'], ['id' => 2, 'value' => 'Mujer'], ['id' => 3, 'value' => 'Unisex']]);
