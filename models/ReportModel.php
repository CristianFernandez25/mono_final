<?php
require_once 'drivers/db.php';
require_once 'entities/Reports.php';
require_once 'models/entities/Income.php';
require_once 'models/entities/Bills.php';
require_once 'models/entities/Category.php';

class ReportModel {
    private $connection;
    private $reportEntity;
    private $incomeEntity;
    private $billEntity;
    private $categoryEntity;

    public function __construct() {
        $db = new db();
        $this->connection = $db->getConnection();
    }

    public function getReportByMonthYear($month, $year) {
        $query = "SELECT * FROM reports WHERE month = :month AND year = :year";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        
        $result = $stmt->fetch();
        
        if ($result) {
            return new Report($result['id'], $result['month'], $result['year']);
        }
        
        return null;
    }

    public function createReport($month, $year) {
        $query = "INSERT INTO reports (month, year) VALUES (:month, :year)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        
        return $this->connection->lastInsertId();
    }

    public function getAllReports() {
        $query = "SELECT * FROM reports ORDER BY year DESC, FIELD(month, 
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        $reports = [];
        $results = $stmt->fetchAll();
        
        foreach ($results as $result) {
            $reports[] = new Report($result['id'], $result['month'], $result['year']);
        }
        
        return $reports;
    }
    
    public function getReportById($id) {
        $query = "SELECT * FROM reports WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch();
        
        if ($result) {
            return new Report($result['id'], $result['month'], $result['year']);
        }
        
        return null;
    }
}
?>