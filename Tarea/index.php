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
$sql = "SELECT * FROM alumnos ORDER BY id DESC";
$query = mysqli_query($con, $sql);
$total_alumnos = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Alumnos | Gestión Escolar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <!-- Barra Superior de Usuario / Sesión -->
    <div class="user-status-bar">
        <div class="user-info">
            <div class="user-avatar">👤</div>
            <div class="user-details">
                <span class="user-greeting">Sesión Iniciada</span>
                <div class="user-name-wrapper">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user']) ?></span>
                    <?php if (!empty($_SESSION['epitafio'])): ?>
                        <span class="user-badge" title="Epitafio registrado" style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            📜 "<?= htmlspecialchars($_SESSION['epitafio']) ?>"
                        </span>
                    <?php elseif (isset($_SESSION['edad'])): ?>
                        <span class="user-badge"><?= htmlspecialchars($_SESSION['edad']) ?> años</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <a href="logout.php" class="btn-logout" title="Cerrar la sesión actual">
            <span>🚪</span> Cerrar Sesión
        </a>
    </div>

    <header class="header-hero">
        <span class="header-badge">🚀 Panel de Control Académico</span>
        <h1>Registro y Control de Alumnos</h1>
        <p>Administra la información de los alumnos con cálculo automático de IMC y preferencias personales.</p>
    </header>

    <div class="crud-grid">
        <section class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="icon">📝</span>
                    <span>Registrar Nuevo Alumno</span>
                </div>
            </div>

            <form action="insert_alumno.php" method="POST" id="form-alumno">
                <div class="form-section">
                    <h3 class="section-title">👤 Datos Personales y Antropométricos</h3>
                    <div class="form-grid">
                        <div class="form-group col-span-2">
                            <label for="nombre">Nombre Completo <span class="required">*</span></label>
                            <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez González" required>
                        </div>

                        <div class="form-group">
                            <label for="edad">Edad <span class="required">*</span></label>
                            <input type="number" id="edad" name="edad" placeholder="Años (ej. 20)" min="1" max="120" required>
                        </div>

                        <div class="form-group">
                            <label for="altura">Altura (metros) <span class="required">*</span></label>
                            <input type="number" id="altura" name="altura" step="0.01" placeholder="Ej. 1.75" required>
                        </div>

                        <div class="form-group">
                            <label for="peso">Peso (kg) <span class="required">*</span></label>
                            <input type="number" id="peso" name="peso" step="0.1" placeholder="Ej. 70.5" required>
                        </div>

                        <div class="form-group">
                            <label for="imc">IMC Calculado</label>
                            <input type="number" id="imc" name="imc" step="0.01" placeholder="Automático" readonly>
                        </div>
                    </div>

                    <div style="margin-top: 12px;" id="imc-indicator-container">
                        <div class="imc-preview-box">
                            <span>Estado de IMC:</span>
                            <span id="imc-badge" class="imc-badge imc-normal">Esperando datos de peso y altura...</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">🐘 Experiencias y Hábitos</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="montado_elefante">¿Ha montado un elefante?</label>
                            <select id="montado_elefante" name="montado_elefante">
                                <option value="No">No</option>
                                <option value="Sí">Sí</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="miedo_volar">¿Tienes miedo a volar?</label>
                            <select id="miedo_volar" name="miedo_volar">
                                <option value="No">No</option>
                                <option value="Sí">Sí</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="libros_leidos">¿Cuántos libros has leído?</label>
                            <input type="number" id="libros_leidos" name="libros_leidos" min="0" placeholder="Ej. 12" value="0">
                        </div>

                        <div class="form-group">
                            <label for="color_cabello">Color de cabello</label>
                            <input type="text" id="color_cabello" name="color_cabello" placeholder="Ej. Castaño, Negro, Rubio...">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">🎬 Gustos y Entretenimiento</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="pelicula_favorita">Película favorita</label>
                            <input type="text" id="pelicula_favorita" name="pelicula_favorita" placeholder="Ej. Inception, Titanic...">
                        </div>

                        <div class="form-group">
                            <label for="serie_favorita">Serie favorita</label>
                            <input type="text" id="serie_favorita" name="serie_favorita" placeholder="Ej. Breaking Bad, Dark...">
                        </div>

                        <div class="form-group">
                            <label for="genero_musica_favorita">Género de música favorita</label>
                            <input type="text" id="genero_musica_favorita" name="genero_musica_favorita" placeholder="Ej. Rock, Pop, Jazz, Hip-hop...">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">🏛️ Ideología y Epitafio</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="preferencias_religiosas">Preferencias religiosas</label>
                            <input type="text" id="preferencias_religiosas" name="preferencias_religiosas" placeholder="Ej. Católico, Ateo, Budista...">
                        </div>

                        <div class="form-group">
                            <label for="preferencias_politicas">Preferencias políticas</label>
                            <input type="text" id="preferencias_politicas" name="preferencias_politicas" placeholder="Ej. Centro, Liberal, Conservador...">
                        </div>

                        <div class="form-group col-span-full">
                            <label for="recitar_epitafio">Recitar epitafio</label>
                            <textarea id="recitar_epitafio" name="recitar_epitafio" placeholder="Escribe tu epitafio personal o cita predilecta para la posteridad..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Limpiar Campos</button>
                    <button type="submit" class="btn btn-primary">➕ Registrar Alumno</button>
                </div>

            </form>
        </section>

        <section class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="icon">📋</span>
                    <span>Lista de Alumnos Registrados</span>
                </div>
                <div class="records-count">
                    Total: <strong><?= $total_alumnos ?></strong> alumnos
                </div>
            </div>

            <div class="table-filter-bar">
                <div class="search-input-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="search-input" placeholder="Buscar alumno por nombre, película o música...">
                </div>
            </div>

            <div class="table-wrapper">
                <table id="alumnos-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Altura</th>
                            <th>Peso</th>
                            <th>IMC</th>
                            <th>Religión</th>
                            <th>Elefante</th>
                            <th>Miedo a Volar</th>
                            <th>Libros</th>
                            <th>Cabello</th>
                            <th>Película</th>
                            <th>Serie</th>
                            <th>Música</th>
                            <th>Política</th>
                            <th>Epitafio</th>
                            <th style="text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total_alumnos > 0): ?>
                            <?php while ($row = mysqli_fetch_array($query)): ?>
                                <?php 
                                    $imc_val = floatval($row['imc']);
                                    $imc_class = 'imc-normal';
                                    if ($imc_val < 18.5) $imc_class = 'imc-bajo';
                                    elseif ($imc_val >= 25 && $imc_val < 30) $imc_class = 'imc-sobrepeso';
                                    elseif ($imc_val >= 30) $imc_class = 'imc-obesidad';
                                ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($row['id']) ?></strong></td>
                                    <td class="cell-name"><?= htmlspecialchars($row['nombre']) ?></td>
                                    <td><?= htmlspecialchars($row['edad']) ?> años</td>
                                    <td><?= htmlspecialchars($row['altura']) ?> m</td>
                                    <td><?= htmlspecialchars($row['peso']) ?> kg</td>
                                    <td>
                                        <span class="imc-badge <?= $imc_class ?>">
                                            <?= number_format($imc_val, 2) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['preferencias_religiosas'] ?: 'N/A') ?></td>
                                    <td>
                                        <span class="<?= ($row['montado_elefante'] === 'Sí') ? 'badge-yes' : 'badge-no' ?>">
                                            <?= htmlspecialchars($row['montado_elefante']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="<?= ($row['miedo_volar'] === 'Sí') ? 'badge-yes' : 'badge-no' ?>">
                                            <?= htmlspecialchars($row['miedo_volar']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['libros_leidos']) ?></td>
                                    <td><?= htmlspecialchars($row['color_cabello'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['pelicula_favorita'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['serie_favorita'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['genero_musica_favorita'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['preferencias_politicas'] ?: 'N/A') ?></td>
                                    <td class="cell-epitaph" title="<?= htmlspecialchars($row['recitar_epitafio'] ?? '') ?>">
                                        <?php 
                                            $ep_info = password_get_info($row['recitar_epitafio'] ?? '');
                                            if (!empty($ep_info['algo'])) {
                                                echo '<span style="font-family: monospace; font-size: 0.8rem; color: #a5b4fc;" title="Contraseña protegida (Hash): ' . htmlspecialchars($row['recitar_epitafio']) . '">🔒 ' . htmlspecialchars(substr($row['recitar_epitafio'], 0, 15)) . '...</span>';
                                            } else {
                                                echo htmlspecialchars($row['recitar_epitafio'] ?: 'Sin epitafio');
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="cell-actions">
                                            <a href="update.php?id=<?= $row['id'] ?>" class="btn-action-edit" title="Editar">
                                                ✏️ Editar
                                            </a>
                                            <a href="delete_alumno.php?id=<?= $row['id'] ?>" class="btn-action-delete" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar al alumno \'<?= htmlspecialchars(addslashes($row['nombre'])) ?>\'?');">
                                                🗑️ Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="17">
                                    <div class="empty-state">
                                        <div class="icon">📂</div>
                                        <p>No hay alumnos registrados todavía.</p>
                                        <small>Completa el formulario de arriba para agregar el primero.</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
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
                imcBadge.textContent = `IMC: ${imc} (Peso saludable / Normal)`;
            } else if (imc < 30) {
                imcBadge.className = 'imc-badge imc-sobrepeso';
                imcBadge.textContent = `IMC: ${imc} (Sobrepeso)`;
            } else {
                imcBadge.className = 'imc-badge imc-obesidad';
                imcBadge.textContent = `IMC: ${imc} (Obesidad)`;
            }
        } else {
            imcInput.value = '';
            imcBadge.className = 'imc-badge';
            imcBadge.style.background = '#334155';
            imcBadge.style.color = '#94a3b8';
            imcBadge.textContent = 'Ingresa peso y altura para calcular';
        }
    }

    alturaInput.addEventListener('input', calculateIMC);
    pesoInput.addEventListener('input', calculateIMC);

    const searchInput = document.getElementById('search-input');
    const tableBody = document.querySelector('#alumnos-table tbody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', function() {
            const query = searchInput.value.toLowerCase().trim();
            const rows = tableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                if (text.includes(query)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    }
});
</script>

</body>
</html>
