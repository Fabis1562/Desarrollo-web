<?php
session_start();

// Si ya tiene sesión iniciada, redirigir al panel principal
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

include("connection.php");
$con = connection();

// Obtener alumnos registrados con epitafio para pruebas y autocompletado rápido
$demo_query = mysqli_query($con, "SELECT id, nombre, edad, recitar_epitafio FROM alumnos WHERE recitar_epitafio IS NOT NULL AND recitar_epitafio != '' ORDER BY id DESC LIMIT 8");
$demo_alumnos = [];
if ($demo_query) {
    while ($row = mysqli_fetch_assoc($demo_query)) {
        $demo_alumnos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Gestión de Alumnos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .auth-tabs {
            display: flex;
            background: rgba(15, 23, 42, 0.6);
            border-radius: var(--radius-md);
            padding: 4px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            gap: 4px;
        }

        .auth-tab-btn {
            flex: 1;
            padding: 10px 14px;
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .auth-tab-btn.active {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        }

        .auth-tab-btn:hover:not(.active) {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .auth-view {
            display: none;
        }

        .auth-view.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-wrapper">
        <div class="login-header">
            <div class="login-icon-box">📜</div>
            <h1>Control Escolar</h1>
            <p>Accede con tu <strong>Nombre</strong> y <strong>Epitafio</strong> registrados</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'empty'): ?>
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <span>Por favor, ingresa tanto tu <strong>nombre</strong> como tu <strong>epitafio</strong>.</span>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <span>No se encontró ningún alumno con ese nombre y epitafio. Revisa los datos o regístrate.</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
            <div class="alert alert-success">
                <span>👋</span>
                <span>Has cerrado sesión exitosamente.</span>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="auth-tabs">
                <button type="button" class="auth-tab-btn active" id="tab-login-btn" onclick="switchTab('login')">
                    <span>🔑</span> Iniciar Sesión
                </button>
                <button type="button" class="auth-tab-btn" id="tab-register-btn" onclick="switchTab('register')">
                    <span>✨</span> Nuevo Alumno
                </button>
            </div>

            <!-- Formulario de Iniciar Sesión con Nombre y Epitafio -->
            <div id="view-login" class="auth-view active">
                <form action="login_process.php" method="POST">
                    <div class="form-section" style="margin-bottom: 0;">
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label for="login-nombre">
                                <span>👤 Nombre del Alumno</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="login-nombre" name="nombre" placeholder="Ej. juan, zumiko, luis..." required autocomplete="off">
                        </div>

                        <div class="form-group" style="margin-bottom: 22px;">
                            <label for="login-epitafio">
                                <span>📜 Epitafio Personal</span>
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="login-epitafio" name="recitar_epitafio" placeholder="Ej. Lo mejor está por llegar." required autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px;">
                            <span>🚀</span> Iniciar Sesión
                        </button>
                    </div>
                </form>

                <?php if (!empty($demo_alumnos)): ?>
                    <div class="demo-users-box">
                        <div class="demo-users-title">
                            <span>💡</span> Alumnos en el sistema (clic para autocompletar):
                        </div>
                        <div class="demo-chips-grid">
                            <?php foreach ($demo_alumnos as $alumno): ?>
                                <?php 
                                    $ep_info = password_get_info($alumno['recitar_epitafio'] ?? '');
                                    $is_hash = !empty($ep_info['algo']);
                                ?>
                                <button type="button" class="demo-chip" onclick="fillLoginForm('<?= htmlspecialchars(addslashes($alumno['nombre'])) ?>', '<?= htmlspecialchars(addslashes($alumno['recitar_epitafio'])) ?>')">
                                    <span>👤</span> <?= htmlspecialchars($alumno['nombre']) ?>: <em><?= $is_hash ? '🔒 [Hash Protegido]' : '"' . htmlspecialchars($alumno['recitar_epitafio']) . '"' ?></em>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Formulario Rápido de Registro para Nuevos Alumnos -->
            <div id="view-register" class="auth-view">
                <form action="register_alumno.php" method="POST">
                    <div class="form-section" style="margin-bottom: 0;">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="reg-nombre">Nombre Completo <span class="required">*</span></label>
                            <input type="text" id="reg-nombre" name="nombre" placeholder="Ej. Carlos Mendoza" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="reg-epitafio">Epitafio Personal (Frase o lema) <span class="required">*</span></label>
                            <input type="text" id="reg-epitafio" name="recitar_epitafio" placeholder="Ej. Siempre hacia adelante" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="reg-edad">Edad (Años)</label>
                            <input type="number" id="reg-edad" name="edad" placeholder="Ej. 21" min="1" max="120" value="20">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 22px;">
                            <div class="form-group">
                                <label for="reg-altura">Altura (m)</label>
                                <input type="number" id="reg-altura" name="altura" step="0.01" placeholder="Ej. 1.75" value="1.70">
                            </div>

                            <div class="form-group">
                                <label for="reg-peso">Peso (kg)</label>
                                <input type="number" id="reg-peso" name="peso" step="0.1" placeholder="Ej. 68.0" value="70.0">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px;">
                            <span>✨</span> Registrarse y Entrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const loginTabBtn = document.getElementById('tab-login-btn');
    const registerTabBtn = document.getElementById('tab-register-btn');
    const viewLogin = document.getElementById('view-login');
    const viewRegister = document.getElementById('view-register');

    if (tab === 'login') {
        loginTabBtn.classList.add('active');
        registerTabBtn.classList.remove('active');
        viewLogin.classList.add('active');
        viewRegister.classList.remove('active');
        document.getElementById('login-nombre').focus();
    } else {
        registerTabBtn.classList.add('active');
        loginTabBtn.classList.remove('active');
        viewRegister.classList.add('active');
        viewLogin.classList.remove('active');
        document.getElementById('reg-nombre').focus();
    }
}

function fillLoginForm(nombre, epitafio) {
    document.getElementById('login-nombre').value = nombre;
    document.getElementById('login-epitafio').value = epitafio;
    document.getElementById('login-epitafio').focus();
}

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('tab') === 'register') {
    switchTab('register');
}
</script>

</body>
</html>
