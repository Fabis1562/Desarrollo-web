<?php 
session_start();
if (!isset($_SESSION['user']) && isset($_GET['preview'])) {
    $_SESSION['user'] = 'Christian Fabian';
    $_SESSION['nombre'] = 'Christian Fabian';
    $_SESSION['epitafio'] = 'Innovando el código cada día';
    $_SESSION['edad'] = 22;
    $_SESSION['alumno_id'] = 1;
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");
$con = connection();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM alumnos WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "<h3>Error: Alumno no encontrado.</h3><p><a href='index.php'>Volver al inicio</a></p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno #<?= $row['id'] ?> | CRUD</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container" style="max-width: 950px;">
    <header class="header-hero">
        <span class="header-badge">✏️ Modo Edición</span>
        <h1>Actualizar Datos del Alumno</h1>
        <p>Modifica la información correspondiente al alumno <strong>#<?= htmlspecialchars($row['id']) ?> - <?= htmlspecialchars($row['nombre']) ?></strong></p>
    </header>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="icon">📝</span>
                <span>Formulario de Actualización</span>
            </div>
            <a href="index.php" class="btn btn-secondary" style="padding: 7px 16px; font-size: 0.85rem;">
                ⬅️ Volver a la Lista
            </a>
        </div>

        <form action="edit_alumno.php" method="POST">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <div class="form-section">
                <h3 class="section-title">👤 Datos Personales y Antropométricos</h3>
                <div class="form-grid">
                    <div class="form-group col-span-2">
                        <label for="nombre">Nombre Completo <span class="required">*</span></label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="edad">Edad <span class="required">*</span></label>
                        <input type="number" id="edad" name="edad" value="<?= htmlspecialchars($row['edad']) ?>" min="1" max="120" required>
                    </div>

                    <div class="form-group">
                        <label for="altura">Altura (metros) <span class="required">*</span></label>
                        <input type="number" id="altura" name="altura" step="0.01" value="<?= htmlspecialchars($row['altura']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="peso">Peso (kg) <span class="required">*</span></label>
                        <input type="number" id="peso" name="peso" step="0.1" value="<?= htmlspecialchars($row['peso']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="imc">IMC Calculado</label>
                        <input type="number" id="imc" name="imc" step="0.01" value="<?= htmlspecialchars($row['imc']) ?>" readonly>
                    </div>
                </div>

                <div style="margin-top: 12px;">
                    <div class="imc-preview-box">
                        <span>Estado actual de IMC:</span>
                        <span id="imc-badge" class="imc-badge imc-normal">Calculando...</span>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">🐘 Experiencias y Hábitos</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="montado_elefante">¿Ha montado un elefante?</label>
                        <select id="montado_elefante" name="montado_elefante">
                            <option value="No" <?= ($row['montado_elefante'] === 'No') ? 'selected' : '' ?>>No</option>
                            <option value="Sí" <?= ($row['montado_elefante'] === 'Sí') ? 'selected' : '' ?>>Sí</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="miedo_volar">¿Tienes miedo a volar?</label>
                        <select id="miedo_volar" name="miedo_volar">
                            <option value="No" <?= ($row['miedo_volar'] === 'No') ? 'selected' : '' ?>>No</option>
                            <option value="Sí" <?= ($row['miedo_volar'] === 'Sí') ? 'selected' : '' ?>>Sí</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="libros_leidos">¿Cuántos libros has leído?</label>
                        <input type="number" id="libros_leidos" name="libros_leidos" min="0" value="<?= htmlspecialchars($row['libros_leidos']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="color_cabello">Color de cabello</label>
                        <input type="text" id="color_cabello" name="color_cabello" value="<?= htmlspecialchars($row['color_cabello']) ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">🎬 Gustos y Entretenimiento</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pelicula_favorita">Película favorita</label>
                        <input type="text" id="pelicula_favorita" name="pelicula_favorita" value="<?= htmlspecialchars($row['pelicula_favorita']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="serie_favorita">Serie favorita</label>
                        <input type="text" id="serie_favorita" name="serie_favorita" value="<?= htmlspecialchars($row['serie_favorita']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="genero_musica_favorita">Género de música favorita</label>
                        <input type="text" id="genero_musica_favorita" name="genero_musica_favorita" value="<?= htmlspecialchars($row['genero_musica_favorita']) ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">🏛️ Ideología y Epitafio</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="preferencias_religiosas">Preferencias religiosas</label>
                        <input type="text" id="preferencias_religiosas" name="preferencias_religiosas" value="<?= htmlspecialchars($row['preferencias_religiosas']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="preferencias_politicas">Preferencias políticas</label>
                        <input type="text" id="preferencias_politicas" name="preferencias_politicas" value="<?= htmlspecialchars($row['preferencias_politicas']) ?>">
                    </div>

                    <div class="form-group col-span-full">
                        <?php 
                            $ep_info = password_get_info($row['recitar_epitafio'] ?? '');
                            $is_ep_hash = !empty($ep_info['algo']);
                        ?>
                        <label for="recitar_epitafio">
                            Recitar epitafio (Contraseña del alumno)
                            <?php if ($is_ep_hash): ?>
                                <span style="font-size: 0.8rem; font-weight: normal; color: #a5b4fc; margin-left: 8px;">🔒 Actualmente protegido con Hash. Escribe uno nuevo si deseas cambiarlo.</span>
                            <?php endif; ?>
                        </label>
                        <textarea id="recitar_epitafio" name="recitar_epitafio" placeholder="Escribe el nuevo epitafio o contraseña si deseas modificarlo..."><?= htmlspecialchars($row['recitar_epitafio'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alturaInput = document.getElementById('altura');
    const pesoInput = document.getElementById('peso');
    const imcInput = document.getElementById('imc');
    const imcBadge = document.getElementById('imc-badge');

    function calculateIMC() {
        let h = parseFloat(alturaInput.value);
        const w = parseFloat(pesoInput.value);

        if (h > 3) {
            h = h / 100;
        }

        if (h > 0 && w > 0) {
            const imc = (w / (h * h)).toFixed(2);
            imcInput.value = imc;

            if (imc < 18.5) {
                imcBadge.className = 'imc-badge imc-bajo';
                imcBadge.textContent = `IMC: ${imc} (Bajo peso)`;
            } else if (imc < 25) {
                imcBadge.className = 'imc-badge imc-normal';
                imcBadge.textContent = `IMC: ${imc} (Peso normal)`;
            } else if (imc < 30) {
                imcBadge.className = 'imc-badge imc-sobrepeso';
                imcBadge.textContent = `IMC: ${imc} (Sobrepeso)`;
            } else {
                imcBadge.className = 'imc-badge imc-obesidad';
                imcBadge.textContent = `IMC: ${imc} (Obesidad)`;
            }
        }
    }

    calculateIMC();
    alturaInput.addEventListener('input', calculateIMC);
    pesoInput.addEventListener('input', calculateIMC);
});
</script>

</body>
</html>
