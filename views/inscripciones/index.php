<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripciones - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>📋 Gestión de Inscripciones</h1>
            <a href="index.php?action=dashboard" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver al Dashboard</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- ==========================================
        TOP BAR: Botones + Búsqueda + Exportación
        ========================================== -->
        <div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 18px;">
            
            <div class="d-flex gap-2 flex-wrap" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="index.php?action=inscripcion_crear" class="btn btn-agregar">
                    <i class="bi bi-plus-circle"></i> Nueva Inscripción
                </a>
                <!-- Botones de exportación para Inscripciones -->
                <a href="index.php?action=exportar_inscripciones_pdf" class="btn btn-danger btn-sm" style="padding: 8px 16px; border-radius: 8px; text-decoration: none; color: white; background: #dc3545;">
                    📄 PDF
                </a>
                <a href="index.php?action=exportar_inscripciones_excel" class="btn btn-success btn-sm" style="padding: 8px 16px; border-radius: 8px; text-decoration: none; color: white; background: #28a745;">
                    📊 Excel
                </a>
            </div>

            <form method="GET" action="index.php" class="d-flex gap-2" style="display: flex; gap: 8px;">
                <input type="hidden" name="action" value="buscar_inscripciones">
                <input type="text" name="termino" class="form-control" placeholder="Buscar por estudiante o curso..." 
                       value="<?= htmlspecialchars($_GET['termino'] ?? '') ?>" style="width: 280px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc;">
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px; border-radius: 6px; border: none; background: #1a5276; color: white;">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <?php if (isset($_GET['termino']) && !empty($_GET['termino'])): ?>
                    <a href="index.php?action=inscripciones" class="btn btn-secondary btn-sm" style="padding: 8px 16px; border-radius: 6px; text-decoration: none; background: #e0e0e0; color: #333;">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (empty($inscripciones)): ?>
            <div class="mensaje-vacio">No hay inscripciones registradas aún.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Código</th>
                        <th>Fecha Inscripción</th>
                        <th>Nota</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $contador = 1; foreach ($inscripciones as $i): ?>
                    <tr>
                        <td><?= $contador++ ?></td>
                        <td><?= htmlspecialchars($i['estudiante_nombre'] . ' ' . $i['estudiante_apellido']) ?></td>
                        <td><?= htmlspecialchars($i['curso_nombre']) ?></td>
                        <td><?= htmlspecialchars($i['curso_codigo']) ?></td>
                        <td><?= $i['fecha_inscripcion'] ?></td>
                        <td><?= $i['nota_final'] !== null ? number_format($i['nota_final'], 2) : 'Sin nota' ?></td>
                        <td>
                            <a href="index.php?action=inscripcion_editar&id=<?= $i['id'] ?>" class="btn-small btn-editar">✏️ Nota</a>
                            <a href="index.php?action=inscripcion_eliminar&id=<?= $i['id'] ?>" class="btn-small btn-eliminar" onclick="return confirm('¿Eliminar esta inscripción?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>