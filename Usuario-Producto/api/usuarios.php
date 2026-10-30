<?php
/**
 * API REST: Módulo de Usuarios
 * Retorna exactamente los 5 campos de un usuario:
 * 1. id
 * 2. nombre
 * 3. email
 * 4. telefono
 * 5. direccion
 *
 * Cumple con el requerimiento:
 * "un usuario va a tener 5 campos y si nos metemos en su parte nomas vamos a ver ese usuario"
 */

if (!headers_sent()) {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

// Respuesta pre-flight para navegadores y Postman
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
        // Consultar un usuario específico por ID (Muestra únicamente los datos de ese usuario)
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $con->prepare("SELECT id, nombre, email, telefono, direccion FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "mensaje" => "Usuario encontrado exitosamente",
                    "total_campos" => 5,
                    "campos" => ["id", "nombre", "email", "telefono", "direccion"],
                    "data" => [
                        "id" => intval($user['id']),
                        "nombre" => $user['nombre'],
                        "email" => $user['email'],
                        "telefono" => $user['telefono'],
                        "direccion" => $user['direccion']
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Usuario con ID $id no fue encontrado"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            $stmt->close();
        } else {
            // Consultar lista general de usuarios (cada uno con sus 5 campos)
            $sql = "SELECT id, nombre, email, telefono, direccion FROM usuarios ORDER BY id ASC";
            $result = $con->query($sql);
            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = [
                    "id" => intval($row['id']),
                    "nombre" => $row['nombre'],
                    "email" => $row['email'],
                    "telefono" => $row['telefono'],
                    "direccion" => $row['direccion']
                ];
            }

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "total_registros" => count($users),
                "campos_por_usuario" => 5,
                "data" => $users
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        // Registrar un nuevo usuario (5 campos: id es autoincremental, nombre, email, telefono, direccion)
        $nombre = trim($input['nombre'] ?? $_POST['nombre'] ?? '');
        $email = trim($input['email'] ?? $_POST['email'] ?? '');
        $telefono = trim($input['telefono'] ?? $_POST['telefono'] ?? '');
        $direccion = trim($input['direccion'] ?? $_POST['direccion'] ?? '');

        if (empty($nombre) || empty($email) || empty($telefono) || empty($direccion)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Todos los campos son obligatorios: nombre, email, telefono, direccion"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "El formato del correo electrónico es inválido"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        $stmt = $con->prepare("INSERT INTO usuarios (nombre, email, telefono, direccion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $telefono, $direccion);

        if ($stmt->execute()) {
            $new_id = $con->insert_id;
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Usuario creado exitosamente",
                "data" => [
                    "id" => $new_id,
                    "nombre" => $nombre,
                    "email" => $email,
                    "telefono" => $telefono,
                    "direccion" => $direccion
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(409);
            echo json_encode([
                "status" => "error",
                "message" => "Error al registrar el usuario: " . $stmt->error
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'PUT':
        // Actualizar usuario existente
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);
        $nombre = trim($input['nombre'] ?? '');
        $email = trim($input['email'] ?? '');
        $telefono = trim($input['telefono'] ?? '');
        $direccion = trim($input['direccion'] ?? '');

        if ($id <= 0 || empty($nombre) || empty($email) || empty($telefono) || empty($direccion)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Campos obligatorios requeridos: id, nombre, email, telefono, direccion"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        $stmt = $con->prepare("UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0 || $con->affected_rows === 0) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Usuario actualizado exitosamente",
                    "data" => [
                        "id" => $id,
                        "nombre" => $nombre,
                        "email" => $email,
                        "telefono" => $telefono,
                        "direccion" => $direccion
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Usuario no encontrado para actualizar"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar: " . $stmt->error
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Eliminar usuario por ID (URL ?id=X o body JSON {"id": X})
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Se requiere un parámetro 'id' numérico válido para eliminar"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        $stmt = $con->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Usuario y sus productos asociados eliminados exitosamente",
                    "usuario_id_eliminado" => $id
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Usuario no encontrado"
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