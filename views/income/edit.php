<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ingreso</title>
    <link rel="stylesheet" href="views/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Control de Gastos Mensuales</h1>
            <nav>
                <ul>
                    <li><a href="index.php?controller=income&action=index">Ingresos</a></li>
                    <li><a href="index.php?controller=income&action=create">Registrar Ingreso</a></li>
                </ul>
            </nav>
        </header>
        
        <main>
            <h2>Editar Ingreso - <?php echo $report->getMonth() . ' ' . $report->getYear(); ?></h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form action="index.php?controller=income&action=edit&id=<?php echo $report->getId(); ?>" method="post" class="form">
                <div class="form-group">
                    <label for="month">Mes:</label>
                    <input type="text" id="month" value="<?php echo $report->getMonth(); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="year">Año:</label>
                    <input type="text" id="year" value="<?php echo $report->getYear(); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="value">Valor del Ingreso:</label>
                    <input type="number" name="value" id="value" min="0.01" step="0.01" value="<?php echo $income->getValue(); ?>" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Actualizar Ingreso</button>
                    <a href="index.php?controller=income&action=index" class="btn">Cancelar</a>
                </div>
            </form>
        </main>
        
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Control de Gastos Mensuales</p>
        </footer>
    </div>
</body>
</html>