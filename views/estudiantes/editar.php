<?php
/** @var array $estudiante Datos del estudiante a editar */
$titulo = 'Editar Estudiante - Gestión Escolar';
include_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
    <img src="assets/img/logo.png" alt="GE - Gestión Escolar" class="logo-img">
    <span class="logo-text">GE</span>
</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="index.php?action=dashboard" class="nav-link">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=estudiantes" class="nav-link active">
                    <i class="bi bi-people-fill"></i> Estudiantes
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=cursos" class="nav-link">
                    <i class="bi bi-book-fill"></i> Cursos
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=inscripciones" class="nav-link">
                    <i class="bi bi-clipboard-data-fill"></i> Inscripciones
                </a>
            </li>
        </ul>

        <div style="flex: 1;"></div>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="avatar">
                    <?php echo strtoupper(substr($_SESSION['usuario_nombre'] ?? 'U', 0, 1)); ?>
                </div>
                <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></strong>
                <br>
                <span class="badge bg-secondary"><?php echo htmlspecialchars($_SESSION['usuario_rol'] ?? 'estudiante'); ?></span>
            </div>

            <button id="themeToggle" class="btn btn-outline-secondary w-100 mb-2" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-moon-stars"></i> <span id="themeText">Modo Oscuro</span>
            </button>

            <a href="index.php?action=logout" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>✏️ Editar Estudiante</h1>
            <a href="index.php?action=estudiantes" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card p-4">
            <form id="formEstudiante" method="POST" action="index.php?action=estudiante_actualizar" onsubmit="return validarFormulario()">
                <?php echo campoTokenCSRF(); ?>
                <input type="hidden" name="id" value="<?= $estudiante['id'] ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cedula">Cédula (formato: 0-000-0000, 0-00-00 o 0-00-000):</label>
                            <input type="text" id="cedula" name="cedula" class="form-control" value="<?= htmlspecialchars($estudiante['cedula']) ?>" required>
                            <small class="hint">Ejemplos válidos: 8-888-8888, 8-88-88, 8-88-888</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono">Teléfono (formato: xxxx-xxxx):</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" value="<?= htmlspecialchars($estudiante['telefono']) ?>" placeholder="Ej: 1234-5678">
                            <small class="hint">Formato: 4 dígitos, guión, 4 dígitos</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($estudiante['nombre']) ?>" required>
                            <small class="hint">Solo letras. Primera letra mayúscula.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" value="<?= htmlspecialchars($estudiante['apellido']) ?>" required>
                            <small class="hint">Solo letras. Primera letra mayúscula.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($estudiante['email']) ?>" placeholder="ejemplo@correo.com">
                            <small class="hint">Solo minúsculas. Debe contener @ y .com / .es</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" value="<?= $estudiante['fecha_nacimiento'] ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($estudiante['direccion']) ?>" placeholder="Dirección completa">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Estudiante</button>
            </form>
        </div>
    </main>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>