<?php
require_once 'drivers/db.php';
require_once 'entities/Bills.php';

class BillModel {
    private $connection;

    public function __construct() {
        $db = new db();
        $this->connection = $db->getConnection();
    }

    
    // Obtener todos los gastos
    public function getAllBills() {
        try {
            $query = "SELECT b.id, b.value, c.name as category, r.month, r.year, c.id as idCategory, r.id as idReport 
                      FROM bills b 
                      JOIN categories c ON b.idCategory = c.id 
                      JOIN reports r ON b.idReport = r.id 
                      ORDER BY r.year DESC, r.month ASC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Obtener un gasto específico por ID
    public function getBillById($id) {
        try {
            $query = "SELECT b.id, b.value, c.name as category, r.month, r.year, c.id as idCategory, r.id as idReport 
                      FROM bills b 
                      JOIN categories c ON b.idCategory = c.id 
                      JOIN reports r ON b.idReport = r.id 
                      WHERE b.id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Obtener gastos por mes y año
    public function getBillsByMonthYear($month, $year) {
        try {
            $query = "SELECT b.id, b.value, c.name as category, r.month, r.year, c.id as idCategory, r.id as idReport, c.percentage 
                      FROM bills b 
                      JOIN categories c ON b.idCategory = c.id 
                      JOIN reports r ON b.idReport = r.id 
                      WHERE r.month = :month AND r.year = :year 
                      ORDER BY c.name ASC";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Crear un nuevo gasto
    public function createBill($value, $idCategory, $month, $year) {
        try {
            // Primero verificamos si existe un reporte para ese mes y año
            $reportId = $this->getReportId($month, $year);
            
            // Si no existe, lo creamos
            if (!$reportId) {
                $reportId = $this->createReport($month, $year);
                if (!$reportId) {
                    return false;
                }
            }
            
            // Ahora creamos el gasto
            $query = "INSERT INTO bills (value, idCategory, idReport) VALUES (:value, :idCategory, :idReport)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':idCategory', $idCategory);
            $stmt->bindParam(':idReport', $reportId);
            $stmt->execute();
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Actualizar un gasto existente
    public function updateBill($id, $value, $idCategory) {
        try {
            $query = "UPDATE bills SET value = :value, idCategory = :idCategory WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':idCategory', $idCategory);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Eliminar un gasto
    public function deleteBill($id) {
        try {
            $query = "DELETE FROM bills WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Obtener ID de un reporte por mes y año
    private function getReportId($month, $year) {
        try {
            $query = "SELECT id FROM reports WHERE month = :month AND year = :year";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Crear un nuevo reporte
    private function createReport($month, $year) {
        try {
            $query = "INSERT INTO reports (month, year) VALUES (:month, :year)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Obtener todos los reportes (meses y años)
    public function getAllReports() {
        try {
            $query = "SELECT id, month, year FROM reports ORDER BY year DESC, 
                      CASE month 
                         WHEN 'Enero' THEN 1 
                         WHEN 'Febrero' THEN 2 
                         WHEN 'Marzo' THEN 3 
                         WHEN 'Abril' THEN 4 
                         WHEN 'Mayo' THEN 5 
                         WHEN 'Junio' THEN 6 
                         WHEN 'Julio' THEN 7 
                         WHEN 'Agosto' THEN 8 
                         WHEN 'Septiembre' THEN 9 
                         WHEN 'Octubre' THEN 10 
                         WHEN 'Noviembre' THEN 11 
                         WHEN 'Diciembre' THEN 12 
                      END ASC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}