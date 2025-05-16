<?php
require_once 'drivers/db.php';
require_once 'entities/Reports.php';
require_once 'entities/Income.php';
require_once 'entities/Bills.php';
require_once 'entities/Category.php';

class CreateReportModel {
    private $connection;

    public function __construct() {
        $db = new db();
        $this->connection = $db->getConnection();
    }

    /**
     * Obtiene el ID del reporte para un mes y año específico
     */
    public function getReportId($month, $year) {
        $query = "SELECT id FROM reports WHERE month = :month AND year = :year";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':month', $month, PDO::PARAM_STR);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result['id'];
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Error en getReportId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene el ingreso para un mes y año específico
     */
    public function getIncome($month, $year) {
        $reportId = $this->getReportId($month, $year);
        
        if ($reportId) {
            $query = "SELECT i.* FROM income i 
                      JOIN reports r ON i.idReport = r.id 
                      WHERE r.month = :month AND r.year = :year";
            
            try {
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':month', $month, PDO::PARAM_STR);
                $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    $income = new Income();
                    $income->setId($row['id']);
                    $income->setValue($row['value']);
                    $income->setIdReport($row['idReport']);
                    
                    return $income;
                }
            } catch (PDOException $e) {
                error_log("Error en getIncome: " . $e->getMessage());
                return null;
            }
        }
        
        return null;
    }

    /**
     * Obtiene todos los gastos para un mes y año específico
     */
    public function getBills($month, $year) {
        $reportId = $this->getReportId($month, $year);
        $bills = [];
        
        if ($reportId) {
            $query = "SELECT b.*, c.name as categoryName, c.percentage as categoryPercentage 
                      FROM bills b 
                      JOIN categories c ON b.idCategory = c.id 
                      WHERE b.idReport = :reportId";
            
            try {
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':reportId', $reportId, PDO::PARAM_INT);
                $stmt->execute();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $bill = [
                        'id' => $row['id'],
                        'value' => $row['value'],
                        'idCategory' => $row['idCategory'],
                        'idReport' => $row['idReport'],
                        'categoryName' => $row['categoryName'],
                        'categoryPercentage' => $row['categoryPercentage']
                    ];
                    $bills[] = $bill;
                }
            } catch (PDOException $e) {
                error_log("Error en getBills: " . $e->getMessage());
                return [];
            }
        }
        
        return $bills;
    }

    /**
     * Calcula el total de gastos por cada categoría para un mes y año específico
     */
    public function calculateTotalByCategory($month, $year) {
        $reportId = $this->getReportId($month, $year);
        $totalByCategory = [];
        
        if ($reportId) {
            $query = "SELECT c.id, c.name, c.percentage, SUM(b.value) as totalValue 
                      FROM bills b 
                      JOIN categories c ON b.idCategory = c.id 
                      WHERE b.idReport = :reportId 
                      GROUP BY c.id";
            
            try {
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':reportId', $reportId, PDO::PARAM_INT);
                $stmt->execute();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $totalByCategory[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'percentage' => $row['percentage'],
                        'totalValue' => $row['totalValue']
                    ];
                }
            } catch (PDOException $e) {
                error_log("Error en calculateTotalByCategory: " . $e->getMessage());
                return [];
            }
        }
        
        return $totalByCategory;
    }

    /**
     * Obtiene todas las categorías que no tienen gastos registrados en un mes y año específico
     */
    public function getCategoriesWithoutBills($month, $year) {
        $reportId = $this->getReportId($month, $year);
        $categories = [];
        
        if ($reportId) {
            $query = "SELECT c.* FROM categories c 
                      WHERE c.id NOT IN (
                          SELECT DISTINCT idCategory FROM bills WHERE idReport = :reportId
                      )";
            
            try {
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':reportId', $reportId, PDO::PARAM_INT);
                $stmt->execute();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $category = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'percentage' => $row['percentage']
                    ];
                    $categories[] = $category;
                }
            } catch (PDOException $e) {
                error_log("Error en getCategoriesWithoutBills: " . $e->getMessage());
                return [];
            }
        }
        
        return $categories;
    }

    /**
     * Calcula el total de gastos para un mes y año específico
     */
    public function calculateTotalExpenses($month, $year) {
        $reportId = $this->getReportId($month, $year);
        $total = 0;
        
        if ($reportId) {
            $query = "SELECT SUM(value) as total FROM bills WHERE idReport = :reportId";
            
            try {
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':reportId', $reportId, PDO::PARAM_INT);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    $total = $row['total'] ?: 0;
                }
            } catch (PDOException $e) {
                error_log("Error en calculateTotalExpenses: " . $e->getMessage());
                return 0;
            }
        }
        
        return $total;
    }

    /**
     * Calcula el porcentaje de ahorro basado en ingresos y gastos
     */
    public function calculateSavingsPercentage($income, $totalExpenses) {
        if ($income > 0) {
            return (($income - $totalExpenses) / $income) * 100;
        }
        return 0;
    }

    /**
     * Analiza si una categoría excede su porcentaje máximo de gasto
     */
    public function analyzeCategorySpending($categoryTotals, $income) {
        $analysis = [];
        
        foreach ($categoryTotals as $category) {
            $spentPercentage = ($category['totalValue'] / $income) * 100;
            $exceeds = $spentPercentage > $category['percentage'];
            
            $analysis[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'maxPercentage' => $category['percentage'],
                'spentValue' => $category['totalValue'],
                'spentPercentage' => $spentPercentage,
                'exceeds' => $exceeds,
                'suggestedReduction' => $exceeds ? 
                    $category['totalValue'] - (($category['percentage'] / 100) * $income) : 0
            ];
        }
        
        return $analysis;
    }

    /**
     * Genera un reporte completo para un mes y año específico
     */
    public function generateReport($month, $year) {
        $report = [
            'month' => $month,
            'year' => $year,
            'income' => 0,
            'totalExpenses' => 0,
            'savings' => 0,
            'savingsPercentage' => 0,
            'savingsWarning' => false,
            'bills' => [],
            'categoryAnalysis' => [],
            'categoriesWithoutBills' => []
        ];
        
        // Obtener ingreso
        $income = $this->getIncome($month, $year);
        if ($income) {
            $report['income'] = $income->getValue();
            
            // Obtener gastos
            $report['bills'] = $this->getBills($month, $year);
            
            // Calcular total de gastos
            $report['totalExpenses'] = $this->calculateTotalExpenses($month, $year);
            
            // Calcular ahorro
            $report['savings'] = $report['income'] - $report['totalExpenses'];
            $report['savingsPercentage'] = $this->calculateSavingsPercentage(
                $report['income'], 
                $report['totalExpenses']
            );
            
            // Verificar si el ahorro es menor al 10% del ingreso
            $report['savingsWarning'] = $report['savingsPercentage'] < 10;
            
            // Calcular total por categoría
            $categoryTotals = $this->calculateTotalByCategory($month, $year);
            
            // Analizar gastos por categoría
            $report['categoryAnalysis'] = $this->analyzeCategorySpending(
                $categoryTotals, 
                $report['income']
            );
            
            // Obtener categorías sin gastos
            $report['categoriesWithoutBills'] = $this->getCategoriesWithoutBills($month, $year);
        }
        
        return $report;
    }

    /**
     * Obtiene los meses y años disponibles para generar reportes
     */
    public function getAvailableReports() {
        $query = "SELECT DISTINCT r.month, r.year FROM reports r 
                  JOIN income i ON r.id = i.idReport 
                  ORDER BY r.year, FIELD(r.month, 
                  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')";
        
        try {
            $stmt = $this->connection->query($query);
            $reports = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reports[] = [
                    'month' => $row['month'],
                    'year' => $row['year']
                ];
            }
            
            return $reports;
        } catch (PDOException $e) {
            error_log("Error en getAvailableReports: " . $e->getMessage());
            return [];
        }
    }
}
?>