<?php
echo "=== PRUEBA 1: GET /api/usuarios.php?id=1 ===\n";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET = ['id' => 1];
ob_start();
require __DIR__ . '/api/usuarios.php';
$res = ob_get_clean();
echo $res . "\n\n";

echo "=== PRUEBA 2: GET /api/productos.php?usuario_id=1 ===\n";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET = ['usuario_id' => 1];
ob_start();
require __DIR__ . '/api/productos.php';
$res2 = ob_get_clean();
echo $res2 . "\n\n";

echo "=== PRUEBA 3: GET /api/productos.php?id=1 ===\n";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET = ['id' => 1];
ob_start();
require __DIR__ . '/api/productos.php';
$res3 = ob_get_clean();
echo $res3 . "\n\n";
?>
