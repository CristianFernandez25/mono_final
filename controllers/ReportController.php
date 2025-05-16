<?php
require_once 'models/CreateReportModel.php';

class ReportController {
    private $model;
    
    public function __construct() {
        $this->model = new CreateReportModel();
    }
    
    /**
     * Muestra la página de selección de reporte
     */
    public function index() {
        // Obtener los reportes disponibles
        $availableReports = $this->model->getAvailableReports();
        
        // Inicializar $report como un array vacío para evitar errores
        $report = [];
        
        // Cargar la vista
        require_once 'views/reports/index.php';
    }
    
    /**
     * Genera y muestra un reporte para el mes y año seleccionados
     */
    public function generate() {
        $month = isset($_POST['month']) ? $_POST['month'] : '';
        $year = isset($_POST['year']) ? intval($_POST['year']) : 0;
        
        if (empty($month) || $year <= 0) {
            // Si no se proporcionaron mes y año, redirigir al índice
            $_SESSION['error'] = 'Debe seleccionar un mes y un año válidos.';
            header('Location: index.php?controller=Report');
            exit;
        }
        
        // Generar el reporte
        $report = $this->model->generateReport($month, $year);
        
        // Asegurarnos que el mes y año seleccionados están en el reporte
        $report['month'] = $month;
        $report['year'] = $year;
        
        // Si no hay ingreso registrado para el mes y año seleccionados
        if ($report['income'] <= 0) {
            $_SESSION['error'] = "No hay ingresos registrados para $month de $year.";
            header('Location: index.php?controller=Report');
            exit;
        }
        
        // Obtener los reportes disponibles para mostrar en el formulario
        $availableReports = $this->model->getAvailableReports();
        
        // Cargar la vista
        require_once 'views/reports/index.php';
    }
}