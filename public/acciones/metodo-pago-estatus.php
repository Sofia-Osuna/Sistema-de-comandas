<?php
// public/acciones/metodo-pago-estatus.php
// Baja lógica / reactivación. Recibe el estatus al que se quiere llegar ('0' o '1').
require_once __DIR__ . '/../../includes/acciones-metodos-pago.php';

$pk      = (int) post_texto('pk');
$estatus = post_texto('estatus');

redirigir($modelo->cambiarEstatus($pk, $estatus, $idUsuario, $ip));
