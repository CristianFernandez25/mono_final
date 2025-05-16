<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Ingreso</title>
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
            <h2>Registrar Nuevo Ingreso</h2>
            
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
            
            <form action="index.php?controller=income&action=create" method="post" class="form">
                <div class="form-group">
                    <label for="month">Mes:</label>
                    <select name="month" id="month" required>
                        <option value="">Seleccione un mes</option>
                        <option value="Enero">Enero</option>
                        <option value="Febrero">Febrero</option>
                        <option value="Marzo">Marzo</option>
                        <option value="Abril">Abril</option>
                        <option value="Mayo">Mayo</option>
                        <option value="Junio">Junio</option>
                        <option value="Julio">Julio</option>
                        <option value="Agosto">Agosto</option>
                        <option value="Septiembre">Septiembre</option>
                        <option value="Octubre">Octubre</option>
                        <option value="Noviembre">Noviembre</option>
                        <option value="Diciembre">Diciembre</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="year">Año:</label>
                    <select name="year" id="year" required>
                        <option value="">Seleccione un año</option>
                        <?php 
                        $currentYear = date('Y');
                        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
                            echo "<option value=\"$i\">$i</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="value">Valor del Ingreso:</label>
                    <input type="number" name="value" id="value" min="0.01" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Registrar Ingreso</button>
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