<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos - Categorías</title>
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
                <h2>Listado de Categorías</h2>
                
                <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                    <?php 
                    echo $_SESSION['message']['text']; 
                    unset($_SESSION['message']);
                    ?>
                </div>
                <?php endif; ?>
                
                <div class="actions">
                    <a href="index.php?controller=Category&action=create" class="btn btn-primary">Crear Nueva Categoría</a>
                </div>
                
                <?php if(empty($categories)): ?>
                <p>No hay categorías registradas.</p>
                <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Porcentaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($categories as $category): ?>
                            <?php $isInUse = $this->categoryModel->isCategoryInUse($category->getId()); ?>
                            <tr>
                                <td><?php echo $category->getName(); ?></td>
                                <td><?php echo $category->getPercentage(); ?>%</td>
                                <td class="actions">
                                    <?php if(!$isInUse): ?>
                                    <a href="index.php?controller=Category&action=edit&id=<?php echo $category->getId(); ?>" class="btn btn-sm btn-edit">Editar</a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $category->getId(); ?>)" class="btn btn-sm btn-delete">Eliminar</a>
                                    <?php else: ?>
                                    <span class="text-muted" title="Esta categoría está siendo utilizada en gastos y no puede ser modificada o eliminada">En uso</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>
        </main>
        
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Control de Gastos Mensuales</p>
        </footer>
    </div>
    
    <script>
        function confirmDelete(id) {
            if (confirm('¿Está seguro de que desea eliminar esta categoría?')) {
                window.location.href = 'index.php?controller=Category&action=delete&id=' + id;
            }
        }
    </script>
</body>
</html>