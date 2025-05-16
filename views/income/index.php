<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ingresos</title>
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
            <h2>Listado de Ingresos Mensuales</h2>
            
            <?php if (empty($reports)): ?>
                <p>No hay ingresos registrados.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Año</th>
                            <th>Ingreso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <?php 
                            $income = $this->incomeModel->getIncomeByReportId($report->getId());
                            if ($income):
                            ?>
                                <tr>
                                    <td><?php echo $report->getMonth(); ?></td>
                                    <td><?php echo $report->getYear(); ?></td>
                                    <td>$<?php echo number_format($income->getValue(), 2, ',', '.'); ?></td>
                                    <td>
                                        <a href="index.php?controller=income&action=edit&id=<?php echo $report->getId(); ?>" class="btn btn-edit">Editar</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
        
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Control de Gastos Mensuales</p>
        </footer>
    </div>
</body>
</html>