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

        <div class="top-bar">
            <a href="index.php?action=inscripcion_crear" class="btn btn-primary" style="display:inline-block; width:auto; padding:8px 20px;">➕ Nueva Inscripción</a>
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
                        <th>Fecha de Inscripción</th>
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