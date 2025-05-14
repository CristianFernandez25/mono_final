<?php
require_once 'drivers/db.php';
require_once 'entities/Income.php';

class IncomeModel {
    private $connection;

    public function __construct() {
        $db = new db();
        $this->connection = $db->getConnection();
    }

    public function getIncomeByReportId($reportId) {
        $query = "SELECT * FROM income WHERE idReport = :reportId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':reportId', $reportId);
        $stmt->execute();
        
        $result = $stmt->fetch();
        
        if ($result) {
            return new Income($result['id'], $result['value'], $result['idReport']);
        }
        
        return null;
    }

    public function createIncome($value, $reportId) {
        $query = "INSERT INTO income (value, idReport) VALUES (:value, :reportId)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':reportId', $reportId);
        $stmt->execute();
        
        return $this->connection->lastInsertId();
    }

    public function updateIncome($id, $value) {
        $query = "UPDATE income SET value = :value WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>