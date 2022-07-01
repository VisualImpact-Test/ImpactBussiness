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


//Compras
defined('IGV')       OR define('IGV',0.18);

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

//Wireframe

defined('RUTA_WIREFRAME') OR define('RUTA_WIREFRAME','../public/assets/images/wireframe/');
defined('IMG_WIREFRAME') OR define('IMG_WIREFRAME','../public/assets/images/wireframe/image.png');
defined('IMG_WIREFRAME2') OR define('IMG_WIREFRAME2','../public/assets/images/wireframe/clip.png');

//Tipo Archivo

defined('TIPO_ORDEN_COMPRA') OR define('TIPO_ORDEN_COMPRA',1);
defined('TIPO_IMAGEN') OR define('TIPO_IMAGEN',2);
defined('TIPO_PDF') OR define('TIPO_PDF',3);
defined('TIPO_LINK') OR define('TIPO_LINK',4);
