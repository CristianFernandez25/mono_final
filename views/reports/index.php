<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Gastos | Reportes</title>
    <link rel="stylesheet" href="views/css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Reportes</h1>
            <nav>
                <ul>
                    <li><a href="index.php?controller=income&action=index">Ingresos</a></li>
                    <li><a href="index.php?controller=income&action=create">Registrar Ingreso</a></li>
                    <li><a href="index.php?controller=category&action=index">Categorías</a></li>
                    <li><a href="index.php?controller=Bills&action=index">Gastos</a></li>
                    <li><a href="index.php?controller=Report&action=index" class="active">Reportes</a></li>
                </ul>
            </nav>
        </header>
        
        <main>
            <h2>Generación de Reportes</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="form">
                <form action="index.php?controller=Report&action=generate" method="post">
                    <div class="form-group">
                        <label for="month">Mes:</label>
                        <select name="month" id="month" required>
                            <option value="">Seleccione un mes</option>
                            <?php foreach($availableReports as $availableReport): ?>
                                <option value="<?php echo $availableReport['month']; ?>" <?php echo (isset($report['month']) && $report['month'] == $availableReport['month']) ? 'selected' : ''; ?>>
                                    <?php echo $availableReport['month']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="year">Año:</label>
                        <select name="year" id="year" required>
                            <option value="">Seleccione un año</option>
                            <?php 
                            $years = [];
                            foreach($availableReports as $availableReport) {
                                if(!in_array($availableReport['year'], $years)) {
                                    $years[] = $availableReport['year'];
                                    echo '<option value="'.$availableReport['year'].'" '.
                                         (isset($report['year']) && $report['year'] == $availableReport['year'] ? 'selected' : '').
                                         '>'.$availableReport['year'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Generar Reporte</button>
                    </div>
                </form>
            </div>
            
            <?php if(isset($report) && !empty($report) && isset($report['month']) && isset($report['year'])): ?>
                <div class="report-container">
                    <h3>Reporte: <?php echo $report['month'].' '.$report['year']; ?></h3>
                    
                    <div class="summary-box">
                        <div class="summary-item">
                            <h4>Ingresos</h4>
                            <p class="amount income">$<?php echo number_format(isset($report['income']) ? $report['income'] : 0, 2, ',', '.'); ?></p>
                        </div>
                        
                        <div class="summary-item">
                            <h4>Gastos</h4>
                            <p class="amount expenses">$<?php echo number_format(isset($report['totalExpenses']) ? $report['totalExpenses'] : 0, 2, ',', '.'); ?></p>
                        </div>
                        
                        <div class="summary-item">
                            <h4>Ahorros</h4>
                            <p class="amount savings <?php echo (isset($report['savingsWarning']) && $report['savingsWarning']) ? 'warning' : ''; ?>">
                                $<?php echo number_format(isset($report['savings']) ? $report['savings'] : 0, 2, ',', '.'); ?>
                                (<?php echo number_format(isset($report['savingsPercentage']) ? $report['savingsPercentage'] : 0, 2); ?>%)
                            </p>
                            <?php if(isset($report['savingsWarning']) && $report['savingsWarning']): ?>
                                <p class="warning-text">¡Advertencia! El ahorro es menor al 10% recomendado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h3>Análisis por Categoría</h3>
                    <?php if(isset($report['categoryAnalysis']) && !empty($report['categoryAnalysis'])): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Porcentaje Máximo</th>
                                    <th>Monto Gastado</th>
                                    <th>Porcentaje Actual</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($report['categoryAnalysis'] as $category): ?>
                                    <tr class="<?php echo ($category['exceeds']) ? 'exceeded' : ''; ?>">
                                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        <td><?php echo number_format($category['maxPercentage'], 2); ?>%</td>
                                        <td>$<?php echo number_format($category['spentValue'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format($category['spentPercentage'], 2); ?>%</td>
                                        <td>
                                            <?php if($category['exceeds']): ?>
                                                <span class="status warning">Excedido</span>
                                                <p class="suggestion">Reducir: $<?php echo number_format($category['suggestedReduction'], 2, ',', '.'); ?></p>
                                            <?php else: ?>
                                                <span class="status ok">OK</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay análisis de categorías disponible para este período.</div>
                    <?php endif; ?>
                    
                    <?php if(isset($report['categoriesWithoutBills']) && !empty($report['categoriesWithoutBills'])): ?>
                        <h3>Categorías sin Gastos</h3>
                        <ul class="categories-list">
                            <?php foreach($report['categoriesWithoutBills'] as $category): ?>
                                <li><?php echo htmlspecialchars($category['name']); ?> (<?php echo number_format($category['percentage'], 2); ?>%)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <h3>Detalles de Gastos</h3>
                    <?php if(!isset($report['bills']) || empty($report['bills'])): ?>
                        <div class="alert alert-info">No hay gastos registrados para este período.</div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Valor</th>
                                    <th>% del Ingreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($report['bills'] as $bill): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($bill['categoryName']); ?></td>
                                        <td>$<?php echo number_format($bill['value'], 2, ',', '.'); ?></td>
                                        <td><?php echo number_format(($bill['value'] / $report['income']) * 100, 2); ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
        
        <footer>
            Control de Gastos © 2025
        </footer>
    </div>
</body>
</html>