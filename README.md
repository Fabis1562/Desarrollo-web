# 🌐 Proyectos de Desarrollo Web

Repositorio con proyectos y prácticas de backend en PHP y MySQL para entornos XAMPP, incluyendo APIs RESTful probadas con Postman y documentación técnica.

---

## 📁 Proyectos Incluidos

### 1. 📦 [Usuario-Producto](Usuario-Producto/) — Sistema de Gestión con API REST (5 Campos)
Sistema con validación estricta y arquitectura relacional:
* **Entidad Usuarios (5 campos):** `id`, `nombre`, `email`, `telefono`, `direccion`. Al consultar un usuario por ID (`GET api/usuarios.php?id=X`), se muestran **únicamente los datos de ese usuario**.
* **Entidad Productos (5 campos):** `id`, `nombre`, `precio`, `stock`, `usuario_id`. Al consultar los productos de un cliente (`GET api/productos.php?usuario_id=X`), se muestran **los productos del cliente y los 5 campos del cliente**.
* **Postman Collection:** Colección importable [Postman_Collection_Usuarios_Productos.json](Usuario-Producto/Postman_Collection_Usuarios_Productos.json).
* **Manual Técnico:** Documentado en [Word (.docx)](Usuario-Producto/MANUAL_TECNICO_USUARIOS_PRODUCTOS.docx) y [Markdown](Usuario-Producto/MANUAL_TECNICO.md) con las 12 capturas de evidencias completas.
* **Dashboard Interactivo:** [index.php](Usuario-Producto/index.php).

### 2. 🎓 [Tarea](Tarea/) — Sistema de Control Escolar
Gestión de alumnos con cálculo antropométrico (IMC), autenticación por sesión y sentencias preparadas contra SQL Injection.

### 3. 👥 [CRUD_php](CRUD_php/) — CRUD Básico de Usuarios
Implementación inicial de operaciones Create, Read, Update y Delete con interfaz web y endpoints básicos.

---

## 🚀 Requisitos de Ejecución
* **XAMPP** (Apache 2.4+ y MariaDB/MySQL).
* **PHP** versión 8.0 o superior.
* **Postman** para pruebas de integración de endpoints REST.
