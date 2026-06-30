<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estudiantes - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>👨‍🎓 Gestión de Estudiantes</h1>
            <a href="index.php?action=dashboard" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver al Dashboard</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="top-bar">
            <a href="index.php?action=estudiante_crear" class="btn btn-primary" style="display:inline-block; width:auto; padding:8px 20px;">➕ Nuevo Estudiante</a>
        </div>

        <?php if (empty($estudiantes)): ?>
            <div class="mensaje-vacio">No hay estudiantes registrados aún.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>  <!-- Cambiado de ID a # -->
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
                        <td><?= $contador++ ?></td>  <!-- Muestra 1, 2, 3... -->
                        <td><?= htmlspecialchars($e['cedula']) ?></td>
                        <td><?= htmlspecialchars($e['nombre']) ?></td>
                        <td><?= htmlspecialchars($e['apellido']) ?></td>
                        <td><?= htmlspecialchars($e['email']) ?></td>
                        <td><?= htmlspecialchars($e['telefono']) ?></td>
                        <td>
                            <a href="index.php?action=estudiante_editar&id=<?= $e['id'] ?>" class="btn-small btn-editar">✏️ Editar</a>
                            <a href="index.php?action=estudiante_eliminar&id=<?= $e['id'] ?>" class="btn-small btn-eliminar" onclick="return confirm('¿Eliminar este estudiante?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>