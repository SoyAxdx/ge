<?php
$titulo = 'Cursos - Gestión Escolar';
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
                <a href="index.php?action=estudiantes" class="nav-link">
                    <i class="bi bi-people-fill"></i> Estudiantes
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=cursos" class="nav-link active">
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
            <h1>📖 Gestión de Cursos</h1>
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

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="index.php?action=curso_crear" class="btn btn-agregar">
                    <i class="bi bi-plus-circle"></i> Nuevo Curso
                </a>
                <a href="index.php?action=exportar_cursos_pdf" class="btn btn-danger">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>
                <a href="index.php?action=exportar_cursos_excel" class="btn btn-success">
                    <i class="bi bi-file-excel"></i> Excel
                </a>
            </div>

            <form method="GET" action="index.php" class="d-flex gap-2">
                <input type="hidden" name="action" value="buscar_cursos">
                <input type="text" name="termino" class="form-control" placeholder="Buscar por nombre o código..." 
                       value="<?= htmlspecialchars($_GET['termino'] ?? '') ?>" style="width: 280px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <?php if (isset($_GET['termino']) && !empty($_GET['termino'])): ?>
                    <a href="index.php?action=cursos" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (empty($cursos)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-book" style="font-size: 48px;"></i>
                <p class="mt-3">No hay cursos registrados aún.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Créditos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 1; foreach ($cursos as $c): ?>
                        <tr>
                            <td><?= $contador++ ?></td>
                            <td><?= htmlspecialchars($c['codigo']) ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars(substr($c['descripcion'] ?? '', 0, 50)) . (strlen($c['descripcion'] ?? '') > 50 ? '...' : '') ?></td>
                            <td><?= $c['creditos'] ?></td>
                            <td>
                                <a href="index.php?action=curso_editar&id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=curso_eliminar&id=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este curso?')">
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