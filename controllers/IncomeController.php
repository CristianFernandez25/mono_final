<?php
require_once 'models/ReportModel.php';
require_once 'models/IncomeModel.php';

class IncomeController {
    private $reportModel;
    private $incomeModel;

    public function __construct() {
        $this->reportModel = new ReportModel();
        $this->incomeModel = new IncomeModel();
    }

    public function index() {
        $reports = $this->reportModel->getAllReports();
        
        // Cargar la vista para mostrar todos los reportes
        include 'views/income/index.php';
    }

    public function create() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $month = $_POST['month'];
            $year = $_POST['year'];
            $value = floatval($_POST['value']);
            
            // Validaciones
            if ($value <= 0) {
                $error = "El ingreso no puede ser menor o igual a cero.";
            } else {
                // Verificar si ya existe un reporte para este mes y año
                $report = $this->reportModel->getReportByMonthYear($month, $year);
                
                if ($report) {
                    // Verificar si ya existe un ingreso para este reporte
                    $income = $this->incomeModel->getIncomeByReportId($report->getId());
                    
                    if ($income) {
                        $error = "Ya existe un ingreso registrado para {$month} de {$year}.";
                    } else {
                        // Crear nuevo ingreso
                        $this->incomeModel->createIncome($value, $report->getId());
                        $success = "Ingreso registrado correctamente para {$month} de {$year}.";
                    }
                } else {
                    // Crear nuevo reporte
                    $reportId = $this->reportModel->createReport($month, $year);
                    
                    // Crear nuevo ingreso
                    $this->incomeModel->createIncome($value, $reportId);
                    $success = "Ingreso registrado correctamente para {$month} de {$year}.";
                }
            }
        }
        
        // Cargar la vista del formulario
        include 'views/income/create.php';
    }

    public function edit($id) {
        $error = '';
        $success = '';
        
        // Obtener el reporte por ID
        $report = $this->reportModel->getReportById($id);
        
        if (!$report) {
            header('Location: index.php?controller=income&action=index');
            exit;
        }
        
        // Obtener el ingreso asociado al reporte
        $income = $this->incomeModel->getIncomeByReportId($report->getId());
        
        if (!$income) {
            header('Location: index.php?controller=income&action=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = floatval($_POST['value']);
            
            // Validaciones
            if ($value <= 0) {
                $error = "El ingreso no puede ser menor o igual a cero.";
            } else {
                // Actualizar el ingreso
                $this->incomeModel->updateIncome($income->getId(), $value);
                $success = "Ingreso actualizado correctamente.";
                
                // Actualizar el objeto income con el nuevo valor
                $income->setValue($value);
            }
        }
        
        // Cargar la vista de edición
        include 'views/income/edit.php';
    }
}
?>