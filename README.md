# 📚 Gestión Escolar (GE)

Sistema de gestión escolar desarrollado en **PHP** con programación orientada a objetos (POO), arquitectura **MVC** y conexión a base de datos con **PDO**.

---

## 🚀 Tecnologías utilizadas

- **PHP 8.x** (POO, PDO)
- **MySQL** (MariaDB)
- **HTML5, CSS3, JavaScript**
- **XAMPP** (Apache + MySQL)
- **Visual Studio Code**

---

## 📁 Estructura del proyecto

```
ge/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── img/
│   │   └── favicon.ico
│   └── js/
│       └── main.js
├── config/
│   └── database.php
├── controllers/
│   ├── AuthController.php
│   ├── EstudianteController.php
│   ├── CursoController.php
│   ├── InscripcionController.php
│   └── DashboardController.php
├── models/
│   ├── Usuario.php
│   ├── Estudiante.php
│   ├── Curso.php
│   └── Inscripcion.php
├── views/
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── estudiantes/
│   ├── cursos/
│   ├── inscripciones/
│   └── dashboard.php
├── helpers/
│   └── functions.php
├── index.php
└── .htaccess
```

---

## 🔧 Instalación y configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/SoyAxdx/ge.git
```

### 2. Mover a la carpeta de XAMPP
```bash
mv ge/ C:\xampp\htdocs\
```

### 3. Crear la base de datos
- Abre **phpMyAdmin**: `http://localhost/phpmyadmin`
- Importa el archivo `ge.sql` (que está en la raíz del proyecto) o ejecuta el script SQL manualmente.

### 4. Configurar la conexión
En `config/database.php`, verifica que los datos sean correctos:
```php
private $host = '127.0.0.1';
private $dbname = 'ge';
private $username = 'root';
private $password = '';
```

### 5. Ejecutar el proyecto
- Asegúrate de que **Apache** y **MySQL** estén activos en XAMPP.
- Abre tu navegador en: `http://localhost/ge/index.php?action=login`

---

## 🔐 Usuario administrador por defecto

| Campo | Valor |
|-------|-------|
| **Email** | `admin@escuela.com` |
| **Contraseña** | `admin123` |

---

## 🧪 Datos de prueba

El sistema incluye datos de prueba para facilitar las pruebas:

- **Estudiantes:** María González, Juan Pérez, Carlos López
- **Cursos:** Desarrollo de Software VII (DS-101)
- **Inscripción:** María González → DS-101 con nota 85.50

---

## ✅ Funcionalidades implementadas

| Módulo | Descripción |
|--------|-------------|
| **Autenticación** | Login, registro con roles (admin, docente, estudiante) |
| **Dashboard** | Estadísticas generales (estudiantes, cursos, inscripciones, promedio de notas) |
| **Estudiantes** | CRUD completo con validaciones (cédula, nombre, apellido, email, teléfono) |
| **Cursos** | CRUD completo con código, nombre, descripción, créditos |
| **Inscripciones** | Asignación de estudiantes a cursos, gestión de notas |

---

## 🛡️ Seguridad

- Contraseñas hasheadas con `password_hash()`
- Consultas preparadas con PDO (prevención de SQL Injection)
- Validaciones en cliente y servidor
- Restricciones `UNIQUE` en base de datos (cédula, teléfono, email)
- Protección CSRF (en desarrollo)

---

## 📌 Próximas mejoras

- [ ] Protección CSRF en todos los formularios
- [ ] Páginas de error personalizadas (404, 403, 500)
- [ ] Mejoras en el diseño y responsive
- [ ] Buscador de estudiantes y cursos
- [ ] Exportar datos a PDF/Excel
- [ ] Uso de variables de entorno (`.env`)

---

## 👨‍💻 Autor

**Andy Pitterson** – [GitHub](https://github.com/SoyAxdx)

---

## 📄 Licencia

Este proyecto es de uso académico para la asignatura **Desarrollo de Software VII** – Universidad Tecnológica de Panamá, Centro Regional de Bocas del Toro.