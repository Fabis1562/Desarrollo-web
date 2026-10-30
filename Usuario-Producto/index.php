<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Usuarios y Productos - API & Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #0ea5e9;
            --accent: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-dark: #0f172a;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius-lg: 16px;
            --radius-md: 10px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px -5px rgba(0,0,0,0.08), 0 8px 10px -6px rgba(0,0,0,0.04);
            --shadow-lg: 0 20px 35px -10px rgba(0,0,0,0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: var(--text-main);
            line-height: 1.5;
            padding-bottom: 80px;
        }

        /* Top Header */
        .top-navbar {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            color: white;
            padding: 2.2rem 2rem 3.5rem;
            box-shadow: 0 10px 30px rgba(49, 46, 129, 0.25);
            position: relative;
            overflow: hidden;
        }

        .top-navbar::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.3) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }

        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .header-subtitle {
            font-size: 1.05rem;
            color: #cbd5e1;
            max-width: 800px;
        }

        /* Quick Action Bar */
        .action-bar-wrapper {
            max-width: 1280px;
            margin: -25px auto 30px;
            padding: 0 1.5rem;
            position: relative;
            z-index: 3;
        }

        .action-bar {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.75rem;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            border: 1px solid var(--border);
        }

        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.92rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            outline: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-accent {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
        }
        .btn-accent:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .btn-danger:hover {
            background: #fecaca;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.82rem;
            border-radius: 6px;
        }

        /* Main Container */
        .main-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        /* Rules Banner */
        .info-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .info-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .icon-user { background: #e0e7ff; color: #4338ca; }
        .icon-prod { background: #e0f2fe; color: #0369a1; }
        .icon-postman { background: #ffedd5; color: #c2410c; }

        .info-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .info-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .fields-tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .field-tag {
            background: #f1f5f9;
            color: #334155;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        /* Content Sections */
        .section-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .section-header {
            padding: 1.5rem 1.75rem;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .section-header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header-title h2 {
            font-size: 1.35rem;
            font-weight: 700;
        }

        .badge-count {
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.82rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
        }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.92rem;
        }

        thead {
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        th {
            padding: 14px 18px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.76rem;
            letter-spacing: 0.6px;
        }

        td {
            padding: 16px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fbfcfe;
        }

        .id-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.82rem;
        }

        .price-badge {
            color: #059669;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }

        .stock-badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .stock-high { background: #dcfce7; color: #15803d; }
        .stock-low { background: #fef3c7; color: #b45309; }

        .client-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid var(--border);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #1e293b;
            font-weight: 500;
        }

        /* Filter Controls */
        .filter-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-select {
            padding: 8px 14px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            font-size: 0.9rem;
            outline: none;
            background: white;
            color: #1e293b;
            font-weight: 500;
        }
        .filter-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* API Console Section */
        .api-console-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 1.75rem;
        }

        @media (max-width: 900px) {
            .api-console-grid {
                grid-template-columns: 1fr;
            }
        }

        .code-box {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: var(--radius-md);
            padding: 1.25rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            overflow-x: auto;
            max-height: 380px;
            border: 1px solid #1e293b;
        }

        /* Modals */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-card {
            background: white;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 580px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: modalFade 0.2s ease-out;
        }

        @keyframes modalFade {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f8fafc;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.92rem;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* User Profile View Card */
        .user-spotlight {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            border: 2px dashed #c7d2fe;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 15px;
        }

        .user-field-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .user-field-row:last-child {
            border-bottom: none;
        }

        .user-field-label {
            font-weight: 700;
            color: #4338ca;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .user-field-val {
            font-weight: 600;
            color: #1e293b;
        }
    </style>
</head>
<body>

    <!-- Header Principal -->
    <header class="top-navbar">
        <div class="header-content">
            <span class="badge-pill">🚀 Sistema API REST + Base de Datos</span>
            <h1 class="header-title">Gestión de Usuarios y Productos</h1>
            <p class="header-subtitle">
                Arquitectura relacional con validación estricta de <strong>5 campos por usuario</strong> y <strong>5 campos por producto</strong>. 
                Lista para pruebas automatizadas con <strong>Postman</strong> y visualización interactiva.
            </p>
        </div>
    </header>

    <!-- Barra de Acciones Rápidas -->
    <div class="action-bar-wrapper">
        <div class="action-bar">
            <div class="action-group">
                <a href="Postman_Collection_Usuarios_Productos.json" download="Postman_Collection_Usuarios_Productos.json" class="btn btn-primary">
                    📥 Descargar Colección Postman (.json)
                </a>
                <a href="MANUAL_TECNICO_USUARIOS_PRODUCTOS.docx" download="MANUAL_TECNICO_USUARIOS_PRODUCTOS.docx" class="btn btn-secondary">
                    📄 Descargar Manual en Word (.docx)
                </a>
                <a href="MANUAL_TECNICO.md" target="_blank" class="btn btn-secondary">
                    📘 Ver Manual (Markdown)
                </a>
                <a href="database.sql" download="database.sql" class="btn btn-secondary">
                    💾 Script SQL (DDL / DML)
                </a>
            </div>
            <div class="action-group">
                <button onclick="openModalUser()" class="btn btn-accent">
                    ➕ Nuevo Usuario (5 campos)
                </button>
                <button onclick="openModalProduct()" class="btn btn-success">
                    ➕ Nuevo Producto (5 campos)
                </button>
            </div>
        </div>
    </div>

    <!-- Contenedor Principal -->
    <main class="main-container">

        <!-- Tarjetas de Reglas de Negocio -->
        <section class="info-card-grid">
            <div class="info-card">
                <div class="info-icon icon-user">👤</div>
                <div>
                    <h3 class="info-title">Módulo Usuarios (5 Campos)</h3>
                    <p class="info-desc">Al consultar un usuario en su parte específica, solo se muestran los datos de ese usuario.</p>
                    <div class="fields-tag-list">
                        <span class="field-tag">1. id</span>
                        <span class="field-tag">2. nombre</span>
                        <span class="field-tag">3. email</span>
                        <span class="field-tag">4. telefono</span>
                        <span class="field-tag">5. direccion</span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon icon-prod">📦</div>
                <div>
                    <h3 class="info-title">Módulo Productos (5 Campos)</h3>
                    <p class="info-desc">Al consultar los productos del cliente, se muestran tanto sus productos como todos los campos del cliente.</p>
                    <div class="fields-tag-list">
                        <span class="field-tag">1. id</span>
                        <span class="field-tag">2. nombre</span>
                        <span class="field-tag">3. precio</span>
                        <span class="field-tag">4. stock</span>
                        <span class="field-tag">5. usuario_id</span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon icon-postman">📮</div>
                <div>
                    <h3 class="info-title">Integración con Postman</h3>
                    <p class="info-desc">Colección completa con métodos GET, POST, PUT y DELETE en endpoints parametrizados.</p>
                    <div class="fields-tag-list">
                        <span class="field-tag">GET /usuarios.php?id=X</span>
                        <span class="field-tag">GET /productos.php?usuario_id=X</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN 1: TABLA DE USUARIOS (5 CAMPOS) -->
        <section class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <h2>👤 Directorio de Usuarios / Clientes</h2>
                    <span class="badge-count" id="count-users">Cargando...</span>
                </div>
                <div>
                    <button onclick="loadUsers()" class="btn btn-secondary btn-sm">🔄 Recargar Usuarios</button>
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>1. ID</th>
                            <th>2. Nombre Completo</th>
                            <th>3. Correo Electrónico</th>
                            <th>4. Teléfono</th>
                            <th>5. Dirección</th>
                            <th style="text-align: right;">Acciones del Requerimiento</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        <tr><td colspan="6" style="text-align:center; padding: 30px;">Cargando usuarios desde la base de datos...</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SECCIÓN 2: TABLA DE PRODUCTOS Y CLIENTE (5 CAMPOS) -->
        <section class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <h2>📦 Catálogo de Productos y Clientes Asociados</h2>
                    <span class="badge-count" id="count-prods">Cargando...</span>
                </div>
                <div class="filter-box">
                    <label for="filter-client" style="font-size:0.85rem; font-weight:600; color:#475569;">Filtrar por Cliente:</label>
                    <select id="filter-client" class="filter-select" onchange="onClientFilterChange()">
                        <option value="">-- Todos los Productos y Clientes --</option>
                    </select>
                    <button onclick="loadProducts()" class="btn btn-secondary btn-sm">🔄 Recargar</button>
                </div>
            </div>

            <!-- Banner Informativo del Cliente Seleccionado (Aparece al filtrar) -->
            <div id="client-spotlight-banner" style="display:none; padding: 1.25rem 1.75rem; background: #eef2ff; border-bottom: 1px solid #c7d2fe;">
                <!-- Dinámico vía JS -->
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>1. ID Prod</th>
                            <th>2. Nombre Producto</th>
                            <th>3. Precio</th>
                            <th>4. Stock</th>
                            <th>5. ID Cliente</th>
                            <th>Cliente Propietario (JOIN)</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="prods-table-body">
                        <tr><td colspan="7" style="text-align:center; padding: 30px;">Cargando productos desde la base de datos...</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SECCIÓN 3: PROBADOR EN VIVO DE ENDPOINTS REST -->
        <section class="section-card">
            <div class="section-header">
                <div class="section-header-title">
                    <h2>⚡ Consola de Prueba Rápida de Endpoints (Postman Preview)</h2>
                </div>
                <div class="action-group">
                    <button onclick="testQuickEndpoint('getUserOnly')" class="btn btn-secondary btn-sm">GET Usuario Solo (ID: 1)</button>
                    <button onclick="testQuickEndpoint('getClientProds')" class="btn btn-secondary btn-sm">GET Productos de Cliente (ID: 1)</button>
                    <button onclick="testQuickEndpoint('getAllUsers')" class="btn btn-secondary btn-sm">GET Todos Usuarios</button>
                    <button onclick="testQuickEndpoint('getAllProds')" class="btn btn-secondary btn-sm">GET Todos Productos</button>
                </div>
            </div>

            <div class="api-console-grid">
                <div>
                    <h4 style="margin-bottom: 8px; font-weight:700;">📡 Petición HTTP Enviada</h4>
                    <div class="code-box" id="request-display">
GET api/usuarios.php?id=1
Host: localhost
Accept: application/json
                    </div>
                </div>
                <div>
                    <h4 style="margin-bottom: 8px; font-weight:700;">📄 Respuesta JSON en Tiempo Real</h4>
                    <div class="code-box" id="response-display">
// Haz clic en cualquiera de los botones de arriba para probar la respuesta en vivo
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ==================== MODAL: VER SOLO ESE USUARIO ==================== -->
    <div class="modal-overlay" id="modal-view-user">
        <div class="modal-card">
            <div class="modal-header">
                <h3 style="font-weight:800; font-size:1.25rem;">🔍 Vista Exclusiva del Usuario (5 Campos)</h3>
                <button onclick="closeModal('modal-view-user')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:15px;">
                    <em>"si nos metemos en su parte nomas vamos a ver ese usuario"</em>
                </p>
                <div class="user-spotlight">
                    <div class="user-field-row">
                        <span class="user-field-label">1. ID de Usuario:</span>
                        <span class="user-field-val" id="view-user-id">#1</span>
                    </div>
                    <div class="user-field-row">
                        <span class="user-field-label">2. Nombre Completo:</span>
                        <span class="user-field-val" id="view-user-nombre">Juan Pérez</span>
                    </div>
                    <div class="user-field-row">
                        <span class="user-field-label">3. Correo Electrónico:</span>
                        <span class="user-field-val" id="view-user-email">juan@example.com</span>
                    </div>
                    <div class="user-field-row">
                        <span class="user-field-label">4. Teléfono:</span>
                        <span class="user-field-val" id="view-user-telefono">492-123-4567</span>
                    </div>
                    <div class="user-field-row">
                        <span class="user-field-label">5. Dirección:</span>
                        <span class="user-field-val" id="view-user-direccion">Av. Principal #100</span>
                    </div>
                </div>
                <div style="font-size:0.8rem; color:#6366f1; background:#eef2ff; padding:10px; border-radius:8px;">
                    💡 Endpoint Postman correspondiente: <code>GET api/usuarios.php?id=<span id="view-user-endpoint-id">1</span></code>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal('modal-view-user')" class="btn btn-secondary">Cerrar</button>
                <button onclick="filterBySelectedUserFromModal()" class="btn btn-primary">Ver sus Productos 📦</button>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL: CREAR / EDITAR USUARIO ==================== -->
    <div class="modal-overlay" id="modal-user-form">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modal-user-title" style="font-weight:800; font-size:1.25rem;">➕ Registrar Nuevo Usuario</h3>
                <button onclick="closeModal('modal-user-form')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
            </div>
            <form id="form-user" onsubmit="saveUser(event)">
                <div class="modal-body">
                    <input type="hidden" id="form-user-id" value="">
                    
                    <div class="form-group">
                        <label class="form-label">2. Nombre Completo</label>
                        <input type="text" id="form-user-nombre" class="form-input" placeholder="Ej. Ana Cristina Morales" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">3. Correo Electrónico</label>
                        <input type="email" id="form-user-email" class="form-input" placeholder="Ej. ana.morales@empresa.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">4. Número Telefónico</label>
                        <input type="text" id="form-user-telefono" class="form-input" placeholder="Ej. 4921234567" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">5. Dirección Física</label>
                        <input type="text" id="form-user-direccion" class="form-input" placeholder="Ej. Calle Morelos #305, Col. Centro" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-user-form')" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL: CREAR / EDITAR PRODUCTO ==================== -->
    <div class="modal-overlay" id="modal-prod-form">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modal-prod-title" style="font-weight:800; font-size:1.25rem;">➕ Registrar Nuevo Producto</h3>
                <button onclick="closeModal('modal-prod-form')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
            </div>
            <form id="form-prod" onsubmit="saveProduct(event)">
                <div class="modal-body">
                    <input type="hidden" id="form-prod-id" value="">
                    
                    <div class="form-group">
                        <label class="form-label">5. Cliente Propietario (usuario_id)</label>
                        <select id="form-prod-usuario" class="form-input" required>
                            <option value="">-- Selecciona el cliente --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">2. Nombre del Producto</label>
                        <input type="text" id="form-prod-nombre" class="form-input" placeholder="Ej. Monitor Gamer 24 pulgadas" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">3. Precio Unitario ($ MXN)</label>
                        <input type="number" step="0.01" min="0" id="form-prod-precio" class="form-input" placeholder="Ej. 2999.50" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">4. Cantidad en Stock</label>
                        <input type="number" min="0" id="form-prod-stock" class="form-input" placeholder="Ej. 15" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-prod-form')" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_USERS = "api/usuarios.php";
        const API_PRODS = "api/productos.php";

        let allUsers = [];
        let allProducts = [];
        let selectedUserIdForFilter = null;

        document.addEventListener("DOMContentLoaded", () => {
            loadUsers();
            loadProducts();
        });

        // ------------------ GESTIÓN DE USUARIOS ------------------
        async function loadUsers() {
            try {
                const res = await fetch(API_USERS);
                const data = await res.json();
                if (data.status === "success") {
                    allUsers = data.data;
                    document.getElementById("count-users").textContent = `${allUsers.length} Registrados`;
                    renderUsersTable(allUsers);
                    populateClientSelects(allUsers);
                }
            } catch (err) {
                console.error("Error al cargar usuarios:", err);
            }
        }

        function renderUsersTable(users) {
            const tbody = document.getElementById("users-table-body");
            if (users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 25px; color:#64748b;">No hay usuarios registrados. Agrega uno con el botón superior.</td></tr>`;
                return;
            }

            tbody.innerHTML = users.map(u => `
                <tr>
                    <td><span class="id-badge">#${u.id}</span></td>
                    <td><strong>${escapeHtml(u.nombre)}</strong></td>
                    <td>${escapeHtml(u.email)}</td>
                    <td>${escapeHtml(u.telefono)}</td>
                    <td>${escapeHtml(u.direccion)}</td>
                    <td style="text-align: right;">
                        <button onclick="viewUserAlone(${u.id})" class="btn btn-secondary btn-sm" title="Requerimiento: Ver solo este usuario">
                            👁️ Ver Solo Usuario
                        </button>
                        <button onclick="filterByClient(${u.id})" class="btn btn-secondary btn-sm" title="Requerimiento: Ver sus productos con datos del cliente">
                            📦 Productos
                        </button>
                        <button onclick="editUser(${u.id})" class="btn btn-secondary btn-sm">
                            ✏️
                        </button>
                        <button onclick="deleteUser(${u.id})" class="btn btn-danger btn-sm">
                            🗑️
                        </button>
                    </td>
                </tr>
            `).join("");
        }

        // ------------------ REQUERIMIENTO: VER SOLO ESE USUARIO ------------------
        async function viewUserAlone(id) {
            try {
                const res = await fetch(`${API_USERS}?id=${id}`);
                const result = await res.json();

                if (result.status === "success") {
                    const u = result.data;
                    document.getElementById("view-user-id").textContent = `#${u.id}`;
                    document.getElementById("view-user-nombre").textContent = u.nombre;
                    document.getElementById("view-user-email").textContent = u.email;
                    document.getElementById("view-user-telefono").textContent = u.telefono;
                    document.getElementById("view-user-direccion").textContent = u.direccion;
                    document.getElementById("view-user-endpoint-id").textContent = u.id;

                    selectedUserIdForFilter = u.id;
                    openModal("modal-view-user");

                    // Reflejar en la consola de prueba
                    displayConsole(`GET ${API_USERS}?id=${id}`, result);
                } else {
                    alert(result.message || "Usuario no encontrado");
                }
            } catch (err) {
                alert("Error de conexión al obtener el usuario");
            }
        }

        function filterBySelectedUserFromModal() {
            closeModal("modal-view-user");
            if (selectedUserIdForFilter) {
                filterByClient(selectedUserIdForFilter);
            }
        }

        // ------------------ GESTIÓN DE PRODUCTOS ------------------
        async function loadProducts(clientId = null) {
            try {
                let url = API_PRODS;
                if (clientId) {
                    url += `?usuario_id=${clientId}`;
                }

                const res = await fetch(url);
                const data = await res.json();

                if (data.status === "success") {
                    if (clientId) {
                        // Respuesta filtrada por cliente (muestra datos del cliente y sus productos)
                        renderClientSpotlightBanner(data.cliente, data.total_productos);
                        renderProductsTable(data.productos, data.cliente);
                        document.getElementById("count-prods").textContent = `${data.total_productos} de ${data.cliente.nombre}`;
                    } else {
                        // Respuesta general
                        document.getElementById("client-spotlight-banner").style.display = "none";
                        allProducts = data.data;
                        document.getElementById("count-prods").textContent = `${allProducts.length} Registrados`;
                        renderProductsTableGeneral(allProducts);
                    }
                }
            } catch (err) {
                console.error("Error al cargar productos:", err);
            }
        }

        function renderClientSpotlightBanner(cliente, totalProds) {
            const banner = document.getElementById("client-spotlight-banner");
            banner.style.display = "block";
            banner.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">
                    <div>
                        <span style="font-size:0.78rem; text-transform:uppercase; font-weight:800; color:#4338ca; letter-spacing:0.5px;">
                            ⭐ Vista del Cliente y sus Productos Asociados (Requerimiento)
                        </span>
                        <h3 style="font-size:1.2rem; font-weight:800; color:#1e1b4b; margin:3px 0 6px;">
                            Cliente: ${escapeHtml(cliente.nombre)} <span class="id-badge">ID: #${cliente.id}</span>
                        </h3>
                        <div style="display:flex; flex-wrap:wrap; gap:15px; font-size:0.88rem; color:#475569;">
                            <span>📧 <strong>Email:</strong> ${escapeHtml(cliente.email)}</span>
                            <span>📞 <strong>Tel:</strong> ${escapeHtml(cliente.telefono)}</span>
                            <span>📍 <strong>Dirección:</strong> ${escapeHtml(cliente.direccion)}</span>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <span class="badge-pill" style="background:#4338ca; color:white; font-size:0.85rem;">
                            ${totalProds} Producto(s) Vinculado(s)
                        </span>
                        <br>
                        <button onclick="resetProductFilter()" class="btn btn-secondary btn-sm" style="margin-top:6px;">
                            ✖️ Ver Todos los Productos
                        </button>
                    </div>
                </div>
            `;
        }

        function renderProductsTable(prods, cliente) {
            const tbody = document.getElementById("prods-table-body");
            if (prods.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding: 25px; color:#64748b;">Este cliente no tiene productos registrados actualmente.</td></tr>`;
                return;
            }

            tbody.innerHTML = prods.map(p => `
                <tr>
                    <td><span class="id-badge">#${p.id}</span></td>
                    <td><strong>${escapeHtml(p.nombre)}</strong></td>
                    <td><span class="price-badge">$${parseFloat(p.precio).toLocaleString('es-MX', {minimumFractionDigits:2})}</span></td>
                    <td>
                        <span class="stock-badge ${p.stock > 10 ? 'stock-high' : 'stock-low'}">
                            ${p.stock} unidades
                        </span>
                    </td>
                    <td><span class="id-badge">#${p.usuario_id}</span></td>
                    <td>
                        <div class="client-chip">
                            👤 ${escapeHtml(cliente.nombre)}
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <button onclick="editProduct(${p.id}, '${escapeJs(p.nombre)}', ${p.precio}, ${p.stock}, ${p.usuario_id})" class="btn btn-secondary btn-sm">
                            ✏️
                        </button>
                        <button onclick="deleteProduct(${p.id})" class="btn btn-danger btn-sm">
                            🗑️
                        </button>
                    </td>
                </tr>
            `).join("");
        }

        function renderProductsTableGeneral(items) {
            const tbody = document.getElementById("prods-table-body");
            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding: 25px; color:#64748b;">No hay productos registrados en el sistema.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map(item => {
                const p = item.producto;
                const c = item.cliente;
                return `
                    <tr>
                        <td><span class="id-badge">#${p.id}</span></td>
                        <td><strong>${escapeHtml(p.nombre)}</strong></td>
                        <td><span class="price-badge">$${parseFloat(p.precio).toLocaleString('es-MX', {minimumFractionDigits:2})}</span></td>
                        <td>
                            <span class="stock-badge ${p.stock > 10 ? 'stock-high' : 'stock-low'}">
                                ${p.stock} unidades
                            </span>
                        </td>
                        <td><span class="id-badge">#${p.usuario_id}</span></td>
                        <td>
                            <div class="client-chip" title="Email: ${escapeHtml(c.email)} | Tel: ${escapeHtml(c.telefono)} | Dir: ${escapeHtml(c.direccion)}">
                                👤 ${escapeHtml(c.nombre)}
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <button onclick="filterByClient(${c.id})" class="btn btn-secondary btn-sm" title="Filtrar solo este cliente">
                                🔍 Ver Cliente
                            </button>
                            <button onclick="editProduct(${p.id}, '${escapeJs(p.nombre)}', ${p.precio}, ${p.stock}, ${p.usuario_id})" class="btn btn-secondary btn-sm">
                                ✏️
                            </button>
                            <button onclick="deleteProduct(${p.id})" class="btn btn-danger btn-sm">
                                🗑️
                            </button>
                        </td>
                    </tr>
                `;
            }).join("");
        }

        // ------------------ FILTROS ------------------
        function filterByClient(clientId) {
            const select = document.getElementById("filter-client");
            select.value = clientId;
            loadProducts(clientId);

            // Reflejar en la consola interactiva
            fetch(`${API_PRODS}?usuario_id=${clientId}`)
                .then(r => r.json())
                .then(json => displayConsole(`GET ${API_PRODS}?usuario_id=${clientId}`, json));
        }

        function onClientFilterChange() {
            const val = document.getElementById("filter-client").value;
            if (val) {
                filterByClient(val);
            } else {
                resetProductFilter();
            }
        }

        function resetProductFilter() {
            document.getElementById("filter-client").value = "";
            loadProducts();
        }

        function populateClientSelects(users) {
            const filterSel = document.getElementById("filter-client");
            const formSel = document.getElementById("form-prod-usuario");

            const currentFilterVal = filterSel.value;
            const currentFormVal = formSel.value;

            filterSel.innerHTML = '<option value="">-- Todos los Productos y Clientes --</option>' +
                users.map(u => `<option value="${u.id}">${u.id}. ${escapeHtml(u.nombre)} (${escapeHtml(u.email)})</option>`).join("");

            formSel.innerHTML = '<option value="">-- Selecciona el cliente --</option>' +
                users.map(u => `<option value="${u.id}">${u.id}. ${escapeHtml(u.nombre)}</option>`).join("");

            filterSel.value = currentFilterVal;
            formSel.value = currentFormVal;
        }

        // ------------------ FORMULARIO DE USUARIO (ALTA / EDICIÓN) ------------------
        function openModalUser() {
            document.getElementById("modal-user-title").textContent = "➕ Registrar Nuevo Usuario (5 Campos)";
            document.getElementById("form-user-id").value = "";
            document.getElementById("form-user-nombre").value = "";
            document.getElementById("form-user-email").value = "";
            document.getElementById("form-user-telefono").value = "";
            document.getElementById("form-user-direccion").value = "";
            openModal("modal-user-form");
        }

        function editUser(id) {
            const u = allUsers.find(x => x.id === id);
            if (!u) return;
            document.getElementById("modal-user-title").textContent = `✏️ Editar Usuario #${id}`;
            document.getElementById("form-user-id").value = u.id;
            document.getElementById("form-user-nombre").value = u.nombre;
            document.getElementById("form-user-email").value = u.email;
            document.getElementById("form-user-telefono").value = u.telefono;
            document.getElementById("form-user-direccion").value = u.direccion;
            openModal("modal-user-form");
        }

        async function saveUser(e) {
            e.preventDefault();
            const id = document.getElementById("form-user-id").value;
            const payload = {
                nombre: document.getElementById("form-user-nombre").value.trim(),
                email: document.getElementById("form-user-email").value.trim(),
                telefono: document.getElementById("form-user-telefono").value.trim(),
                direccion: document.getElementById("form-user-direccion").value.trim()
            };

            const isEdit = Boolean(id);
            if (isEdit) payload.id = parseInt(id);

            try {
                const res = await fetch(API_USERS, {
                    method: isEdit ? "PUT" : "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                displayConsole(`${isEdit ? "PUT" : "POST"} ${API_USERS}`, result);

                if (result.status === "success") {
                    closeModal("modal-user-form");
                    loadUsers();
                    if (isEdit) loadProducts();
                } else {
                    alert(result.message || "Error al guardar usuario");
                }
            } catch (err) {
                alert("Error de comunicación con el servidor");
            }
        }

        async function deleteUser(id) {
            if (!confirm(`¿Estás seguro de eliminar el usuario #${id}? Se eliminarán también todos sus productos vinculados.`)) {
                return;
            }

            try {
                const res = await fetch(`${API_USERS}?id=${id}`, { method: "DELETE" });
                const result = await res.json();
                displayConsole(`DELETE ${API_USERS}?id=${id}`, result);

                if (result.status === "success") {
                    loadUsers();
                    loadProducts();
                } else {
                    alert(result.message || "Error al eliminar usuario");
                }
            } catch (err) {
                alert("Error de comunicación con el servidor");
            }
        }

        // ------------------ FORMULARIO DE PRODUCTO (ALTA / EDICIÓN) ------------------
        function openModalProduct() {
            document.getElementById("modal-prod-title").textContent = "➕ Registrar Nuevo Producto (5 Campos)";
            document.getElementById("form-prod-id").value = "";
            document.getElementById("form-prod-nombre").value = "";
            document.getElementById("form-prod-precio").value = "";
            document.getElementById("form-prod-stock").value = "";
            document.getElementById("form-prod-usuario").value = "";
            openModal("modal-prod-form");
        }

        function editProduct(id, nombre, precio, stock, usuario_id) {
            document.getElementById("modal-prod-title").textContent = `✏️ Editar Producto #${id}`;
            document.getElementById("form-prod-id").value = id;
            document.getElementById("form-prod-nombre").value = nombre;
            document.getElementById("form-prod-precio").value = precio;
            document.getElementById("form-prod-stock").value = stock;
            document.getElementById("form-prod-usuario").value = usuario_id;
            openModal("modal-prod-form");
        }

        async function saveProduct(e) {
            e.preventDefault();
            const id = document.getElementById("form-prod-id").value;
            const payload = {
                nombre: document.getElementById("form-prod-nombre").value.trim(),
                precio: parseFloat(document.getElementById("form-prod-precio").value),
                stock: parseInt(document.getElementById("form-prod-stock").value),
                usuario_id: parseInt(document.getElementById("form-prod-usuario").value)
            };

            const isEdit = Boolean(id);
            if (isEdit) payload.id = parseInt(id);

            try {
                const res = await fetch(API_PRODS, {
                    method: isEdit ? "PUT" : "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                displayConsole(`${isEdit ? "PUT" : "POST"} ${API_PRODS}`, result);

                if (result.status === "success") {
                    closeModal("modal-prod-form");
                    const currentFilter = document.getElementById("filter-client").value;
                    loadProducts(currentFilter || null);
                } else {
                    alert(result.message || "Error al guardar el producto");
                }
            } catch (err) {
                alert("Error de comunicación con el servidor");
            }
        }

        async function deleteProduct(id) {
            if (!confirm(`¿Estás seguro de eliminar el producto #${id}?`)) {
                return;
            }

            try {
                const res = await fetch(`${API_PRODS}?id=${id}`, { method: "DELETE" });
                const result = await res.json();
                displayConsole(`DELETE ${API_PRODS}?id=${id}`, result);

                if (result.status === "success") {
                    const currentFilter = document.getElementById("filter-client").value;
                    loadProducts(currentFilter || null);
                } else {
                    alert(result.message || "Error al eliminar producto");
                }
            } catch (err) {
                alert("Error de comunicación con el servidor");
            }
        }

        // ------------------ CONSOLA REST EN VIVO ------------------
        function displayConsole(requestStr, jsonResponse) {
            document.getElementById("request-display").textContent = `${requestStr}\nHost: localhost\nAccept: application/json\nContent-Type: application/json`;
            document.getElementById("response-display").textContent = JSON.stringify(jsonResponse, null, 4);
        }

        async function testQuickEndpoint(type) {
            let url = "";
            switch (type) {
                case "getUserOnly":
                    url = `${API_USERS}?id=1`;
                    break;
                case "getClientProds":
                    url = `${API_PRODS}?usuario_id=1`;
                    break;
                case "getAllUsers":
                    url = API_USERS;
                    break;
                case "getAllProds":
                    url = API_PRODS;
                    break;
            }

            try {
                const res = await fetch(url);
                const json = await res.json();
                displayConsole(`GET ${url}`, json);
            } catch (e) {
                displayConsole(`GET ${url}`, { error: "No se pudo conectar" });
            }
        }

        // ------------------ MODALES Y HELPERS ------------------
        function openModal(id) {
            document.getElementById(id).style.display = "flex";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target.classList.contains("modal-overlay")) {
                event.target.style.display = "none";
            }
        }

        function escapeHtml(text) {
            if (!text) return "";
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function escapeJs(text) {
            if (!text) return "";
            return String(text).replace(/'/g, "\\'");
        }
    </script>
</body>
</html>

