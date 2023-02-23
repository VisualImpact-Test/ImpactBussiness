<?php
defined('BASEPATH') or exit('No direct script access allowed');

function numRandom($digits = 4)
{
	return str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
}

//handsotable
function refactorizarDataHT($data = []){
	$dataRefactorizada = [];
	foreach ($data["data"] as $row) {
		if (!in_array($row[$data["value"]], $dataRefactorizada)) $dataRefactorizada[] = $row[$data["value"]];
	}
	return !empty($dataRefactorizada) ? $dataRefactorizada : [];
}



function fn_404()
{
	$config['css']['style'] = array();
	$config['single'] = true;
	$config['view'] = 'templates/404';
	$this->view($config);
}

function checkPassword($password)
{
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);

	if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
		return false;
	} else {
		return true;
	}
}

function getHashedPassword($password)
{
	// $hashed_password = password_hash($password, PASSWORD_DEFAULT);
	// $salt = generateRandomString();
	// $hashed_password = crypt($password, $salt);
	$salt = $password;
	$hashed_password = crypt($password, $salt);
	return $hashed_password;
}

function date_change_format($fecha)
{
	if (empty($fecha)) return '';
	$fecha = new DateTime($fecha);
	return $fecha->format('d/m/Y');
}

function date_change_format_bd($fecha)
{
	$result = NULL;
	if (!empty($fecha)) {
		$array_fecha = explode('/', $fecha);
		$result = trim($array_fecha[2]) . '-' . trim($array_fecha[1]) . '-' . trim($array_fecha[0]);
	}
	return $result;
}

function getPrimerYUltimoDia($fecha)
{

	$fecha = explode('/', $fecha);
	$fecha = $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2];
	$fechas[0] = date('01/m/Y', strtotime($fecha));
	$fechas[1] = date('t/m/Y', strtotime($fecha));

	return $fechas;
}

function time_change_format($time)
{
	$time = new DateTime($time);
	return $time->format("H:i:s");
}

function createMessage($config = array())
{
	$defaults = array('type' => 0, 'message' => '', 'iconSize' => 'fa-2x');
	$config += $defaults;

	$icon = '';
	$message = '';

	$iconSize = ' ' . $config['iconSize'];
	$marginTop = '';
	if ($config['iconSize'] == 'fa-2x') {
		$marginTop = 'mt-1';
	} elseif ($config['iconSize'] == 'fa-3x') {
		$marginTop = 'mt-2';
	}

	switch ($config['type']) {
		case 0:
			if (empty($config['message'])) {
				$config['message'] = 'No se logró ejecutar la acción, consulte con el administrador';
			}

			$icon .= 'fas fa-times-circle' . $iconSize . ' text-danger';
			$message .= 'Error! ' . $config['message'] . '.';
			break;
		case 1:
			$icon .= 'fas fa-check-circle' . $iconSize . ' text-success';
			$message .= 'Ok! ' . $config['message'] . '.';
			break;
		case 2:
			$icon .= 'fas fa-exclamation-circle' . $iconSize . ' text-warning';
			$message .= 'Alerta! ' . $config['message'] . '.';
			break;
		case 3:
			$icon .= 'fas fa-question-circle' . $iconSize . ' text-primary';
			$message .= $config['message'];
			break;
		case 4:
			$icon .= 'fas fa-user-times' . $iconSize . ' text-secondary';
			$message .= $config['message'] . '.';
			break;
		default:
			$icon .= 'far fa-comment-alt fa-3x';
			$message .= $config['message'];
			break;
	}

	$html = '';
	// $html .= '<p class="text-left">';
	// $html .= '<i class="'.$icon.' mr-2"></i>'.$message.'.';
	// $html .= '</p>';
	$html .= '<i class="' . $icon . ' mr-2 float-left"></i>';
	$html .= '<p class="text-left ' . $marginTop . '">' . $message . '</p>';

	return $html;
}

function hideNumDocumento($numDocumento)
{
	$parteVisible = substr($numDocumento, -5);
	$parteNoVisible = str_repeat('*', strlen($numDocumento) - 5);
	return $parteNoVisible . $parteVisible;
}

function colorCuenta()
{
	$return = '#000000';
	if ($_SESSION['idCuenta'] == 1) $return = '#ed2c2f';
	elseif ($_SESSION['idCuenta'] == 3) $return = '#005aa1';
	return $return;
}

function moneda($valor, $igv = false, $dec = 2)
{
	if ($igv) $valor = $valor / 1.18;
	if (is_string($valor)) return $valor;
	else {
		$valor = 'S/ ' . number_format($valor, $dec, '.', ',');
		return $valor;
	}
}
function monedaNew($params = [])
{
	$valor = !empty($params['valor']) ? $params['valor'] : '';
	$dec = !empty($params['dec']) ? $params['dec'] : 2;
	$simbolo = !empty($params['simbolo']) ? $params['simbolo'] : 'S/';
	$cambio = !empty($params['cambio']) ? $params['cambio'] : 1;

	if (is_string($valor)) return $valor;
	else {
		$valor = $simbolo . number_format($valor / $cambio, $dec, '.', ',');
		return $valor;
	}
}

function getFechaDias($fecha, $dias = 0)
{
	$pos = strpos($fecha, '/');
	if ($pos !== false) {
    $fecha = date_change_format_bd($fecha);
	}
	$fecha = date($fecha);
	return date('d/m/Y', strtotime($fecha." {$dias} days"));
}

function getFechaActual($dias = 0)
{
	date_default_timezone_set('America/Lima');
	if (!empty($dias)) {
		return date('d/m/Y', strtotime("{$dias} days"));
	}
	return date('d/m/Y', time());
}

function getActualDateTime()
{
	$dateTimeObject = new DateTime();
	$actualDateTime = date_format($dateTimeObject, 'Y-m-d H:i:s');
	$actualDateTime = explode(" ", $actualDateTime);
	$actualDateTime = implode("T", $actualDateTime);
	return $actualDateTime;
}

function isArrayAssoc($array)
{
	if (array() === $array) return false;
	return array_keys($array) !== range(0, count($array) - 1);
}

function getFechasDRP($fechaDeDateRangePicker)
{
	return $fechas = explode(' - ', $fechaDeDateRangePicker);
}

function getPermisos($get = array(), $idProyecto = 0)
{
	$defaults = array(
		'cuenta' => 'cu.idCuenta',
		'proyecto' => 'py.idProyecto',
		'grupoCanal' => 'gca.idGrupoCanal',
		'canal' => 'ca.idCanal',
		'subCanal' => 'sca.idSubCanal',
		'zona' => 'z.idZona',
		'plaza' => 'pl.idPlaza',
		'distribuidora' => 'd.idDistribuidora',
		'distribuidoraSucursal' => 'ds.idDistribuidoraSucursal',
		'cadena' => 'cd.idCadena',
		'banner' => 'bn.idBanner'
	);

	if (!is_array($get)) {
		$text = $get;
		unset($get);
		if (isset($defaults[$text])) {
			$get[$text] = $defaults[$text];
		}
	} elseif (!isArrayAssoc($get)) {
		$newGet = array();
		if (empty($get)) {
			$newGet = $defaults;
		} else {
			foreach ($get as $v) {
				if (isset($defaults[$v]))
					$newGet[$v] = $defaults[$v];
			}
		}

		$get = $newGet;
	} else {
		$get = array_intersect_key($get, $defaults);
	}

	$CI = &get_instance();
	$permisos = $CI->permisos;

	$aIdGrupoCanal = array(
		'tradicional' => array(1, 4),
		'mayorista' => array(5),
		'moderno' => array(2, 8),
	);

	$filtro = "";

	if (!empty($permisos['cuenta'])) {
		if (isset($get['cuenta'])) {
			$filtro .= " AND " . $get['cuenta'] . " IN (";
			$filtro .= implode(',', $permisos['cuenta']);
			$filtro .= ")";
		}
	}

	// if( !empty($permisos['proyecto']) ){
	// if( isset($get['proyecto']) ){
	// $filtro .= " AND ".$get['proyecto']." IN (";
	// $filtro .= implode(',', $permisos['proyecto']);
	// $filtro .= ")";
	// }
	// }

	// if( count($permisos['proyecto']) == 1 ){
	// $idProyecto = array_values($permisos['proyecto']);
	// $idProyecto = $idProyecto[0];
	// }

	if (
		empty($idProyecto) &&
		(isset($get['grupoCanal']) ||
			isset($get['canal']) ||
			isset($get['subCanal'])
		) &&
		(isset($get['zona']) ||
			isset($get['plaza']) ||
			isset($get['distribuidoraSucursal']) ||
			isset($get['banner'])
		)
	) {
		$filtroGrupoCanal = array();

		if (isset($permisos['proyecto'])) {
			foreach ($permisos['proyecto'] as $k_py => $v_py) {
				$wProyecto = (!empty($get['proyecto']) ? $get['proyecto'] : $defaults['proyecto']) . ' = ' . $v_py;

				// GRUPO CANAL - TRADICIONAL
				if (
					isset($get['zona']) ||
					isset($get['distribuidoraSucursal'])
				) {

					$aGrupoCanal = array();
					$aCanal = array();
					$aSubCanal = array();

					foreach ($aIdGrupoCanal['tradicional'] as $k_gca) {
						if (isset($get['grupoCanal']) && !isset($get['canal']) && !isset($get['subCanal'])) {
							$aGrupoCanal[$k_gca] = $k_gca;
						} elseif (isset($permisos['canal'][$v_py][$k_gca])) {
							foreach ($permisos['canal'][$v_py][$k_gca] as $k_ca) {
								if (
									isset($get['canal']) &&
									(!isset($get['subCanal']) ||
										!isset($permisos['subCanal'][$v_py][$k_gca][$k_ca])
									)
								) {
									$aCanal[$k_ca] = $k_ca;
								} elseif (
									isset($get['subCanal'])	&&
									isset($permisos['subCanal'][$v_py][$k_gca][$k_ca])
								) {
									foreach ($permisos['subCanal'][$v_py][$k_gca][$k_ca] as $k_sca) {
										$aSubCanal[$k_sca] = $k_sca;
									}
								}
							}
						}
					}

					$wSegNegocio = "";
					$wSegNegocio .= !empty($aGrupoCanal) ? " AND {$get['grupoCanal']} IN (" . implode(',', $aGrupoCanal) . ")" : '';
					$wSegNegocio .= !empty($aCanal) ? " AND {$get['canal']} IN (" . implode(',', $aCanal) . ")" : '';
					$wSegNegocio .= !empty($aSubCanal) ? " AND {$get['subCanal']} IN (" . implode(',', $aSubCanal) . ")" : '';

					$aTradicional = array();
					if (!empty($get['zona'])) {
						$aTradicional['zona'] = $get['zona'];
					}
					if (!empty($get['distribuidoraSucursal'])) {
						$aTradicional['distribuidoraSucursal'] = $get['distribuidoraSucursal'];
					}

					$tradicional = getPermisos($aTradicional, $v_py);
					$tradicional = substr($tradicional, 5, strlen($tradicional));
					$tradicional = str_replace(" AND ", " OR ", $tradicional);

					if (!empty($tradicional)) {
						$filtroGrupoCanal[] = "({$wProyecto}{$wSegNegocio} AND ({$tradicional}))";
					}
				}

				// GRUPO CANAL - MAYORISTA
				if (isset($get['plaza'])) {
					$aGrupoCanal = array();
					$aCanal = array();
					$aSubCanal = array();

					foreach ($aIdGrupoCanal['mayorista'] as $k_gca) {
						if (isset($get['grupoCanal']) && !isset($get['canal']) && !isset($get['subCanal'])) {
							$aGrupoCanal[$k_gca] = $k_gca;
						} elseif (isset($permisos['canal'][$v_py][$k_gca])) {
							foreach ($permisos['canal'][$v_py][$k_gca] as $k_ca) {
								if (isset($get['canal']) && !isset($get['subCanal'])) {
									$aCanal[$k_ca] = $k_ca;
								} elseif (
									isset($get['subCanal'])	&&
									isset($permisos['subCanal'][$v_py][$k_gca][$k_ca])
								) {
									foreach ($permisos['subCanal'][$v_py][$k_gca][$k_ca] as $k_sca) {
										$aSubCanal[$k_sca] = $k_sca;
									}
								}
							}
						}
					}

					$wSegNegocio = "";
					$wSegNegocio .= !empty($aGrupoCanal) ? " AND {$get['grupoCanal']} IN (" . implode(',', $aGrupoCanal) . ")" : '';
					$wSegNegocio .= !empty($aCanal) ? " AND {$get['canal']} IN (" . implode(',', $aCanal) . ")" : '';
					$wSegNegocio .= !empty($aSubCanal) ? " AND {$get['subCanal']} IN (" . implode(',', $aSubCanal) . ")" : '';

					$mayorista = getPermisos(array('plaza' => $get['plaza']), $v_py);
					$mayorista = substr($mayorista, 5, strlen($mayorista));
					$mayorista = str_replace(" AND ", " OR ", $mayorista);

					if (!empty($mayorista)) {
						$filtroGrupoCanal[] = "({$wProyecto}{$wSegNegocio} AND ({$mayorista}))";
					}
				}

				// GRUPO CANAL - MODERNO
				if (isset($get['banner'])) {
					$aGrupoCanal = array();
					$aCanal = array();
					$aSubCanal = array();

					foreach ($aIdGrupoCanal['mayorista'] as $k_gca) {
						if (isset($get['grupoCanal']) && !isset($get['canal']) && !isset($get['subCanal'])) {
							$aGrupoCanal[$k_gca] = $k_gca;
						} elseif (isset($permisos['canal'][$v_py][$k_gca])) {
							foreach ($permisos['canal'][$v_py][$k_gca] as $k_ca) {
								if (isset($get['canal']) && !isset($get['subCanal'])) {
									$aCanal[$k_ca] = $k_ca;
								} elseif (
									isset($get['subCanal'])	&&
									isset($permisos['subCanal'][$v_py][$k_gca][$k_ca])
								) {
									foreach ($permisos['subCanal'][$v_py][$k_gca][$k_ca] as $k_sca) {
										$aSubCanal[$k_sca] = $k_sca;
									}
								}
							}
						}
					}

					$wSegNegocio = "";
					$wSegNegocio .= !empty($aGrupoCanal) ? " AND {$get['grupoCanal']} IN (" . implode(',', $aGrupoCanal) . ")" : '';
					$wSegNegocio .= !empty($aCanal) ? " AND {$get['canal']} IN (" . implode(',', $aCanal) . ")" : '';
					$wSegNegocio .= !empty($aSubCanal) ? " AND {$get['subCanal']} IN (" . implode(',', $aSubCanal) . ")" : '';

					$moderno = getPermisos(array('banner' => $get['banner']), $v_py);
					$moderno = substr($moderno, 5, strlen($moderno));
					$moderno = str_replace(" AND ", " OR ", $moderno);

					if (!empty($moderno)) {
						$filtroGrupoCanal[] = "({$wProyecto}{$wSegNegocio} AND ({$moderno}))";
					}
				}

				if (
					empty($tradicional) &&
					empty($mayorista) &&
					empty($moderno)
				) {
					$filtroGrupoCanal[] = "({$wProyecto})";
				}
			}
		}

		if (!empty($filtroGrupoCanal)) {
			$filtro .= " AND (";
			$filtro .= implode(' OR ', $filtroGrupoCanal);
			$filtro .= ")";
		}
	} else {
		if (!empty($permisos['proyecto'])) {
			if (isset($get['proyecto'])) {
				$filtro .= " AND " . $get['proyecto'] . " IN (";
				$filtro .= implode(',', $permisos['proyecto']);
				$filtro .= ")";
			}

			if (count($permisos['proyecto']) == 1) {
				$idProyecto = array_values($permisos['proyecto']);
				$idProyecto = $idProyecto[0];
			}
		}

		if (!empty($permisos['grupoCanal'][$idProyecto])) {
			if (isset($get['grupoCanal'])) {
				$filtro .= " AND " . $get['grupoCanal'] . " IN (";
				$filtro .= implode(',', $permisos['grupoCanal'][$idProyecto]);
				$filtro .= ")";
			}

			if (isset($get['canal']) || isset($get['subCanal'])) {
				$aCanal = array();
				$aSubCanal = array();

				foreach ($permisos['grupoCanal'][$idProyecto] as $k_gca => $v_gca) {
					if (!empty($permisos['canal'][$idProyecto][$v_gca])) {
						foreach ($permisos['canal'][$idProyecto][$v_gca] as $k_ca => $v_ca) {
							if (isset($get['canal'])) {
								$aCanal[] = $v_ca;
							}

							if (isset($get['subCanal'])) {
								if (!empty($permisos['subCanal'][$idProyecto][$v_gca][$v_ca])) {
									foreach ($permisos['subCanal'][$idProyecto][$v_gca][$v_ca] as $k_sca => $v_sca) {
										$aSubCanal[] = $v_sca;
									}
								}
							}
						}
					}
				}

				if (!empty($aCanal)) {
					$filtro .= " AND " . $get['canal'] . " IN (";
					$filtro .= implode(',', $aCanal);
					$filtro .= ")";
				}

				if (!empty($aSubCanal)) {
					$filtro .= " AND " . $get['subCanal'] . " IN (";
					$filtro .= implode(',', $aSubCanal);
					$filtro .= ")";
				}
			}
		}

		if (!empty($permisos['zona'][$idProyecto])) {
			if (isset($get['zona'])) {
				$filtro .= " AND " . $get['zona'] . " IN (";
				$filtro .= implode(',', $permisos['zona'][$idProyecto]);
				$filtro .= ")";
			}
		}

		if (!empty($permisos['plaza'][$idProyecto])) {
			if (isset($get['plaza'])) {
				$filtro .= " AND " . $get['plaza'] . " IN (";
				$filtro .= implode(',', $permisos['plaza'][$idProyecto]);
				$filtro .= ")";
			}
		}

		if (!empty($permisos['distribuidora'][$idProyecto])) {
			if (isset($get['distribuidora'])) {
				$filtro .= " AND " . $get['distribuidora'] . " IN (";
				$filtro .= implode(',', $permisos['distribuidora'][$idProyecto]);
				$filtro .= ")";
			}

			if (isset($get['distribuidoraSucursal'])) {
				$aDistribuidoraSucursal = array();

				foreach ($permisos['distribuidora'][$idProyecto] as $k_d => $v_d) {
					if (!empty($permisos['distribuidoraSucursal'][$idProyecto][$v_d])) {
						foreach ($permisos['distribuidoraSucursal'][$idProyecto][$v_d] as $k_ds => $v_ds) {
							$aDistribuidoraSucursal[] = $v_ds;
						}
					}
				}

				if (!empty($aDistribuidoraSucursal)) {
					$filtro .= " AND " . $get['distribuidoraSucursal'] . " IN (";
					$filtro .= implode(',', $aDistribuidoraSucursal);
					$filtro .= ")";
				}
			}
		}

		if (!empty($permisos['cadena'][$idProyecto])) {
			if (isset($get['cadena'])) {
				$filtro .= " AND " . $get['cadena'] . " IN (";
				$filtro .= implode(',', $permisos['cadena'][$idProyecto]);
				$filtro .= ")";
			}

			if (isset($get['banner'])) {
				$aBanner = array();

				foreach ($permisos['cadena'][$idProyecto] as $k_cd => $v_cd) {
					if (!empty($permisos['banner'][$idProyecto][$v_cd])) {
						foreach ($permisos['banner'][$idProyecto][$v_cd] as $k_bn => $v_bn) {
							$aBanner[] = $v_bn;
						}
					}
				}

				if (!empty($aBanner)) {
					$filtro .= " AND " . $get['banner'] . " IN (";
					$filtro .= implode(',', $aBanner);
					$filtro .= ")";
				}
			}
		}
	}
	// responder:
	return $filtro;
}

function getFiltros($get = array())
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');

	$defaults = getFiltrosDefault();

	if (!is_array($get)) {
		$text = $get;
		unset($get);
		if (isset($defaults[$text])) {
			$get[$text] = $defaults[$text];
		}
	} elseif (!isArrayAssoc($get)) {
		$newGet = array();
		foreach ($get as $v) {
			if (isset($defaults[$v])) {
				$newGet[$v] = $defaults[$v];
			}
		}

		$get = $newGet;
	} else {
		foreach ($get as $idx => $cfg) {
			if (isset($defaults[$idx])) {
				$get[$idx] = $cfg + $defaults[$idx];
			} elseif (isset($defaults[$cfg])) {
				$get[$cfg] = $defaults[$cfg];
				unset($get[$idx]);
			}
		}
	}

	$html = "";
	$selectHtml = "";

	//Variables de session Cuenta y Proyecto
	$sessIdCuenta = $_SESSION['idCuenta'];
	$sessIdProyecto = $_SESSION['idProyecto'];

	$aDatos = array();
	if (isset($get['cuenta']) && $get['cuenta']['data']) {
		if (!empty($sessIdCuenta)) {
			$aDatos['cuenta'] = $CI->m_control->get_cuenta(['idCuenta' => $sessIdCuenta]);
		} else {
			$aDatos['cuenta'] = $CI->m_control->get_cuenta();
		}
	}

	if (isset($get['proyecto']) && $get['proyecto']['data']) {
		$aDatos['proyecto'] = $CI->m_control->get_proyecto();
		if (!empty($sessIdProyecto)) {
			$aDatos['proyecto'] = $CI->m_control->get_proyecto(['idProyecto' => $sessIdProyecto]);
		} else {
			$aDatos['proyecto'] = $CI->m_control->get_proyecto();
		}
	}

	if (isset($get['grupoCanal']) && $get['grupoCanal']['data']) {
		$aDatos['grupoCanal'] = $CI->m_control->get_grupoCanal(['idProyecto' => $sessIdProyecto]);
	}

	if (isset($get['canal']) && $get['canal']['data']) {
		$aDatos['canal'] = $CI->m_control->get_canal(['idProyecto' => $sessIdProyecto]);
	}

	if (isset($get['zona']) && $get['zona']['data']) {
		$aDatos['zona'] = $CI->m_control->get_zona();
	}

	if (isset($get['plaza']) && $get['plaza']['data']) {
		$aDatos['plaza'] = $CI->m_control->get_plaza();
	}

	if (isset($get['distribuidora']) && $get['distribuidora']['data']) {
		$aDatos['distribuidora'] = $CI->m_control->get_distribuidora();
	}

	if (isset($get['cadena']) && $get['cadena']['data']) {
		$aDatos['cadena'] = $CI->m_control->get_cadena();
	}

	if (isset($get['tipoCliente']) && $get['tipoCliente']['data']) {
		$aDatos['tipoCliente'] = $CI->m_control->get_tipoCliente();
	}

	if (isset($get['banner']) && $get['banner']['data']) {
		$aDatos['banner'] = $CI->m_control->get_banner();
	}
	if (isset($get['tipoUsuario']) && $get['tipoUsuario']['data']) {
		$aDatos['tipoUsuario'] = $CI->m_control->get_tiposUsuario();
	}
	if (isset($get['usuario']) && $get['usuario']['data']) {
		$aDatos['usuario'] = $CI->m_control->get_usuarios();
	}
	if (isset($get['frecuencia']) && $get['frecuencia']['data']) {
		$aDatos['frecuencia'] = $CI->m_control->get_frecuencia();
	}

	foreach ($get as $idx => $val) {
		$label = $val['label'];
		$divHtml = $val['divHtml'];
		$selected = !empty($val['selected']) ? $val['selected'] : '';

		$hide = '';
		if ($idx == 'cuenta' && (!empty($sessIdCuenta) || (isset($aDatos[$idx]) && count($aDatos[$idx]) == 1
		)
		)) {
			$hide = ' hide-parent';
		} elseif ($idx == 'proyecto' && (!empty($sessIdProyecto) || (isset($aDatos[$idx]) && count($aDatos[$idx]) == 1
		)
		)) {
			$hide = ' hide-parent';
		}

		$name = !empty($val['name']) ? 'name="' . $val['name'] . '"' : '';
		$id = !empty($val['id']) ? 'id="' . $val['id'] . '"' : '';
		$multiple = !empty($val['multiple']) ? 'multiple' : '';
		$class = 'class="flt_' . $idx . ' form-control form-control-sm ';

		if (empty($hide)) $class .= $val['select2'];
		else $class .= $hide;

		if (!empty($val['class'])) {
			if (is_array($val['class'])) {
				$class .= implode(' ', $val['class']);
			} else {
				$class .= $val['class'];
			}
		}
		$class .= '"';

		$attr = array($id, $class, $name);
		$attr = implode(' ', $attr);

		$selectHtml .= '<select ' . $attr . ' required ' . $multiple . '  >';
		if ($label) {
			$selectHtml .= '<option value="">-- ' . ($label) . ' --</option>';
		}
		if (!empty($aDatos[$idx])) {
			foreach ($aDatos[$idx] as $row) {
				$oId = $row['id'];
				$oValue = $row['nombre'];

				$select = '';
				if (
					$idx == 'cuenta' &&
					$row['id'] == $sessIdCuenta
				) {
					$select = "selected";
				} elseif (
					$idx == 'proyecto' &&
					$row['id'] == $sessIdProyecto
				) {
					$select = "selected ";
				} elseif ($idx == 'grupoCanal') {
					if (!empty($selected) && $row['id'] == $selected) {
						$select = "selected ";
					}
				} elseif ($idx == 'tipoUsuario') {
					if (!empty($selected) && $row['id'] == $selected) {
						$select = "selected ";
					}
				} elseif ($idx == 'usuario') {
					if (!empty($selected) && $row['id'] == $selected) {
						$select = "selected ";
					}
				}

				$selectHtml .= '<option value="' . $oId . '" ' . $select . '>' . $oValue . '</option>';
			}
		}
		$selectHtml .= '</select>';

		if (!empty($divHtml)) {
			$html = $selectHtml;
		} else {
			$html .= '<div class="form-group">' . $selectHtml . '</div>';
		}
	}

	return $html;
}

function getFiltrosDefault()
{
	$defaults = array(
		'cuenta' => array('label' => 'Cuenta', 'name' => 'idCuenta', 'data' => true),
		'proyecto' => array('label' => 'Proyecto', 'name' => 'idProyecto'),
		'grupoCanal' => array('label' => 'Grupo Canal', 'name' => 'idGrupoCanal'),
		'canal' => array('label' => 'Canal', 'name' => 'idCanal'),
		'subCanal' => array('label' => 'Sub Canal', 'name' => 'idSubCanal'),
		'zona' => array('label' => 'Zona', 'name' => 'idZona'),
		'plaza' => array('label' => 'Plaza', 'name' => 'idPlaza'),
		'distribuidora' => array('label' => 'Distribuidora', 'name' => 'idDistribuidora'),
		'distribuidoraSucursal' => array('label' => 'Distribuidora Sucursal', 'name' => 'idDistribuidoraSucursal'),
		'cadena' => array('label' => 'Cadena', 'name' => 'idCadena'),
		'banner' => array('label' => 'Banner', 'name' => 'idBanner'),
		'encargado' => array('label' => 'Encargado', 'name' => 'idEncargado'),
		'colaborador' => array('label' => 'Colaborador', 'name' => 'idUsuario'),
		'tipoCliente' => array('label' => 'Tipo Cliente', 'name' => 'idTipoCliente'),
		'tipoUsuario' => array('label' => 'Tipo Usuario', 'name' => 'idTipoUsuario'),
		'usuario' => array('label' => 'Usuario', 'name' => 'idUsuario'),
		'frecuencia' => array('label' => 'Frecuencia', 'name' => 'idFrecuencia'),
	);

	$additional = array(
		'id' => '',
		'class' => '',
		'attr' => array(),
		'data' => false,
		'select2' => 'my_select2',
		'divHtml' => 'formGroup',
	);

	foreach ($defaults as $k_df => $v_df) {
		$defaults[$k_df] += $additional;
	}

	return $defaults;
}

/************GUARDAR AUDITORIA************/
function guardarAuditoria($params = array())
{

	$CI = &get_instance();

	if ($CI->db->table_exists('auditoria_usuario')) {
		if (array_key_exists('idUsuario', $params) && array_key_exists('accion', $params) && array_key_exists('tabla', $params) && array_key_exists('sql', $params)) {
			if ($params['idUsuario'] != null && $params['accion'] != null && $params['tabla'] != null && $params['sql'] != null) {

				$arrayData = array(
					'idUsuario' => $params['idUsuario'], 'accion' => $params['accion'], 'tabla' => $params['tabla'], 'sqlEjecutado' => str_replace("'", '"', $params['sql']), 'ip' => $_SERVER['REMOTE_ADDR']
				);

				$CI->db->insert('trade.auditoria_usuario', $arrayData);
			}
		}
	}
}

function getMensajeGestion($tipoMensaje, $input = [])
{
	$mensaje = [
		'actualizacionExitosa' => createMessage(array("type" => 1, "message" => 'La actualización se realizó correctamente')),
		'actualizacionErronea' => createMessage(array("type" => 2, "message" => 'Hubo un error en la actualización, intentélo nuevamente después de verificar que todos los campos se hayan llenado correctamente')),
		'registroExitoso' => createMessage(array("type" => 1, "message" => 'El registro se realizó correctamente')),
		'registroErroneo' => createMessage(array("type" => 2, "message" => 'Hubo un error en el registro, intentélo nuevamente después de verificar que todos los campos se hayan llenado correctamente')),
		'cambioEstadoExitoso' => createMessage(array("type" => 1, "message" => 'El cambio de estado se realizó correctamente')),
		'cambioEstadoErroneo' => createMessage(array("type" => 2, "message" => 'Hubo un error en el cambio de estado, inténtelo nuevamente')),
		'guardadoMasivoExitoso' => createMessage(array("type" => 1, "message" => 'Los datos se guardaron correctamente')),
		'guardadoMasivoErroneo' => createMessage(array("type" => 2, "message" => 'Hubo un error en el guardado de datos, inténtelo nuevamente después de verificar que todos los campos se hayan ingresado correctamente')),
		'registroConDatosInvalidos' => createMessage(array("type" => 2, "message" => 'Se encontraron incidencias en la operación. Verifique el formulario y vuelvalo a intentar')),
		'listaElementoRepetido' => createMessage(array("type" => 2, "message" => 'Está intentado guardar registros duplicados')),
		'eliminacionExitosa' => createMessage(array("type" => 1, "message" => 'Se ha eliminado el registro correctamente')),
		'registroRepetido' => createMessage(array("type" => 2, "message" => 'Ya existe un registro igual')),
		'email_envio' => '<div style="overflow: auto;font-size:0.7em">
		<hr>
		<p>En caso de que el enlace no funcione, copie y pegue el siguiente link en su navegador: <?= $link ?></p>
		<p style="margin-top:10px;width: 270px;"><strong>Correo Contacto: teamsystem@visualimpact.com.pe</strong></p>
		<p style="margin: 0;float:left;text-align:justify;">Aviso: Este mensaje , así como los archivos adjuntos, ha sido elaborado únicamente para uso de la persona o entidad a la que es remitido, ya que puede contener información confidencial. Si el lector de este mensaje no es el destinatario señalado, le indicamos que cualquier divulgación , retransmisión o copia de esta comunicación, está estrictamente prohibida. Si Usted ha recibido esta comunicación por error , por favor sírvase informarlo de inmediato al remitente del correo electrónico y borrar inmediatamente el mensaje original.<br><br> Finalmente se deja establecido que el mensaje remitido a través de nuestro servidor de correo en caso no se refiera a información con nuestro giro profesional y propósitos del mismo, deberá entenderse como la opinión del remitente, bajo responsabilidad individual de éste, y sin que involucre o comprometa a nuestra organización con dicha opinión o comentario.</p>
		<img src="https://s3.us-central-1.wasabisys.com/impact.business/email/WhatsApp%20Image%202022-06-14%20at%2010.49.59%20AM.jpeg" style="margin-top:10px;width: 150px;float:right;padding: 0.5rem;">
    	</div>',
		'noResultados' => '<div class="alert alert-warning m-3 noResultado" role="alert">
									<i class="fal fa-exclamation-triangle"></i> No se ha generado ningún registro.
								</div>',
		'noRegistros' => '<div class="alert alert-warning m-3 noResultado" role="alert">
								<i class="fal fa-exclamation-triangle"></i> No se ha generado ningún registro con los filtros ingresados.
							</div>',
		'initReporte' => '<div class="alert alert-info m-3 noResultado" role="alert">
								<i class="fas fa-info-circle"></i> Presione <b class="mx-1"><i class="fa fa-search"></i></b> para mostrar resultados.
							</div>',
		'siRegistros' => '<div class="alert alert-success m-3 noResultado" role="alert">
								<i class="fas fa-check"></i> El registro se realizó correctamente.
							</div>',
		'noCuentaProyecto' => '<div class="alert alert-warning m-3 noResultado" role="alert">
                                <i class="fal fa-exclamation-triangle"></i> Seleccione una Cuenta y/o Proyecto para realizar consultas.
                            </div>',
		'custom' => '<div class="' . (!empty($input['class']) ? $input['class'] : '') . '" role="alert">
							<i class="' . (!empty($input['icono']) ? $input['icono'] : '') . '"></i> ' . (!empty($input['message']) ? $input['message'] : '') . '.
						</div>',
		'oops' => '
				<div id="notfound">
					<div class="notfound">
						<div class="notfound-404">  <h1>Oops!</h1> </div>
						<h2>Error - Acceso denegado</h2>
						<p>Asegúrese de estar en el proyecto correcto.</p>
						<a href="../home">Volver al inicio</a>
					</div>
				</div>',
		'alertaPersonalizada' => createMessage(array("type" => 2, "message" => (!empty($input['message']) ? $input['message'] : ''))),
		'exitosoPersonalizado' => createMessage(array("type" => 1, "message" => (!empty($input['message']) ? $input['message'] : ''))),
	];

	return $mensaje[$tipoMensaje];
}

function verificarValidacionesBasicas($elementosAValidar, $datos)
{
	$validaciones = [];
	foreach ($elementosAValidar as $idElemento => $tiposDeValidacion) {
		$validaciones[$idElemento] = [];
		foreach ($tiposDeValidacion as $idTipoValidacion => $tipoDeValidacion) {
			$valor = !empty($datos[$idElemento]) ? $datos[$idElemento] : null;

			switch ($tipoDeValidacion) {
				case 'requerido':
					if (empty(trim($valor))) $validaciones[$idElemento][] = 'Debe de llenar este campo.';
					break;
				case 'numerico':
					if (!is_numeric($valor) && !empty($valor)) $validaciones[$idElemento][] = 'Ingresar un valor númerico.';
					break;
				case 'minimoUnCheck':
					if (empty($valor)) $validaciones[$idElemento][] = 'Debe seleccionar al menos una casilla.';
					break;
				case 'radioRequerido':
					if (empty($valor)) $validaciones[$idElemento][] = 'Debe seleccionar una opción';
					break;
				case 'selectRequerido':
					if (empty($valor)) $validaciones[$idElemento][] = 'Debe seleccionar una opción';
					break;
				case 'claveOchoDigitosAlfanumerico':
					$uppercase = preg_match('@[A-Z]@', $valor);
					$lowercase = preg_match('@[a-z]@', $valor);
					$number    = preg_match('@[0-9]@', $valor);
					if (!$uppercase || !$lowercase || !$number || strlen($valor) < 8) $validaciones[$idElemento][] = 'La clave debe contener mayúsculas, minúsculas, números y debe ser de al menos 8 carácteres de longitud.';
					break;
				case 'email':
					if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) $validaciones[$idElemento][] = 'Debe de ingresar un email válido.';
					break;
			}
		}
	}

	return $validaciones;
}

function verificarValidacionesBasicasMulti($elementosAValidar, $multiDataRefactorizada)
{
	$validaciones = [];
	foreach ($multiDataRefactorizada as $fila => $value) {
		$validaciones[$fila] = verificarValidacionesBasicas($elementosAValidar, $value);
	}
	return $validaciones;
}

function verificarSeCumplenValidaciones($validaciones)
{
	foreach ($validaciones as $key => $value) {
		if (count($value) != 0) return false;
	}
	return true;
}

function verificarSeCumplenValidacionesMulti($validaciones)
{
	foreach ($validaciones as $validacion) {
		if (!verificarSeCumplenValidaciones($validacion)) return false;
	}

	return true;
}

function colsFromArray(array $array, $keys)
{
	if (!is_array($keys)) $keys = [$keys];
	$filter = function ($k) use ($keys) {
		return in_array($k, $keys);
	};
	return array_map(function ($el) use ($keys, $filter) {
		return array_filter($el, $filter, ARRAY_FILTER_USE_KEY);
	}, $array);
}

function filterByKeys($array = [], $keys)
{
	if (!is_array($keys)) $keys = [$keys];
	$filtered = array_filter(
		$array,
		function ($key) use ($keys) {
			return in_array($key, $keys);
		},
		ARRAY_FILTER_USE_KEY
	);
}

function replaceKey($subject, $newKey, $oldKey)
{
	// if the value is not an array, then you have reached the deepest
	// point of the branch, so return the value
	if (!is_array($subject)) return $subject;

	$newArray = array(); // empty array to hold copy of subject
	foreach ($subject as $key => $value) {

		// replace the key with the new key only if it is the old key
		$key = ($key === $oldKey) ? $newKey : $key;

		// add the value with the recursive call
		$newArray[$key] = replaceKey($value, $newKey, $oldKey);
	}
	return $newArray;
}

function agregarSiNoEsta($elemento, $array)
{
	if (!in_array($elemento, $array)) {
		$array[] = $elemento;
	}
}

function getDataRefactorizadaMulti($post)
{
	$multiDataRefactorizada = [];
	foreach ($post as $key => $value) {
		$filaColummna = explode('-', $key);
		if (count($filaColummna) == 2) {
			$columna = $filaColummna[0];
			$fila = $filaColummna[1];
			$multiDataRefactorizada[$fila][$columna] = $value;
		}
	}
	return $multiDataRefactorizada;
}

function validacionesMultiToSimple($validacionesMulti)
{

	$validacionesSimple = [];
	foreach ($validacionesMulti as $fila => $validaciones) {
		foreach ($validaciones as $columna => $mensajes) {
			$validacionesSimple[$columna . '-' . $fila] = $mensajes;
		}
	}

	return $validacionesSimple;
}

function getIp()
{
	return $_SERVER['REMOTE_ADDR'];
}

function formatBytes($bytes, $precision = 2)
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

	$bytes /= pow(1024, $pow);
	// $bytes /= (1 << (10 * $pow));

	return round($bytes, $precision) . ' ' . $units[$pow];
}

function saveBase64Png($base64, $file)
{
	$result = false;
	$base64 = str_replace('data:image/png;base64,', '', $base64);
	$base64 = str_replace(' ', '+', $base64);
	$base64 = base64_decode($base64);
	$result = file_put_contents($file, $base64);
	return $result;
}

function get_ruta_foto($idModulo, $fotoUrl = '')
{

	$ruta_foto = '';

	if ($idModulo == 3) {
		$ruta_foto = 'checklist/';
	} else if ($idModulo == 9) {
		$ruta_foto = 'moduloFotos/';
	} else if ($idModulo == 29 || $idModulo == 1) {
		$ruta_foto = 'encuestas/';

		$sufijo = substr($fotoUrl, -7, 3);
		$ruta_foto = 'sod/';

		if ($sufijo == "VISIB") {
			$ruta_foto = 'Visibilidad/';
		}
	} else if ($idModulo == 7) {
		$ruta_foto = 'seguimientoPlan/';

		$sufijo = substr($fotoUrl, -9, 5);

		if ($sufijo == 'PROMO') {
			$ruta_foto = 'promociones/';
		}
	} else if ($idModulo == 5) {
		$sufijo = substr($fotoUrl, -7, 3);
		$ruta_foto = 'sod/';

		if ($sufijo == "SOS") {
			$ruta_foto = 'sos/';
		}
		if ($sufijo == "VISIB") {
			$ruta_foto = 'Visibilidad/';
		}
	}

	return $ruta_foto;
}

function get_porcentaje($total, $cantidad, $decimales = 2)
{
	if ($cantidad == 0) {
		$percent = 0;
	} else {
		$percent = 100 * ($cantidad / $total);
	}

	return number_format($percent, $decimales, '.', '');
}

function get_fecha_larga($date)
{
	setlocale(LC_TIME, "es_PE.UTF-8");
	$nombreDia = '';
	$nombreMes = '';


	$fecha_ = strtotime($date);
	$dia = date('d', $fecha_);
	$year = date('Y', $fecha_);

	$dateDesc = date('l', $fecha_);

	switch ($dateDesc) {
		case 'Sunday':
			$nombreDia =  "Domingo";
			break;
		case 'Monday':
			$nombreDia =  "Lunes";
			break;
		case 'Tuesday':
			$nombreDia =  "Martes";
			break;
		case 'Wednesday':
			$nombreDia =  "Miércoles";
			break;
		case 'Thursday':
			$nombreDia =  "Jueves";
			break;
		case 'Friday':
			$nombreDia =  "Viernes";
			break;
		case 'Saturday':
			$nombreDia =  "Sábado";
			break;
	}

	$dateDesc = date('n', $fecha_);
	switch ($dateDesc) {
		case 1:
			$nombreMes =  "Enero";
			break;
		case 2:
			$nombreMes =  "Febrero";
			break;
		case 3:
			$nombreMes =  "Marzo";
			break;
		case 4:
			$nombreMes =  "Abril";
			break;
		case 5:
			$nombreMes =  "Mayo";
			break;
		case 6:
			$nombreMes =  "Junio";
			break;
		case 7:
			$nombreMes =  "Julio";
			break;
		case 8:
			$nombreMes =  "Agosto";
			break;
		case 9:
			$nombreMes =  "Setiembre";
			break;
		case 10:
			$nombreMes =  "Octubre";
			break;
		case 11:
			$nombreMes =  "Noviembre";
			break;
		case 12:
			$nombreMes =  "Diciembre";
			break;
	}


	$fechaDescripcion = $nombreDia . ' ' . $dia . ' de ' . $nombreMes . ' del ' . $year;
	return $fechaDescripcion;
}

function generar_espacios($max, $min)
{
	$digitos = strlen($max);
	$min_digitos = strlen($min);

	$espacios = '';

	if ($min_digitos == 3) {

		for ($i = 0; $i < 3; $i++) {
			$espacios .= '&nbsp;';
		}
	} else if ($min_digitos == 2) {

		for ($i = 0; $i < 6; $i++) {
			$espacios .= '&nbsp;';
		}
	} else if ($min_digitos == 1) {

		for ($i = 0; $i < 9; $i++) {
			$espacios .= '&nbsp;';
		}
	}

	return $espacios;
}

function htmlSelectOptionArray($query, $idSelected = '')
{
	$html = '<option value="">-- Seleccione --</option>';
	foreach ($query as $f) {
		if (!empty($idSelected)) {
			if ($f['id'] == $idSelected) {
				$fix = 'selected';
			} else {
				$fix = '';
			}
		} else {
			$fix = '';
		}
		$html .= "<option $fix value='" . $f['id'] . "'>" . $f['value'] . "</option>";
	}

	return $html;
}

function htmlSelectOptionArray2($params = [])
{

	!empty($params['simple']) ?  $html = '' : $html = '<option value="">-- Seleccione --</option>';
	!empty($params['query']) ? $query = $params['query'] : $query = [];
	!empty($params['selected']) ? $idSelected = $params['selected'] : $idSelected = '';
	!empty($params['title']) ? $html = '<option value="">' . $params['title'] . '</option>' : $html = '';
	!empty($params['value']) ? $v = $params['value'] : $v = 'value';
	!empty($params['id']) ? $id = $params['id'] : $id = 'id';
	!empty($params['class']) ? $class = $params['class'] : $class = 'text-uppercase';

	foreach ($query as $f) {
		if (!empty($idSelected) || !empty($params['selectAll'])) {
			if ($f[$id] == $idSelected || !empty($params['selectAll'])) {
				$fix = 'selected';
			} else {
				$fix = '';
			}
		} else {
			$fix = '';
		}

		$idDependiente = ""; $data_option =  '';
		if (!empty($f['idDependiente'])) {
			$idDependiente = " data-parentDependiente='" . $f['idDependiente'] . "' style='display: none;'";
		}
		if(!empty($params['data-option'])){
			foreach($params['data-option'] as $row){
				!empty($f[$row]) ? $data_option .= " data-".$row." = \"".$f[$row]."\"": '';
			}
		}

		if(!empty($f['icono'])){
			$icono = "<i class='{$f['icono']}'></i>";
		}else{
			$icono = '';
		}

		$html .= "<option $data_option class='" . $class . "' $fix value='" . $f[$id] . "'" . $idDependiente . ">" .$icono. strtoupper($f[$v]) . "</option>";
	}

	return $html;
}

function generarCorrelativo($num, $max_cifras)
{

	$cifras = $max_cifras  - (strlen($num));
	$cadena = '';
	for ($i = 0; $i < $cifras; $i++) {
		$cadena .= '0';
	}
	$cadena .= $num;

	return $cadena;
}

function getDataRefactorizada($post, $push_all = array())
{
	$dataRefactorizada = [];

	if (!empty($post[0])) {
		$dataRefactorizada = $post;
	} else {
		foreach ($post as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $key2 => $value2) {
					$dataRefactorizada[$key2][$key] = $value2;
					if (!empty($push_all)) {
						foreach ($push_all as $key3 => $value3) {
							$dataRefactorizada[$key2][$key3] = $value3;
						}
					}
				}
			} else {
				if (!empty($push_all)) {
					foreach ($push_all as $key3 => $push) {
						$dataRefactorizada[0][$key] = $value;
						$dataRefactorizada[0][$key3] = $push;
					}
				} else {
					$dataRefactorizada[0][$key] = $value;
				}
			}
		}
	}

	return $dataRefactorizada;
}

function email($email = array())
{
	$ci = &get_instance();

	$result = false;
	$defaults = array(
		'to' => '',
		'cc' => '',
		'asunto' => '',
		'contenido' => '',
		'adjunto' => ''
	);
	$email += $defaults;

	if (!empty($email['to']) && !empty($email['asunto'])) {

		$ci->load->library('email');

		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'teamsystem@visualimpact.com.pe',
			'smtp_pass' => '#nVi=0sN0ti$',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);

		$ci->email->initialize($config);
		$ci->email->clear(true);

		$ci->email->from('team.sistemas@visualimpact.com.pe', 'VISUAL IMPACT - IMPACTBUSSINESS');
		$ci->email->to($email['to']);

		if (!empty($email['cc'])) {
			$ci->email->cc($email['cc']);
		}
		$bcc = [];
		//$bcc = array('luis.durand@visualimpact.com.pe');
		$ci->email->bcc($bcc);

		$ci->email->subject($email['asunto']);
		$ci->email->message($email['contenido']);
		if (!empty($email['adjunto'])) {
			$ci->email->attach($email['adjunto']);
		}

		if ($ci->email->send()) {
			$result = true;
		}
	}

	return $result;
}

function arrayToString($input = [])
{
	$output = implode(', ', array_filter(array_map(
		function ($v, $k) {
			if (strlen($v) > 0)
				return sprintf("%s = '%s'", $k, $v);
		},
		$input,
		array_keys($input)
	)));

	return $output;
}

function ocultarEmail($email)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		list($first, $last) = explode('@', $email);
		$first = str_replace(substr($first, '3'), str_repeat('*', strlen($first) - 3), $first);
		$last = explode('.', $last, 2);
		$last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0']) - 1), $last['0']);
		$hideEmailAddress = $first . '@' . $last_domain . '.' . $last['1'];
		return $hideEmailAddress;
	}
}

function verificarEmpty($string, $tipo = 1, $txtAntes = '', $txtDespues = '')
{
	$resultado = "";
	if ($tipo == 2) {
		$resultado = 0;
	}
	if ($tipo == 3) {
		$resultado = "-";
	}
	if ($tipo == 4) {
		$resultado = NULL;
	}
	if (!empty($string)) {
		$resultado = $txtAntes . $string . $txtDespues;
	}
	return $resultado;
}
function fileExists($filePath)
{
	return is_file($filePath) && file_exists($filePath);
}

function obtener_carpeta_foto($foto)
{
	$carpetas = ["accionesCotingencia/", "asistencia/", "checklist/", "checklistExhibiciones/", "despachos/", "encartes/", "encuestas/", "encuestasPremio/", "exhibicion/", "incidencias/", "iniciativa/", "inteligencia/", "ipp/", "moduloFotos/", "orden/", "promociones/", "prospeccion/", "reprogramaciones/", "seguimientoPlan/", "sod/", "sos/", "surtido/", "visibilidad/", "visibilidadAuditoria/"];
	$carpeta = '';
	foreach ($carpetas as $v) {
		$url = 'http://movil.visualimpact.com.pe/fotos/impactTrade_android/' . $v . $foto . '';
		if (@getimagesize($url) !== false) {
			$carpeta = $v;
			break;
		}
	}
	return $carpeta . $foto;
}

function ruta_gps($lati_1, $long_1, $lati_2, $long_2)
{
	return ' <a href="javascript:;" style="margin-right:3px;font-size: 15px;" class="lk-show-gps" data-lati-1="' . $lati_1 . '" data-long-1="' . $long_1 . '" data-lati-2="' . $lati_2 . '" data-long-2="' . $long_2 . '" ><i class="fa fa-map-marker" ></i></a> ';
}

function ruta_foto($foto)
{
	return ' <a href="javascript:;" style="margin-right:3px;font-size: 15px;" class="lk-show-foto" data-modulo="premiacion" data-foto="' . $foto . '" ><i class="fa fa-camera" ></i></a> ';
}

function obtener_url_sistema($config = ['uri' => false])
{
	$uri = '';
	if ($config['uri']) {
		$uri = $_SERVER['REQUEST_URI'];
	}
	return sprintf(
		"%s://%s%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		$uri
	);
}

function validar_foto($foto)
{
	$ruta = 'http://movil.visualimpact.com.pe/fotos/impactTrade_android/' . $foto;
	$ruta_controlador = base_url() . 'ControlFoto/obtener_carpeta_foto/' . $foto;

	$retorno = base_url() . 'public/assets/images/sin_imagen.jpg';
	if (@getimagesize($ruta) !== false) {
		$retorno = $ruta_controlador;
	}

	return $retorno;
}
function foto_controlador($foto)
{
	$ruta = 'http://movil.visualimpact.com.pe/fotos/impactTrade_android/' . $foto;
	$ruta_controlador = base_url() . 'ControlFoto/obtener_carpeta_foto/' . $foto;

	return $ruta_controlador;
}

function getClienteHistoricoCuenta()
{
	$ci = &get_instance();
	$idCuenta = $ci->sessIdCuenta;

	$retorno = "{$ci->sessBDCuenta}.trade.cliente_historico";
	// if ($idCuenta == "3") {
	// 	$retorno = "{$ci->sessBDCuenta}.trade.cliente_historico";
	// };
	// if ($idCuenta == "13") {
	// 	$retorno = "{$ci->sessBDCuenta}.trade.cliente_historico";
	// };
	// if ($idCuenta == "2") {
	// 	$retorno = "{$ci->sessBDCuenta}.trade.cliente_historico";
	// };

	return $retorno;
}

function getSegmentacion($input = [])
{
	$result = [];
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');
	$array = [];
	$filtro_permiso = "";

	$gruposCanal = $CI->m_control->get_grupoCanal();
	foreach ($gruposCanal as $key => $row) {
		$array['gruposCanal'][$row['id']] = $row['nombre'];
	}

	if (!empty($input['grupoCanal_filtro'])) {
		$grupoCanal = $array['gruposCanal'][$input['grupoCanal_filtro']];
		$join = '';
		$columnas = [];
		$columnas_bd = '';
		$tiposegmentacion = '';
		$orderBy = '';
		$arreglo_columnas = [];
		if (in_array($grupoCanal, GC_TRADICIONALES)) {
			$tiposegmentacion = 'tradicional';
			$str_permisos = getPermisosUsuario(['segmentacion' => 1]);

			!empty($str_permisos) ? $filtro_permiso .= " AND sctd.idDistribuidoraSucursal IN ({$str_permisos})" : '';
			$join .= " JOIN trade.segmentacionClienteTradicional sct WITH(NOLOCK) ON ch.idSegClienteTradicional = sct.idSegClienteTradicional ";
			$join .= " JOIN trade.segmentacionClienteTradicionalDet sctd WITH(NOLOCK) ON sct.idSegClienteTradicional = sctd.idSegClienteTradicional {$filtro_permiso} ";

			if ($grupoCanal == 'HFS' || $grupoCanal == 'Tradicional') {
				array_push(
					$columnas,
					['header' => 'Distribuidora', 'columna' => 'distribuidora', 'align' => 'left'],
					['header' => 'Sucursal', 'columna' => 'ciudadDistribuidoraSuc', 'align' => 'left'],
					['header' => 'Zona', 'columna' => 'zona', 'align' => 'left']

				); //Columnas para la vista


				//Columnas para la consulta a base de datos
				$columnas_bd  .= '
						, d.nombre AS distribuidora
						, ubi1.provincia AS ciudadDistribuidoraSuc
						, ubi1.cod_ubigeo AS codUbigeoDisitrito
						, ds.idDistribuidoraSucursal
						, z.nombre AS zona
						';
				//Columnas para diferentes usos adicionales
				$arreglo_columnas = ['distribuidora', 'ciudadDistribuidoraSuc', 'codUbigeoDisitrito', 'idDistribuidoraSucursal', 'zona'];
				// JOINS para la consulta a base de datos
				$join .= " LEFT JOIN trade.distribuidoraSucursal ds WITH(NOLOCK) ON ds.idDistribuidoraSucursal = sctd.idDistribuidoraSucursal ";
				$join .= " LEFT JOIN trade.distribuidora d WITH(NOLOCK) ON d.idDistribuidora = ds.idDistribuidora ";
				$join .= " LEFT JOIN General.dbo.ubigeo ubi1 WITH(NOLOCK) ON ubi1.cod_ubigeo=ds.cod_ubigeo";
				$join .= " LEFT JOIN trade.zona z WITH(NOLOCK) ON ch.idZona = z.idZona";

				$orderBy = 'd.nombre,ubi1.provincia';
			};
		}
		if (in_array($grupoCanal, GC_MAYORISTAS)) {
			$tiposegmentacion = 'mayorista';
			$str_permisos = getPermisosUsuario(['segmentacion' => 2]);
			!empty($str_permisos) ? $filtro_permiso .= " AND sct.idPlaza IN ({$str_permisos})" : '';
			$join .= " JOIN trade.segmentacionClienteTradicional sct ON ch.idSegClienteTradicional = sct.idSegClienteTradicional {$filtro_permiso} ";
			$join .= " LEFT JOIN trade.segmentacionClienteTradicionalDet sctd ON sct.idSegClienteTradicional = sctd.idSegClienteTradicional ";

			if ($grupoCanal == 'WHLS') {
				array_push(
					$columnas,
					['header' => 'Plaza', 'columna' => 'plaza', 'align' => 'left']
				);
				$columnas_bd .= '
						, pl.nombre AS plaza
						, pl.idPlaza
						, z.nombre AS zona
						, ds.idDistribuidoraSucursal
						, ubpl.provincia ciudadPlaza
						';
				$arreglo_columnas = ['plaza', 'idPlaza', 'zona', 'idDistribuidoraSucursal', 'ciudadPlaza'];
				$join .= " LEFT JOIN trade.plaza pl WITH(NOLOCK) ON pl.idPlaza = sct.idPlaza";
				$join .= " LEFT JOIN trade.zona z WITH(NOLOCK) ON ch.idZona = z.idZona";
				$join .= " LEFT JOIN trade.distribuidoraSucursal ds WITH(NOLOCK) ON ds.idDistribuidoraSucursal = sctd.idDistribuidoraSucursal ";
				$join .= " LEFT JOIN General.dbo.ubigeo ubpl WITH(NOLOCK) ON ubpl.cod_ubigeo = pl.cod_ubigeo ";

				$orderBy = 'pl.nombre,z.nombre';
			};
		}

		if (in_array($grupoCanal, GC_MODERNOS)) {
			$tiposegmentacion = 'moderno';
			$str_permisos = getPermisosUsuario(['segmentacion' => 3]);
			!empty($str_permisos) ? $filtro_permiso .= " AND scm.idBanner IN ({$str_permisos})" : '';
			$join .= " JOIN trade.segmentacionClienteModerno scm ON ch.idSegClienteModerno = scm.idSegClienteModerno {$filtro_permiso} ";

			if ($grupoCanal == 'HSM' || $grupoCanal == 'Moderno') {
				array_push(
					$columnas,
					['header' => 'Cadena', 'columna' => 'cadena', 'align' => 'left'],
					['header' => 'Banner', 'columna' => 'banner', 'align' => 'left']
				); //Columnas para la vista

				//Columnas para la consulta a base de datos
				$columnas_bd  .= '
						, cad.idCadena
						, ba.idBanner
						, ba.nombre AS banner
						, cad.nombre AS cadena
						';
				$arreglo_columnas = ['idCadena', 'idBanner', 'banner', 'cadena'];
				// JOINS para la consulta a base de datos
				$join .= " LEFT JOIN trade.banner ba WITH(NOLOCK) ON ba.idBanner = scm.idBanner";
				$join .= " LEFT JOIN trade.cadena cad WITH(NOLOCK) ON cad.idCadena = ba.idCadena";

				$orderBy = 'cad.nombre,ba.nombre';
			}
		}
	}

	$result['join'] = !empty($join) ? $join : '';
	$result['headers'] = !empty($columnas) ? $columnas : '';
	$result['columnas_bd'] = !empty($columnas_bd) ? $columnas_bd : '';
	$result['grupoCanal'] = !empty($grupoCanal) ? $grupoCanal : '';
	$result['tipoSegmentacion'] = !empty($tiposegmentacion) ? $tiposegmentacion : '';
	$result['orderBy'] = !empty($orderBy) ? $orderBy : '';
	$result['arreglo_columnas'] = $arreglo_columnas;

	return $result;
}

function getTabPermisos($input = [])
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');

	return $CI->m_control->get_tab_menu_opcion($input);
}
function getPermisosUsuario($params = [])
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');
	$string = '';

	if ($params['segmentacion'] == 1) {
		$rs = $CI->m_control->getPermisosTradicional($params);
		$arr = [];
		foreach ($rs as $k => $v) {
			$arr[] = $v['idDistribuidoraSucursal'];
		}
		$string = implode(",", $arr);
	}
	if ($params['segmentacion'] == 2) {
		$rs = $CI->m_control->getPermisosMayorista($params);
		$arr = [];
		foreach ($rs as $k => $v) {
			$arr[] = $v['idPlaza'];
		}
		$string = implode(",", $arr);
	}
	if ($params['segmentacion'] == 3) {
		$rs = $CI->m_control->getPermisosModerno($params);
		$arr = [];
		foreach ($rs as $k => $v) {
			$arr[] = $v['idBanner'];
		}
		$string = implode(",", $arr);
	}


	return $string;
}

function getColumnasAdicionales($params)
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');

	$idGrupoCanal = !empty($params['idGrupoCanal']) ? $params['idGrupoCanal'] : '';

	$columnas_adicionales_query = $CI->m_control->getColumnasAdicionales(['idModulo' => $params['idModulo'], 'idGrupoCanal' => $idGrupoCanal]);
	$columnas_adicionales = '';
	$headers_adicionales = '';
	$body_adicionales = [];

	if (!empty($columnas_adicionales_query)) {
		foreach ($columnas_adicionales_query as $key => $row) {
			$shortag = !empty($params['shortag']) ? $params['shortag'] : NULL;
			if (empty($shortag)) {
				$shortag = '';
			} else {
				$shortag = $shortag . '.';
			}
			$columnas_adicionales .= ',' . $shortag . $row['columna'];
			$headers_adicionales .= '<th class="text-center align-middle">' . $row['header'] . '</th>';
			$body_adicionales[] = $row['columna'];
		}
	}

	return ['columnas_adicionales' => $columnas_adicionales, 'headers_adicionales' => $headers_adicionales, 'body_adicionales' => $body_adicionales];
}

function getGrupoCanalDeVisita($params)
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');

	$grupoCanal = $CI->m_control->getGrupoCanalDeVisita($params);

	return $grupoCanal['idGrupoCanal'];
}
function rutafotoModulo($input = [])
{
	$modulo = !empty($input['modulo']) ? $input['modulo'] : 'fotos';
	$foto = !empty($input['foto']) ? $input['foto'] : '';
	$icono = !empty($input['icono']) ? $input['icono'] : 'fa fa-camera';

	return ' <a href="javascript:;" style="margin-right:3px;font-size: 15px;" class="lk-show-foto" data-modulo="' . $modulo . '" data-foto="' . $foto . '" ><i class="' . $icono . '" ></i></a> ';
}

function getDistanciaMaps($input = [])
{
	$km = rad2deg(acos((sin(deg2rad($input['lat1'])) * sin(deg2rad($input['lat2']))) + (cos(deg2rad($input['lat1'])) * cos(deg2rad($input['lat2'])) * cos(deg2rad($input['long1'] - $input['long2'])))));
	return $km;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
	if (($lat1 == $lat2) && ($lon1 == $lon2)) {
		return 0;
	} else {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
}
function kmtom($km)
{
	$m = $km * 1000;
	$result = '';
	if ($m >= 1000) {
		$result = round($m / 1000, 2) . ' km';
	} else {
		$result = round($m, 2) . ' m';
	}

	return $result;
}
function permisos_usuarios_multi($input = [])
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');

	$array['usuarios'] = [];
	foreach ($input['tipoSegmentacion'] as $k => $tipo) {
		$tipoSegmentacion = $tipo;
		if ($tipoSegmentacion == 'tradicional') {
			$permisos_usuarios = $CI->m_control->obtenerUsuariosPermisosDistribuidoraSucursal($input);
			foreach ($permisos_usuarios as $k => $v) {
				$array['usuarios']['tradicional'][$v['idTipoUsuario']][$v['idDistribuidoraSucursal']] = $v['nombreUsuario'];
			}
		}
		if ($tipoSegmentacion == 'mayorista') {
			$permisos_usuarios = $CI->m_control->obtenerUsuariosPermisosPlaza($input);
			foreach ($permisos_usuarios as $k => $v) {
				$array['usuarios']['mayorista'][$v['idTipoUsuario']][$v['idPlaza']] = $v['nombreUsuario'];
			}
		}
		if ($tipoSegmentacion == 'moderno') {
			$permisos_usuarios = $CI->m_control->obtenerUsuariosPermisosBanner($input);
			foreach ($permisos_usuarios as $k => $v) {
				$array['usuarios']['moderno'][$v['idTipoUsuario']][$v['idBanner']] = $v['nombreUsuario'];
			}
		}
	}

	return $array['usuarios'];
}
function permisos_usuarios($tipoSegmentacion = '', $input = [])
{
	$CI = &get_instance();
	$CI->load->model('M_control', 'm_control');

	$array['usuarios'] = [];
	if ($tipoSegmentacion == 'tradicional') {
		$permisos_usuarios = $CI->m_control->obtenerUsuariosPermisosDistribuidoraSucursal($input);
		foreach ($permisos_usuarios as $k => $v) {
			$array['usuarios']['tradicional'][$v['idTipoUsuario']][$v['idDistribuidoraSucursal']] = $v['nombreUsuario'];
		}
	} else if ($tipoSegmentacion == 'mayorista') {
		$permisos_usuarios = $CI->m_control->obtenerUsuariosPermisosPlaza($input);
		foreach ($permisos_usuarios as $k => $v) {
			$array['usuarios']['mayorista'][$v['idTipoUsuario']][$v['idPlaza']] = $v['nombreUsuario'];
		}
	} else if ($tipoSegmentacion == 'moderno') {
		$permisos_usuarios = $CI->m_control->obtenerUsuariosPermisosBanner($input);
		foreach ($permisos_usuarios as $k => $v) {
			$array['usuarios']['moderno'][$v['idTipoUsuario']][$v['idBanner']] = $v['nombreUsuario'];
		}
	}

	return $array['usuarios'];
}
function segmentacion_usuarios($usuarios = [], $row = [])
{
	//Funcion para la segmentación de los usuarios Supervisor, Ejecutivo y Coordinador
	$retorno = [];

	$grupoCanal = $row['grupoCanal'];

	if (in_array($grupoCanal, GC_TRADICIONALES)) {
		$ejecutivo = !empty($usuarios['tradicional'][11][$row['idDistribuidoraSucursal']]) ? $usuarios['tradicional'][11][$row['idDistribuidoraSucursal']] : ' - ';
		$supervisor = !empty($usuarios['tradicional'][2][$row['idDistribuidoraSucursal']]) ? $usuarios['tradicional'][2][$row['idDistribuidoraSucursal']] : ' - ';
		$coordinador = !empty($usuarios['tradicional'][17][$row['idDistribuidoraSucursal']]) ? $usuarios['tradicional'][17][$row['idDistribuidoraSucursal']] : ' - ';
	} else if (in_array($grupoCanal, GC_MAYORISTAS)) {
		$ejecutivo = !empty($usuarios['mayorista'][11][$row['idPlaza']]) ? $usuarios['mayorista'][11][$row['idPlaza']] : ' - ';
		$supervisor = !empty($usuarios['mayorista'][2][$row['idPlaza']]) ? $usuarios['mayorista'][2][$row['idPlaza']] : ' - ';
		$coordinador = !empty($usuarios['mayorista'][17][$row['idPlaza']]) ? $usuarios['mayorista'][17][$row['idPlaza']] : ' - ';
	} else if (in_array($grupoCanal, GC_MODERNOS)) {
		$ejecutivo = !empty($usuarios['moderno'][11][$row['idBanner']]) ? $usuarios['moderno'][11][$row['idBanner']] : ' - ';
		$supervisor = !empty($usuarios['moderno'][2][$row['idBanner']]) ? $usuarios['moderno'][2][$row['idBanner']] : ' - ';
		$coordinador = !empty($usuarios['moderno'][17][$row['idBanner']]) ? $usuarios['moderno'][17][$row['idBanner']] : ' - ';
	} else {
		$ejecutivo = '-';
		$supervisor = '-';
		$coordinador = '-';
	}

	$retorno['ejecutivo'] = $ejecutivo; //Gerente Zonal
	$retorno['supervisor'] = $supervisor;
	$retorno['coordinador'] = $coordinador; //Coordinador Zonal

	return $retorno;
}

function difMin($horaini, $horafin)
{
	$horai = substr($horaini, 0, 2);
	$mini = substr($horaini, 3, 2);
	$segi = substr($horaini, 6, 2);

	$horaf = substr($horafin, 0, 2);
	$minf = substr($horafin, 3, 2);
	$segf = substr($horafin, 6, 2);

	$ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
	$fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

	$dif = $fin - $ini;

	$array['difh'] = $difh = floor($dif / 3600);
	$array['difm'] = $difm = floor(($dif - ($difh * 3600)) / 60);
	$array['difs'] = $difs = $dif - ($difm * 60) - ($difh * 3600);
	//
	return $array;
}

function execInBackground($cmd = "", $input = [])
{
	$idCuenta = !empty($input['idCuenta']) ? $input['idCuenta'] : '';
	$idProyecto = !empty($input['idProyecto']) ? $input['idProyecto'] : '';

	if (substr(php_uname(), 0, 7) == "Windows") {
		pclose(popen("start /B " . $cmd . " " . $idCuenta . " " . $idProyecto, "r"));
	} else {
		exec($cmd . " " . $idCuenta . " > /dev/null &");
	}
}

function textopropio($text)
{
	return ucwords(mb_strtolower($text, 'UTF-8'));
}

function checkAndConvertToArray($variable)
{
	$resultado = $variable;
	if (!is_array($variable)) {
		$resultado = [$variable];
	}

	return $resultado;
}

function validarTiempoLimite($tiempoInicio, $tiempoFinal)
{
	$tiempoInicio = new \DateTime($tiempoInicio);
	$tiempoFinal   = new \DateTime($tiempoFinal);

	$diferencia = (array) $tiempoFinal->diff($tiempoInicio);

	if ($diferencia['y'] > 0 || $diferencia['m'] > 0 || $diferencia['d'] > 0 || $diferencia['h'] > 0) {
		return false;
	}

	if ($diferencia['i'] > 5) {
		return false;
	} else {
		return true;
	}
}

function costoUnitario($cantidad, $importe, $decimales = 2)
{
    $costo = round(($importe / $cantidad), 2);
    return $costo;
}

function moneyToText($params = []){

	$moneda = !empty($params['moneda']) ? $params['moneda'] : '';

	$CI = &get_instance();
	$CI->load->library('NumeroALetras');
	$CI->load->library('S3');

	$formatter = $CI->numeroaletras;
	$numero = $params['numero'];
	// return $formatter->toMoney(10.10, 2, 'SOLES', 'CENTIMOS');
	return $formatter->toInvoice($numero, 2, $moneda);

}

function completarFilasPdf($params){

	$filas = count($params['data']);
	$filasRequeridas = $params['filas'];
	$filasAdicionales = !empty($params['filasAdicionales']) ? ($params['filasAdicionales'] + $filas) : 0;
	$columnas = $params['columnas'];

	$table = '';
	for($i = $filas; $i < $filasRequeridas ; $i++){
		$table .= '<tr>';
			for ($j=0; $j < $columnas ; $j++) {
				$table .= '<td class="text-center">';
					if($j == 0 ){
						//Solo ponemos el numero de fila si es la primera columna
						$table .= ($i + 1);
					}
				$table .= '</td>';
			}
		$table .= '</tr>';
	}
	for($x = $filas; $x < $filasAdicionales ; $x++){
		$table .= '<tr>';
			for ($y=0; $y < $columnas ; $y++) {
				$table .= '<td class="text-center">';
					if($y == 0 ){
						//Solo ponemos el numero de fila si es la primera columna
						$table .= ($x + 1);
					}
				$table .= '</td>';
			}
		$table .= '</tr>';
	}

	return $table;
}

function encrypt($string) {
	$method = 'aes-256-cbc';
	$iv = base64_decode("C8fBxl1g7EWtYTL1/M8jfstw==");
	
	return openssl_encrypt($string, $method, SECRET_KEY_GET, false, $iv);
 }

function decrypt($string) {
	$method = 'aes-256-cbc';
	$iv = base64_decode("C8fBxl1g7EWtYTL1/M8jfstw==");
	
	return openssl_decrypt($string, $method, SECRET_KEY_GET, false, $iv);
 }

class Encriptar {

    static $key = 'EnCRypT10nK#Y!RiSRNn';

    static function codificar($dato) {
        $resultado = $dato;
        $arrayLetras = self::$key;
        $limite = strlen($arrayLetras) - 1;
        $num = mt_rand(3,5);
        for ($i = 1; $i <= $num; $i++) {
            $resultado = base64_encode($resultado);
        }
        $resultado = $resultado . '+' . $arrayLetras[$num];
        $resultado = base64_encode($resultado);
        return $resultado;
    }

    static function decodificar($dato) {
        $resultado = base64_decode($dato);
        list($resultado, $letra) = explode('+', $resultado);
        $arrayLetras = self::$key;
        for ($i = 0; $i < strlen($arrayLetras); $i++) {
            if ($arrayLetras[$i] == $letra) {
                for ($j = 1; $j <= $i; $j++) {
                    $resultado = base64_decode($resultado);
                }
                break;
            }
        }
        return $resultado;
    }

}
