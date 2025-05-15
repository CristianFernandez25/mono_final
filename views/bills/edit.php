<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos | Editar Gasto</title>
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
            <h2>Editar Gasto</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="form">
                <form action="index.php?controller=bills&action=update" method="post">
                    <input type="hidden" name="id" value="<?php echo $bill['id']; ?>">
                    
                    <div class="form-group">
                        <label>Mes:</label>
                        <input type="text" value="<?php echo htmlspecialchars($bill['month']); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Año:</label>
                        <input type="text" value="<?php echo htmlspecialchars($bill['year']); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="idCategory">Categoría:</label>
                        <select name="idCategory" id="idCategory" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $bill['idCategory']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?> 
                                    (<?php echo number_format($category['percentage'], 2); ?>%)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="value">Valor:</label>
                        <input type="number" step="0.01" min="0.01" name="value" id="value" value="<?php echo $bill['value']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Actualizar gasto</button>
                        <a href="index.php?controller=bills&action=index" class="btn">Cancelar</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>