<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cursos - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>📖 Gestión de Cursos</h1>
            <a href="index.php?action=dashboard" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver al Dashboard</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="top-bar">
            <a href="index.php?action=curso_crear" class="btn btn-primary" style="display:inline-block; width:auto; padding:8px 20px;">➕ Nuevo Curso</a>
        </div>

        <?php if (empty($cursos)): ?>
            <div class="mensaje-vacio">No hay cursos registrados aún.</div>
        <?php else: ?>
            <table>
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
                        <td><?= htmlspecialchars(substr($c['descripcion'], 0, 50)) . (strlen($c['descripcion']) > 50 ? '...' : '') ?></td>
                        <td><?= $c['creditos'] ?></td>
                        <td>
                            <a href="index.php?action=curso_editar&id=<?= $c['id'] ?>" class="btn-small btn-editar">✏️ Editar</a>
                            <a href="index.php?action=curso_eliminar&id=<?= $c['id'] ?>" class="btn-small btn-eliminar" onclick="return confirm('¿Eliminar este curso?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>