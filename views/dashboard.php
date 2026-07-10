<?php
$titulo = 'Dashboard - Gestión Escolar';
include_once __DIR__ . '/layout/header.php';
?>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-mortarboard-fill"></i> GE
        </div>

        <!-- Navegación principal -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="index.php?action=dashboard" class="nav-link active">
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
                <a href="index.php?action=inscripciones" class="nav-link">
                    <i class="bi bi-clipboard-data-fill"></i> Inscripciones
                </a>
            </li>
        </ul>

        <!-- Espaciador para empujar el contenido hacia abajo -->
        <div style="flex: 1;"></div>

        <!-- Perfil de usuario, Modo Oscuro y Cerrar Sesión (abajo del todo) -->
        <div class="sidebar-footer">
            <!-- Información del usuario -->
            <div class="user-info">
                <div class="avatar">
                    <?php echo strtoupper(substr($_SESSION['usuario_nombre'] ?? 'U', 0, 1)); ?>
                </div>
                <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></strong>
                <br>
                <span class="badge bg-secondary"><?php echo htmlspecialchars($_SESSION['usuario_rol'] ?? 'estudiante'); ?></span>
            </div>

            <!-- Botón Modo Oscuro/Claro -->
            <button id="themeToggle" class="btn btn-outline-secondary w-100 mb-2" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-moon-stars"></i> <span id="themeText">Modo Oscuro</span>
            </button>

            <!-- Botón Cerrar Sesión -->
            <a href="index.php?action=logout" class="btn btn-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <h1 class="mb-4">📊 Dashboard</h1>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="stat-number"><?= $total_estudiantes ?? 0 ?></p>
                            <p class="stat-label">Estudiantes</p>
                        </div>
                        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card green">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="stat-number"><?= $total_cursos ?? 0 ?></p>
                            <p class="stat-label">Cursos</p>
                        </div>
                        <div class="stat-icon"><i class="bi bi-book-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card orange">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="stat-number"><?= $total_inscripciones ?? 0 ?></p>
                            <p class="stat-label">Inscripciones</p>
                        </div>
                        <div class="stat-icon"><i class="bi bi-clipboard-data-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card red">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="stat-number"><?= $promedio_notas ?? 0 ?></p>
                            <p class="stat-label">Promedio de Notas</p>
                        </div>
                        <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos inscritos y Cursos populares -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="table-container">
                    <h5><i class="bi bi-clock-history"></i> Últimos inscritos</h5>
                    <?php if (empty($ultimos_inscritos)): ?>
                        <p class="text-muted">No hay inscripciones recientes.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($ultimos_inscritos as $insc): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($insc['estudiante_nombre'] . ' ' . $insc['estudiante_apellido']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= $insc['fecha_inscripcion'] ?></small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($insc['curso_nombre']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-container">
                    <h5><i class="bi bi-fire"></i> Cursos populares</h5>
                    <?php if (empty($cursos_populares)): ?>
                        <p class="text-muted">No hay cursos con inscripciones aún.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($cursos_populares as $curso): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($curso['nombre']) ?></strong>
                                        <br>
                                        <small class="text-muted">Código: <?= htmlspecialchars($curso['codigo']) ?></small>
                                    </div>
                                    <span class="badge bg-success rounded-pill"><?= $curso['total_estudiantes'] ?> estudiantes</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>