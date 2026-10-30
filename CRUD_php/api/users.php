<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejo de pre-flight request para navegadores y clientes HTTP
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "../connection.php";
$con = connection();

$method = $_SERVER['REQUEST_METHOD'];

// Para recibir datos enviados en formato JSON en el Body (POST, PUT, DELETE)
$input_raw = file_get_contents("php://input");
$input = json_decode($input_raw, true) ?? [];

switch ($method) {
    case 'GET':
        // Consultar un usuario específico por ID: ?id=1
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $con->prepare("SELECT id, name, lastname, username, email FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                http_response_code(200);
                echo json_encode(["status" => "success", "data" => $user]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
            }
            $stmt->close();
        } else {
            // Consultar todos los usuarios
            $sql = "SELECT id, name, lastname, username, email FROM users";
            $result = $con->query($sql);
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            http_response_code(200);
            echo json_encode(["status" => "success", "data" => $users]);
        }
        break;

    case 'POST':
        // Crear un nuevo usuario (acepta JSON o form-data)
        $name = trim($input['name'] ?? $_POST['name'] ?? '');
        $lastname = trim($input['lastname'] ?? $_POST['lastname'] ?? '');
        $username = trim($input['username'] ?? $_POST['username'] ?? '');
        $email = trim($input['email'] ?? $_POST['email'] ?? '');
        $password = $input['password'] ?? $_POST['password'] ?? '';

        if (empty($name) || empty($lastname) || empty($username) || empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios (name, lastname, username, email, password)"]);
            break;
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO users (name, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $lastname, $username, $email, $password_hashed);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Usuario creado exitosamente",
                "user_id" => $con->insert_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error al guardar el usuario: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'PUT':
        // Actualizar usuario existente
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);
        $name = trim($input['name'] ?? '');
        $lastname = trim($input['lastname'] ?? '');
        $username = trim($input['username'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($id <= 0 || empty($name) || empty($lastname) || empty($username) || empty($email)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios (id, name, lastname, username, email)"]);
            break;
        }

        // Si se provee nueva contraseña se actualiza con hash, sino se mantiene la existente
        if (!empty($password)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare("UPDATE users SET name = ?, lastname = ?, username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $name, $lastname, $username, $email, $password_hashed, $id);
        } else {
            $stmt = $con->prepare("UPDATE users SET name = ?, lastname = ?, username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $lastname, $username, $id);
        }

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Usuario actualizado exitosamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error al actualizar: " . $stmt->error]);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Eliminar usuario por ID (desde URL ?id=X o desde JSON body {"id": X})
        $id = intval($input['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Debe proporcionar un ID válido"]);
            break;
        }

        $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Usuario eliminado exitosamente"]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Error al eliminar: " . $stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Método HTTP no permitido"]);
        break;
}

$con->close();
?>

