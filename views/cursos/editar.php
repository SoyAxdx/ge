<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso - Gestión Escolar</title>
    <link rel="icon" href="assets/img/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php $curso = $curso ?? []; ?>
    <div class="dashboard-container" style="max-width:600px;">
        <div class="dashboard-header">
            <h1>✏️ Editar Curso</h1>
            <a href="index.php?action=cursos" class="btn btn-secondary" style="display:inline-block; width:auto; padding:5px 15px;">← Volver</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=curso_actualizar">
            <?php echo campoTokenCSRF(); ?>
            <input type="hidden" name="id" value="<?= $curso['id'] ?>">

            <div class="form-group">
                <label>Código del Curso:</label>
                <input type="text" name="codigo" value="<?= htmlspecialchars($curso['codigo']) ?>" required>
                <small class="hint">Código único del curso</small>
            </div>

            <div class="form-group">
                <label>Nombre del Curso:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($curso['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="3"><?= htmlspecialchars($curso['descripcion']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Créditos:</label>
                <input type="number" name="creditos" min="1" max="6" value="<?= $curso['creditos'] ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Curso</button>
        </form>
    </div>
</body>
</html>