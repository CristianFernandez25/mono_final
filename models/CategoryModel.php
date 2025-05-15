<?php
require_once 'drivers/db.php';
require_once 'entities/Category.php';

class CategoryModel {
    private $connection;

    public function __construct() {
        $db = new db();
        $this->connection = $db->getConnection();
    }

    // Obtener todas las categorías
    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY name";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $categories = [];
            foreach ($categoriesData as $categoryData) {
                $category = new Category();
                $category->setId($categoryData['id']);
                $category->setName($categoryData['name']);
                $category->setPercentage($categoryData['percentage']);
                
                $categories[] = $category;
            }
            
            return $categories;
        } catch (PDOException $e) {
            error_log("Error en getAllCategories: " . $e->getMessage());
            return [];
        }
    }

    // Obtener una categoría por su ID
    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE id = :id";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$categoryData) {
                return null;
            }
            
            $category = new Category();
            $category->setId($categoryData['id']);
            $category->setName($categoryData['name']);
            $category->setPercentage($categoryData['percentage']);
            
            return $category;
        } catch (PDOException $e) {
            error_log("Error en getCategoryById: " . $e->getMessage());
            return null;
        }
    }

    // Verificar si una categoría está siendo usada en gastos
    public function isCategoryInUse($id) {
        $query = "SELECT COUNT(*) as count FROM bills WHERE idCategory = :id";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error en isCategoryInUse: " . $e->getMessage());
            return true; // Por seguridad, si hay error asumimos que está en uso
        }
    }

    // Crear una nueva categoría
    public function createCategory($name, $percentage) {
        $query = "INSERT INTO categories (name, percentage) VALUES (:name, :percentage)";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':percentage', $percentage, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en createCategory: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar una categoría existente
    public function updateCategory($id, $name, $percentage) {
        $query = "UPDATE categories SET name = :name, percentage = :percentage WHERE id = :id";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':percentage', $percentage, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en updateCategory: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar una categoría
    public function deleteCategory($id) {
        $query = "DELETE FROM categories WHERE id = :id";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en deleteCategory: " . $e->getMessage());
            return false;
        }
    }
}
?>