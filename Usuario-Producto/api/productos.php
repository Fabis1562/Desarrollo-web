<?php
/**
 * API REST: Módulo de Productos
 * Los productos tienen exactamente 5 campos:
 * 1. id
 * 2. nombre
 * 3. precio
 * 4. stock
 * 5. usuario_id (vinculado al cliente)
 *
 * Cumple con el requerimiento:
 * "y si es en productos son 5 campos y se va a mostrar los productos de el cliente y los campos del cliente"
 */

if (!headers_sent()) {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/connection.php";
$con = connection();

$method = $_SERVER['REQUEST_METHOD'];
$input_raw = file_get_contents("php://input");
$input = json_decode($input_raw, true) ?? [];

switch ($method) {
    case 'GET':
        // CASO 1: Filtrar productos por Cliente / Usuario (?usuario_id=X o ?cliente_id=X)
        // Muestra los campos del cliente Y todos los productos asociados a ese cliente.
        $filtro_usuario = null;
        if (isset($_GET['usuario_id']) && is_numeric($_GET['usuario_id'])) {
            $filtro_usuario = intval($_GET['usuario_id']);
        } elseif (isset($_GET['cliente_id']) && is_numeric($_GET['cliente_id'])) {
            $filtro_usuario = intval($_GET['cliente_id']);
        }

        if ($filtro_usuario !== null) {
            // 1. Obtener los 5 campos del cliente
            $stmt_u = $con->prepare("SELECT id, nombre, email, telefono, direccion FROM usuarios WHERE id = ?");
            $stmt_u->bind_param("i", $filtro_usuario);
            $stmt_u->execute();
            $res_u = $stmt_u->get_result();

            if ($cliente = $res_u->fetch_assoc()) {
                // 2. Obtener los productos asociados a este cliente (5 campos cada uno)
                $stmt_p = $con->prepare("SELECT id, nombre, precio, stock, usuario_id FROM productos WHERE usuario_id = ? ORDER BY id ASC");
                $stmt_p->bind_param("i", $filtro_usuario);
                $stmt_p->execute();
                $res_p = $stmt_p->get_result();

                $productos = [];
                $vista_join = [];

                while ($prod = $res_p->fetch_assoc()) {
                    $prod_data = [
                        "id" => intval($prod['id']),
                        "nombre" => $prod['nombre'],
                        "precio" => floatval($prod['precio']),
                        "stock" => intval($prod['stock']),
                        "usuario_id" => intval($prod['usuario_id'])
                    ];
                    $productos[] = $prod_data;

                    // Formato relacional combinado plano (JOIN)
                    $vista_join[] = [
                        "producto_id" => intval($prod['id']),
                        "producto_nombre" => $prod['nombre'],
                        "producto_precio" => floatval($prod['precio']),
                        "producto_stock" => intval($prod['stock']),
                        "cliente_id" => intval($cliente['id']),
                        "cliente_nombre" => $cliente['nombre'],
                        "cliente_email" => $cliente['email'],
                        "cliente_telefono" => $cliente['telefono'],
                        "cliente_direccion" => $cliente['direccion']
                    ];
                }

                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "mensaje" => "Productos y datos del cliente recuperados exitosamente",
                    "cliente" => [
                        "id" => intval($cliente['id']),
                        "nombre" => $cliente['nombre'],
                        "email" => $cliente['email'],
                        "telefono" => $cliente['telefono'],
                        "direccion" => $cliente['direccion']
                    ],
                    "total_productos" => count($productos),
                    "campos_producto" => ["id", "nombre", "precio", "stock", "usuario_id"],
                    "productos" => $productos,
                    "vista_relacional_join" => $vista_join
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                $stmt_p->close();
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "El cliente con ID $filtro_usuario no existe en la base de datos"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            $stmt_u->close();
            break;
        }

        // CASO 2: Consultar un producto específico por su ID (?id=X)
        // Muestra los 5 campos del producto y los 5 campos de su cliente asociado
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT 
                        p.id AS prod_id, p.nombre AS prod_nombre, p.precio AS prod_precio, p.stock AS prod_stock, p.usuario_id AS prod_usuario_id,
                        u.id AS cli_id, u.nombre AS cli_nombre, u.email AS cli_email, u.telefono AS cli_telefono, u.direccion AS cli_direccion
                    FROM productos p
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE p.id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($row = $res->fetch_assoc()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "mensaje" => "Detalle del producto y su cliente asociado",
                    "producto" => [
                        "id" => intval($row['prod_id']),
                        "nombre" => $row['prod_nombre'],
                        "precio" => floatval($row['prod_precio']),
                        "stock" => intval($row['prod_stock']),
                        "usuario_id" => intval($row['prod_usuario_id'])
                    ],
                    "cliente" => [
                        "id" => intval($row['cli_id']),
                        "nombre" => $row['cli_nombre'],
                        "email" => $row['cli_email'],
                        "telefono" => $row['cli_telefono'],
                        "direccion" => $row['cli_direccion']
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Producto con ID $id no fue encontrado"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            $stmt->close();
            break;
        }

        // CASO 3: Listar TODOS los productos con los datos de sus clientes respectivos (INNER JOIN)
        $sql = "SELECT 
                    p.id AS prod_id, p.nombre AS prod_nombre, p.precio AS prod_precio, p.stock AS prod_stock, p.usuario_id AS prod_usuario_id,
                    u.id AS cli_id, u.nombre AS cli_nombre, u.email AS cli_email, u.telefono AS cli_telefono, u.direccion AS cli_direccion
                FROM productos p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.id ASC";
        $res = $con->query($sql);
        $items = [];

        while ($row = $res->fetch_assoc()) {
            $items[] = [
                "producto" => [
                    "id" => intval($row['prod_id']),
                    "nombre" => $row['prod_nombre'],
                    "precio" => floatval($row['prod_precio']),
                    "stock" => intval($row['prod_stock']),
                    "usuario_id" => intval($row['prod_usuario_id'])
                ],
                "cliente" => [
                    "id" => intval($row['cli_id']),
                    "nombre" => $row['cli_nombre'],
                    "email" => $row['cli_email'],
                    "telefono" => $row['cli_telefono'],
                    "direccion" => $row['cli_direccion']
                ]
            ];
        }

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "total_registros" => count($items),
            "descripcion" => "Listado general de productos mostrando cada producto con los campos del cliente correspondiente",
            "data" => $items
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;

    case 'POST':
        // Registrar un nuevo producto vinculado a un cliente
        // Campos requeridos: nombre, precio, stock, usuario_id (o cliente_id)
        $nombre = trim($input['nombre'] ?? $_POST['nombre'] ?? '');
        $precio = isset($input['precio']) ? floatval($input['precio']) : (isset($_POST['precio']) ? floatval($_POST['precio']) : null);
        $stock = isset($input['stock']) ? intval($input['stock']) : (isset($_POST['stock']) ? intval($_POST['stock']) : null);
        $usuario_id = intval($input['usuario_id'] ?? $input['cliente_id'] ?? $_POST['usuario_id'] ?? $_POST['cliente_id'] ?? 0);

        if (empty($nombre) || $precio === null || $precio < 0 || $stock === null || $stock < 0 || $usuario_id <= 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Campos obligatorios requeridos: nombre, precio (positivo), stock (positivo), usuario_id (ID de cliente válido)"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        // Verificar que el usuario exista
        $stmt_check = $con->prepare("SELECT id, nombre, email, telefono, direccion FROM usuarios WHERE id = ?");
        $stmt_check->bind_param("i", $usuario_id);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();

        if (!($cliente = $res_check->fetch_assoc())) {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "No existe un cliente con el ID $usuario_id proporcionado"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $stmt_check->close();
            break;
        }
        $stmt_check->close();

        $stmt = $con->prepare("INSERT INTO productos (nombre, precio, stock, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdii", $nombre, $precio, $stock, $usuario_id);

        if ($stmt->execute()) {
            $new_prod_id = $con->insert_id;
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Producto registrado exitosamente",
                "producto" => [
                    "id" => $new_prod_id,
                    "nombre" => $nombre,
                    "precio" => $precio,
                    "stock" => $stock,
                    "usuario_id" => $usuario_id
                ],
                "cliente_asociado" => [
                    "id" => intval($cliente['id']),
                    "nombre" => $cliente['nombre'],
                    "email" => $cliente['email'],
                    "telefono" => $cliente['telefono'],
                    "direccion" => $cliente['direccion']
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error al registrar producto: " . $stmt->error
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'PUT':
        // Actualizar producto
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);
        $nombre = trim($input['nombre'] ?? '');
        $precio = isset($input['precio']) ? floatval($input['precio']) : null;
        $stock = isset($input['stock']) ? intval($input['stock']) : null;
        $usuario_id = intval($input['usuario_id'] ?? $input['cliente_id'] ?? 0);

        if ($id <= 0 || empty($nombre) || $precio === null || $precio < 0 || $stock === null || $stock < 0 || $usuario_id <= 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Datos incompletos o inválidos: id, nombre, precio, stock, usuario_id son obligatorios"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        // Verificar que el usuario exista
        $stmt_check = $con->prepare("SELECT id FROM usuarios WHERE id = ?");
        $stmt_check->bind_param("i", $usuario_id);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows === 0) {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "El usuario_id $usuario_id no existe"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $stmt_check->close();
            break;
        }
        $stmt_check->close();

        $stmt = $con->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ?, usuario_id = ? WHERE id = ?");
        $stmt->bind_param("sdiii", $nombre, $precio, $stock, $usuario_id, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0 || $con->affected_rows === 0) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Producto actualizado exitosamente",
                    "producto" => [
                        "id" => $id,
                        "nombre" => $nombre,
                        "precio" => $precio,
                        "stock" => $stock,
                        "usuario_id" => $usuario_id
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Producto no encontrado para actualizar"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar producto: " . $stmt->error
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Eliminar producto por ID
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Se requiere un parámetro 'id' numérico válido para eliminar el producto"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        $stmt = $con->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Producto eliminado exitosamente",
                    "producto_id_eliminado" => $id
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Producto no encontrado"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error al eliminar: " . $stmt->error
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "message" => "Método HTTP no permitido. Métodos válidos: GET, POST, PUT, DELETE"
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
}
?>