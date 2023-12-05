<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')									or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')							or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')			or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')	or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')							or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')					or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')				or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')			or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')				or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')				or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')				or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')		or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')		or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD')	or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')			or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')			or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')			or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')			or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
defined('RUTA_MOVIL_FOTOS')		or define('RUTA_MOVIL_FOTOS', 'http://movil.visualimpact.com.pe/fotos/impactTrade_android/');
defined('GC_TRADICIONALES')		or define('GC_TRADICIONALES', ['Tradicional', 'TRADICIONAL', 'HFS']);
defined('GC_MODERNOS')				or define('GC_MODERNOS', ['Moderno', 'MODERNO', 'HSM']);
defined('GC_MAYORISTAS')			or define('GC_MAYORISTAS', ['WHLS', 'whls']);
defined('ID_TIPOUSUARIO_TI')		or define('ID_TIPOUSUARIO_TI', 4);
// defined('FOTOS_CONTROLADOR')		OR define('FOTOS_CONTROLADOR',base_url()+ 'ControlFoto/');
// defined('RUTA_BAT')					OR define('RUTA_BAT','C:\apache24\PHP\php7\php.exe -f "C:\apache24\htdocs\impactTrade\index.php" ');
defined('RUTA_BAT')					or define('RUTA_BAT', 'C:\wamp64\bin\php\php7.1.33\php-win.exe -f "C:\wamp64\www\pruebas\w7impactTrade\index.php" ');

//WASABI
defined('RUTA_WASABI') or define('RUTA_WASABI', 'https://s3.us-central-1.wasabisys.com/impact.business/');
// defined('RUTA_WASABI') or define('RUTA_WASABI', 'http://s3.us-central-1.wasabisys.com/impact.business/');
defined('FILES_WASABI') or define('FILES_WASABI', [
	'vnd.ms-excel.sheet.macroEnabled.12' => 'xlsm',
	'vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
	'vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
	'vnd.ms-excel' => 'xls',
	'vnd.ms-powerpoint' => 'ppt',
	'pdf' => 'pdf',
	'png' => 'png',
	'jpg' => 'jpg',
	'jpeg' => 'jpeg',
	'xml' => 'xml',
	'x-zip-compressed' => 'zip'
]);
defined('FILES_TIPO_WASABI') or define('FILES_TIPO_WASABI', [
	'vnd.ms-excel.sheet.macroEnabled.12' => '6',
	'vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '6',
	'vnd.openxmlformats-officedocument.presentationml.presentation' => '5',
	'vnd.ms-excel' => '6',
	'vnd.ms-powerpoint' => '5',
	'pdf' => '3',
	'png' => '2',
	'jpg' => '2',
	'jpeg' => '2',
	'xml' => '5',
	'x-zip-compressed' => '5'
]);

//Tablas
defined('TABLA_HISTORICO_ESTADO_COTIZACION') or define('TABLA_HISTORICO_ESTADO_COTIZACION', 'compras.cotizacionEstadoHistorico');

//Compras
defined('IGV') or define('IGV', 0.18);
defined('LIMITE_COMPRAS') or define('LIMITE_COMPRAS', 1000);
defined('RUC_VISUAL') or define('RUC_VISUAL', '20467225155');

//Operaciones
defined('GAP') or define('GAP', 15);
defined('MONTOGAP') or define('MONTOGAP', 1500);

//Tipos de Item
defined('COD_ARTICULO')			or define('COD_ARTICULO', ['id' => 1, 'nombre' => 'ARTICULO']);
defined('COD_SERVICIO')			or define('COD_SERVICIO', ['id' => 2, 'nombre' => 'SERVICIO']);
defined('COD_COMPUTO')			or define('COD_COMPUTO', ['id' => 3, 'nombre' => 'COMPUTO']);
defined('COD_MOVIL')				or define('COD_MOVIL', ['id' => 4, 'nombre' => 'MOVIL']);
defined('COD_PERSONAL')			or define('COD_PERSONAL', ['id' => 5, 'nombre' => 'PERSONAL']);
defined('COD_EVENTO')			or define('COD_EVENTO', ['id' => 6, 'nombre' => 'EVENTO']);
defined('COD_DISTRIBUCION')	or define('COD_DISTRIBUCION', ['id' => 7, 'nombre' => 'DISTRIBUCION']);
defined('COD_TEXTILES')			or define('COD_TEXTILES', ['id' => 9, 'nombre' => 'TEXTILES']);
defined('COD_TARJETAS_VALES')	or define('COD_TARJETAS_VALES', ['id' => 10, 'nombre' => 'TARJETAS_VALES']);
defined('COD_TRANSPORTE')		or define('COD_TRANSPORTE', ['id' => 12, 'nombre' => 'TRANSPORTE']);

//Wireframe

defined('RUTA_WIREFRAME') or define('RUTA_WIREFRAME', '../public/assets/images/wireframe/');
defined('IMG_WIREFRAME') or define('IMG_WIREFRAME', '../public/assets/images/wireframe/image.png');
defined('IMG_WIREFRAME2') or define('IMG_WIREFRAME2', '../public/assets/images/wireframe/clip.png');

//Tipo Archivo

defined('TIPO_ORDEN_COMPRA') or define('TIPO_ORDEN_COMPRA', 1);
defined('TIPO_IMAGEN') or define('TIPO_IMAGEN', 2);
defined('TIPO_PDF') or define('TIPO_PDF', 3);
defined('TIPO_LINK') or define('TIPO_LINK', 4);
defined('TIPO_OTROS') or define('TIPO_OTROS', 5);
defined('TIPO_EXCEL') or define('TIPO_EXCEL', 6);
defined('TIPO_ENLACE') or define('TIPO_ENLACE', 7);

//ESTADO COTIZACION

defined("ESTADO_REGISTRADO") or define("ESTADO_REGISTRADO", 1);
defined("ESTADO_ENVIADO_COMPRAS") or define("ESTADO_ENVIADO_COMPRAS", 2);
defined("ESTADO_CONFIRMADO_COMPRAS") or define("ESTADO_CONFIRMADO_COMPRAS", 3);
defined("ESTADO_ENVIADO_CLIENTE") or define("ESTADO_ENVIADO_CLIENTE", 4);
defined("ESTADO_COTIZACION_APROBADA") or define("ESTADO_COTIZACION_APROBADA", 5);
defined("ESTADO_OPER_GENERADO") or define("ESTADO_OPER_GENERADO", 6);
defined("ESTADO_OPER_ENVIADO") or define("ESTADO_OPER_ENVIADO", 7);
defined("ESTADO_OC_GENERADA") or define("ESTADO_OC_GENERADA", 8);
defined("ESTADO_OC_ENVIADA") or define("ESTADO_OC_ENVIADA", 9);
defined("ESTADO_OC_CONFIRMADA") or define("ESTADO_OC_CONFIRMADA", 10);
defined("ESTADO_FINALIZADA") or define("ESTADO_FINALIZADA", 11);

//ESTADO COTIZACION INTERNA
defined("INTERNA_ENVIADA") or define("INTERNA_ENVIADO", 1);
defined("INTERNA_PRECIO_RECIBIDO") or define("INTERNA_PRECIO_RECIBIDO", 2);
defined("INTERNA_CONFIRMADA") or define("INTERNA_CONFIRMADA", 3);
defined("INTERNA_FINALIZADA") or define("INTERNA_FINALIZADA", 4);

//ARCHIVOS PERMITIDOS
defined("ARCHIVOS_PERMITIDOS") or define("ARCHIVOS_PERMITIDOS", "image/*,.pdf,.xls,.xlsx,.ppt,.pptx");
defined("KB_MAXIMO_ARCHIVO") or define("KB_MAXIMO_ARCHIVO", 7168); //7MB
defined("MAX_ARCHIVOS") or define("MAX_ARCHIVOS", 10);

//TIPO AUTORIZACION

defined("AUTH_CAMBIO_COSTO") or define("AUTH_CAMBIO_COSTO", 1);

//AUTORIZACION ESTADO
defined("AUTH_ESTADO_PENDIENTE") or define("AUTH_ESTADO_PENDIENTE", 1);
defined("AUTH_ESTADO_ACEPTADO") or define("AUTH_ESTADO_ACEPTADO", 2);
defined("AUTH_ESTADO_RECHAZADO") or define("AUTH_ESTADO_RECHAZADO", 3);

//TIPOS DE USUARIO 
defined("USER_ADMIN") or define("USER_ADMIN", 1);
defined("USER_COORDINADOR_OPERACIONES") or define("USER_COORDINADOR_OPERACIONES", 2);
defined("USER_COORDINADOR_COMPRAS") or define("USER_COORDINADOR_COMPRAS", 3);
defined("USER_GERENTE_OPERACIONES") or define("USER_GERENTE_OPERACIONES", 4);

//CORREOS 
defined("MAIL_DESARROLLO") or define("MAIL_DESARROLLO", ['eder.alata@visualimpact.com.pe']);
defined("MAIL_COORDINADORA_OPERACIONES") or define("MAIL_COORDINADORA_OPERACIONES", ['andrivette.tavara@visualimpact.com.pe', 'margarita.bailon@visualimpact.com.pe', 'tamar.roque@visualimpact.com.pe']);
defined("MAIL_COORDINADORA_COMPRAS") or define("MAIL_COORDINADORA_COMPRAS", ['anghy.vega@visualimpact.com.pe', 'diana.zuniga@visualimpact.com.pe']);
defined("MAIL_GERENCIA_OPERACIONES") or define("MAIL_GERENCIA_OPERACIONES", ['milenka.gargurevich@visualimpact.com.pe']);

//Acceso
defined("SECRET_KEY_GET") or define("SECRET_KEY_GET", 'CLAVESUPERSECRETA');
defined("DIAS_MAX_ACCESO") or define("DIAS_MAX_ACCESO", 30);
defined("URL_WASABI_ITEM_PROPUESTA") or define("URL_WASABI_ITEM_PROPUESTA", 'https://s3.us-central-1.wasabisys.com/impact.business/itemPropuesta/');

// Genero
defined('LIST_GENERO') or define('LIST_GENERO', [['id' => 1, 'value' => 'VARON'], ['id' => 2, 'value' => 'DAMA'], ['id' => 3, 'value' => 'UNISEX']]);
defined('RESULT_GENERO') or define('RESULT_GENERO', ['1' => 'VARON', '2' => 'DAMA', '3' => 'UNISEX']);

defined('COD_SUELDO') or define('COD_SUELDO', '1'); // ImpactBussiness.compras.tipoPresupuesto
defined('COD_GASTOSADMINISTRATIVOS') or define('COD_GASTOSADMINISTRATIVOS', '7'); // ImpactBussiness.compras.tipoPresupuesto
defined('COD_MOVILIDAD') or define('COD_MOVILIDAD', '8'); // ImpactBussiness.compras.tipoPresupuesto
defined('COD_ALMACEN') or define('COD_ALMACEN', '9'); // ImpactBussiness.compras.tipoPresupuesto
defined('COD_SUELDOMINIMO') or define('COD_SUELDOMINIMO', '1'); // ImpactBussiness.compras.tipoPresupuestoDetalle
defined('COD_ASIGNACIONFAMILIAR') or define('COD_ASIGNACIONFAMILIAR', '2'); // ImpactBussiness.compras.tipoPresupuestoDetalle
defined('COD_SCTR') or define('COD_SCTR', '31'); // ImpactBussiness.compras.tipoPresupuestoDetalle

// IDs SERIADOS
defined("OPER_SERIADO") or define("OPER_SERIADO", 1);
defined("OC_SERIADO") or define("OC_SERIADO", 2);


defined('LIST_FRECUENCIA') or define('LIST_FRECUENCIA', [
	['id' => 1, 'value' => 'MENSUAL'],
	['id' => 2, 'value' => 'BIMENSUAL'],
	['id' => 3, 'value' => 'SEMESTRAL'],
	['id' => 4, 'value' => 'ANUAL'],
	['id' => 5, 'value' => 'UNICO'],
	['id' => 6, 'value' => 'FRACCIONADO'],
]);

defined('RESULT_FRECUENCIA') or define('RESULT_FRECUENCIA', [
	'1' => 'MENSUAL',
	'2' => 'BIMENSUAL',
	'3' => 'SEMESTRAL',
	'4' => 'ANUAL',
	'5' => 'UNICO',
	'6' => 'FRACCIONADO'
]);

defined('NOMBRE_MES') or define('NOMBRE_MES', [
	'01' => 'ENERO',
	'02' => 'FEBRERO',
	'03' => 'MARZO',
	'04' => 'ABRIL',
	'05' => 'MAYO',
	'06' => 'JUNIO',
	'07' => 'JULIO',
	'08' => 'AGOSTO',
	'09' => 'SEPTIEMBRE',
	'10' => 'OCTUBRE',
	'11' => 'NOVIEMBRE',
	'12' => 'DICIEMBRE'
]);
