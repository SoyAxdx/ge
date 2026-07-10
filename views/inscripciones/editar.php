<?php
/** @var array $inscripcion Datos de la inscripción a editar */
$titulo = 'Editar Nota - Gestión Escolar';
include_once __DIR__ . '/../layout/header.php';
?>

<div class="dashboard-wrapper">
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
                <a href="index.php?action=estudiantes" class="nav-link">
                    <i class="bi bi-people-fill"></i> Estudiantes
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=cursos" class="nav-link">
                    <i class="bi bi-book-fill"></i> Cursos
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?action=inscripciones" class="nav-link active">
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

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>✏️ Editar Nota</h1>
            <a href="index.php?action=inscripciones" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card p-4">
            <form method="POST" action="index.php?action=inscripcion_actualizar">
                <?php echo campoTokenCSRF(); ?>
                <input type="hidden" name="id" value="<?= $inscripcion['id'] ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Estudiante:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($inscripcion['estudiante_nombre'] . ' ' . $inscripcion['estudiante_apellido']) ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Curso:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($inscripcion['curso_nombre']) ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nota_final">Nota Final (0-100):</label>
                    <input type="number" id="nota_final" name="nota_final" class="form-control" step="0.01" min="0" max="100" value="<?= $inscripcion['nota_final'] ?? '' ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Nota</button>
            </form>
        </div>
    </main>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>