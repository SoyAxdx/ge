<?php
$titulo = 'Estudiantes - Gestión Escolar';
include_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
    <img src="assets/img/logo.png" alt="GE - Gestión Escolar" class="logo-img">
    <span class="logo-text">Sistema de Gestión Escolar</span>
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
            <h1>👨‍🎓 Gestión de Estudiantes</h1>
            <a href="index.php?action=dashboard" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Top Bar: Botones + Búsqueda + Exportación -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="index.php?action=estudiante_crear" class="btn btn-agregar">
                    <i class="bi bi-plus-circle"></i> Nuevo Estudiante
                </a>
                <a href="index.php?action=exportar_estudiantes_pdf" class="btn btn-danger">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
                <a href="index.php?action=exportar_estudiantes_excel" class="btn btn-success">
                    <i class="bi bi-file-excel"></i> Excel
                </a>
            </div>

            <form method="GET" action="index.php" class="d-flex gap-2">
                <input type="hidden" name="action" value="buscar_estudiantes">
                <input type="text" name="termino" class="form-control" placeholder="Buscar por nombre, cédula o email..." 
                       value="<?= htmlspecialchars($_GET['termino'] ?? '') ?>" style="width: 280px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <?php if (isset($_GET['termino']) && !empty($_GET['termino'])): ?>
                    <a href="index.php?action=estudiantes" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabla de estudiantes -->
        <?php if (empty($estudiantes)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people" style="font-size: 48px;"></i>
                <p class="mt-3">No hay estudiantes registrados aún.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 1; foreach ($estudiantes as $e): ?>
                        <tr>
                            <td><?= $contador++ ?></td>
                            <td><?= htmlspecialchars($e['cedula']) ?></td>
                            <td><?= htmlspecialchars($e['nombre']) ?></td>
                            <td><?= htmlspecialchars($e['apellido']) ?></td>
                            <td><?= htmlspecialchars($e['email']) ?></td>
                            <td><?= htmlspecialchars($e['telefono']) ?></td>
                            <td>
                                <a href="index.php?action=estudiante_editar&id=<?= $e['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=estudiante_eliminar&id=<?= $e['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este estudiante?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>