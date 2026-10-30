<?php 
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");
$con = connection();

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM alumnos WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $con->close();
        header("Location: index.php");
        exit();
    } else {
        echo "<h3>Error al eliminar alumno:</h3> " . $stmt->error;
        $stmt->close();
        $con->close();
    }
} else {
    $con->close();
    header("Location: index.php");
    exit();
}
?>
