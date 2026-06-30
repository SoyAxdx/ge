<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #1e6b3a;
        }
        .stat-card .numero {
            font-size: 32px;
            font-weight: bold;
            color: #1e6b3a;
        }
        .stat-card .label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .stat-card .promedio {
            font-size: 28px;
            font-weight: bold;
            color: #f39c12;
        }
        .row-dos-columnas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .lista-reciente {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .lista-reciente h4 {
            margin-bottom: 10px;
            color: #1e6b3a;
        }
        .lista-reciente ul {
            list-style: none;
            padding: 0;
        }
        .lista-reciente li {
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }
        .lista-reciente li:last-child {
            border-bottom: none;
        }
        .badge {
            background: #1e6b3a;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
        }
        @media (max-width: 768px) {
            .row-dos-columnas {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>📚 Gestión Escolar</h1>
            <div class="user-info">
                <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></strong>
                (<?php echo htmlspecialchars($_SESSION['usuario_rol'] ?? 'estudiante'); ?>)
                <a href="index.php?action=logout" class="btn btn-secondary" style="display:inline-block; padding:5px 15px; width:auto; margin-left:10px;">Cerrar Sesión</a>
            </div>
        </div>

        <!-- ==========================================
        BOTONES DE NAVEGACIÓN
        ========================================== -->
        <div style="display:flex; gap:20px; margin-bottom:30px; flex-wrap:wrap;">
            <a href="index.php?action=estudiantes" class="btn btn-primary" style="display:inline-block; width:auto; padding:12px 25px;">👨‍🎓 Gestionar Estudiantes</a>
            <a href="index.php?action=cursos" class="btn btn-primary" style="display:inline-block; width:auto; padding:12px 25px;">📖 Gestionar Cursos</a>
            <a href="index.php?action=inscripciones" class="btn btn-primary" style="display:inline-block; width:auto; padding:12px 25px;">📋 Gestionar Inscripciones</a>
        </div>

        <!-- ==========================================
        ESTADÍSTICAS GENERALES
        ========================================== -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="numero"><?= $total_estudiantes ?? 0 ?></div>
                <div class="label">👨‍🎓 Estudiantes</div>
            </div>
            <div class="stat-card">
                <div class="numero"><?= $total_cursos ?? 0 ?></div>
                <div class="label">📖 Cursos</div>
            </div>
            <div class="stat-card">
                <div class="numero"><?= $total_inscripciones ?? 0 ?></div>
                <div class="label">📋 Inscripciones</div>
            </div>
            <div class="stat-card">
                <div class="promedio"><?= $promedio_notas ?? 0 ?></div>
                <div class="label">📊 Promedio de Notas</div>
            </div>
        </div>

        <!-- ==========================================
        FILA DE DOS COLUMNAS
        ========================================== -->
        <div class="row-dos-columnas">

            <!-- ÚLTIMOS INSCRITOS -->
            <div class="lista-reciente">
                <h4>🕐 Últimos inscritos</h4>
                <?php if (empty($ultimos_inscritos)): ?>
                    <p style="color:#999; font-size:14px;">No hay inscripciones recientes.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($ultimos_inscritos as $insc): ?>
                            <li>
                                <strong><?= htmlspecialchars($insc['estudiante_nombre'] . ' ' . $insc['estudiante_apellido']) ?></strong>
                                <span class="badge"><?= htmlspecialchars($insc['curso_nombre']) ?></span>
                                <br>
                                <small style="color:#999;"><?= $insc['fecha_inscripcion'] ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- CURSOS POPULARES -->
            <div class="lista-reciente">
                <h4>🔥 Cursos con más estudiantes</h4>
                <?php if (empty($cursos_populares)): ?>
                    <p style="color:#999; font-size:14px;">No hay cursos con inscripciones aún.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($cursos_populares as $curso): ?>
                            <li>
                                <strong><?= htmlspecialchars($curso['nombre']) ?></strong>
                                <span class="badge"><?= $curso['total_estudiantes'] ?> estudiantes</span>
                                <br>
                                <small style="color:#999;">Código: <?= htmlspecialchars($curso['codigo']) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div>

        <!-- ==========================================
        PIE DE PÁGINA
        ========================================== -->
        <div style="background:#f9f9f9; padding:20px; border-radius:6px; border:1px solid #e0e0e0; margin-top:20px;">
            <h3 style="margin-bottom:15px;">📊 Resumen del Sistema</h3>
            <p style="text-align:left;">Sistema de gestión escolar desarrollado en PHP con POO y PDO.</p>
        </div>
    </div>
</body>
</html>