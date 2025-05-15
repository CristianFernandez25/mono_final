<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos | Listado de Gastos</title>
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
                    <li><a href="index.php?controller=Bills&action=index" class="active">Gastos</a></li>
                    <li><a href="index.php?controller=Report&action=index">Reportes</a></li>
                </ul>
            </nav>
        </header>     
        
        <main>
            <h2>Listado de Gastos</h2>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div>
                <a href="index.php?controller=bills&action=create" class="btn btn-primary">Registrar nuevo gasto</a>
            </div>
            
            <?php if(empty($bills)): ?>
                <div class="alert alert-danger">
                    No hay gastos registrados.
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Año</th>
                            <th>Categoría</th>
                            <th>Valor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bills as $bill): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bill['month']); ?></td>
                                <td><?php echo htmlspecialchars($bill['year']); ?></td>
                                <td><?php echo htmlspecialchars($bill['category']); ?></td>
                                <td>$<?php echo number_format($bill['value'], 2, ',', '.'); ?></td>
                                <td>
                                    <a href="index.php?controller=bills&action=edit&id=<?php echo $bill['id']; ?>" class="btn btn-edit">Editar</a>
                                    <button type="button" class="btn" onclick="confirmarEliminar(<?php echo $bill['id']; ?>, '<?php echo htmlspecialchars($bill['category']); ?>', '<?php echo $bill['value']; ?>', '<?php echo htmlspecialchars($bill['month']); ?>', '<?php echo htmlspecialchars($bill['year']); ?>')">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <!-- Script para confirmar eliminación sin usar modales -->
            <script>
                function confirmarEliminar(id, categoria, valor, mes, anio) {
                    if (confirm('¿Está seguro de que desea eliminar este gasto de ' + categoria + ' por $' + valor + ' de ' + mes + ' ' + anio + '?')) {
                        // Crear un formulario dinámicamente
                        var form = document.createElement('form');
                        form.method = 'post';
                        form.action = 'index.php?controller=bills&action=delete';
                        
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'id';
                        input.value = id;
                        
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            </script>
        </main>
        
        <footer>
            Control de Gastos © 2025
        </footer>
    </div>
</body>
</html>