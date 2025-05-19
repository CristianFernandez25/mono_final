<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos - Crear Categoría</title>
    <link rel="stylesheet" href="views/css/style.css">
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>Control de Gastos Mensuales</h1>
            <nav>
                <ul>
                    <li><a href="index.php?controller=income&action=index">Ingresos</a></li>
                    <li><a href="index.php?controller=income&action=create">Registrar Ingreso</a></li>
                    <li><a href="index.php?controller=category&action=index">Categorías</a></li>
                    <li><a href="index.php?controller=Bills&action=index">Gastos</a></li>
                    <li><a href="index.php?controller=Report&action=index">Reportes</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="content">
                <h2>Crear Nueva Categoría</h2>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                        <?php
                        echo $_SESSION['message']['text'];
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?controller=Category&action=store" method="POST" class="form">
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" name="name" id="name" required>
                        <small>no se permiten caracteres especiales</small>

                    </div>

                    <div class="form-group">
                        <label for="percentage">Porcentaje (%):</label>
                        <input type="number" name="percentage" id="percentage" min="0.01" max="100" step="0.01" required>
                        <small>El porcentaje debe ser mayor que cero y no superar el 100%</small>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Crear Categoría</button>
                        <a href="index.php?controller=Category&action=index" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Control de Gastos Mensuales</p>
        </footer>
    </div>
</body>

</html>