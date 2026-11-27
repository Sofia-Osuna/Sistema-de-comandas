<?php
// public/acciones/metodo-pago-guardar.php
// Crea o edita un método de pago (según pk). Solo procesa y redirige, no tiene diseño.
require_once __DIR__ . '/../../includes/acciones-metodos-pago.php';

$pk      = (int) post_texto('pk');
$metodo  = post_texto('metodo');
$estatus = isset($_POST['estatus']) ? '1' : '0';

$resultado = $pk > 0
    ? $modelo->actualizar($pk, $metodo, $estatus, $idUsuario, $ip)
    : $modelo->crear($metodo, $estatus, $idUsuario, $ip);

if (!$resultado['ok'] && ($resultado['campo'] ?? '') === 'metodo') {
    // Error de validación: la página vuelve a abrir el modal con lo que escribió la persona.
    $resultado['form'] = ['pk' => $pk, 'metodo' => trim($metodo), 'estatus' => $estatus];
}

redirigir($resultado);
