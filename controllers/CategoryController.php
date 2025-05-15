<?php
class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    // Método para mostrar la lista de categorías
    public function index() {
        $categories = $this->categoryModel->getAllCategories();
        require_once 'views/categories/index.php';
        include 'views/income/index.php';
    }

    // Método para mostrar el formulario de creación
    public function create() {
        require_once 'views/categories/create.php';
    }

    // Método para procesar la creación de una categoría
    public function store() {
        // Validación básica
        if (!isset($_POST['name']) || !isset($_POST['percentage'])) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Todos los campos son obligatorios.'
            ];
            header('Location: index.php?controller=Category&action=create');
            return;
        }

        $name = $_POST['name'];
        $percentage = $_POST['percentage'];

        // Validar que el nombre no esté vacío
        if (empty($name)) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'El nombre de la categoría no puede estar vacío.'
            ];
            header('Location: index.php?controller=Category&action=create');
            return;
        }

        // Validar que el porcentaje sea mayor que cero y no supere el 100%
        if (floatval($percentage) <= 0 || floatval($percentage) > 100) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'El porcentaje debe ser mayor que cero y no superar el 100%.'
            ];
            header('Location: index.php?controller=Category&action=create');
            return;
        }

        // Crear la categoría
        $success = $this->categoryModel->createCategory($name, $percentage);

        if ($success) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Categoría creada correctamente.'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Error al crear la categoría.'
            ];
        }

        header('Location: index.php?controller=Category&action=index');
    }

    // Método para mostrar el formulario de edición
    public function edit() {
        if (!isset($_GET['id'])) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'ID de categoría no proporcionado.'
            ];
            header('Location: index.php?controller=Category&action=index');
            return;
        }

        $categoryId = $_GET['id'];
        $category = $this->categoryModel->getCategoryById($categoryId);
        
        if (!$category) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'La categoría no existe.'
            ];
            header('Location: index.php?controller=Category&action=index');
            return;
        }
        
        // Verificar si la categoría está siendo utilizada
        $isInUse = $this->categoryModel->isCategoryInUse($categoryId);
        
        require_once 'views/categories/edit.php';
    }

    // Método para procesar la actualización de una categoría
    public function update() {
        if (!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['percentage'])) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Datos incompletos para actualizar la categoría.'
            ];
            header('Location: index.php?controller=Category&action=index');
            return;
        }

        $categoryId = $_POST['id'];
        $name = $_POST['name'];
        $percentage = $_POST['percentage'];

        // Validar que el nombre no esté vacío
        if (empty($name)) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'El nombre de la categoría no puede estar vacío.'
            ];
            header("Location: index.php?controller=Category&action=edit&id=$categoryId");
            return;
        }

        // Validar que el porcentaje sea mayor que cero y no supere el 100%
        if (floatval($percentage) <= 0 || floatval($percentage) > 100) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'El porcentaje debe ser mayor que cero y no superar el 100%.'
            ];
            header("Location: index.php?controller=Category&action=edit&id=$categoryId");
            return;
        }

        // Verificar si la categoría está siendo utilizada
        $isInUse = $this->categoryModel->isCategoryInUse($categoryId);
        
        if ($isInUse) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'No se puede modificar la categoría porque está siendo utilizada en gastos.'
            ];
            header("Location: index.php?controller=Category&action=index");
            return;
        }

        // Actualizar la categoría
        $success = $this->categoryModel->updateCategory($categoryId, $name, $percentage);

        if ($success) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Categoría actualizada correctamente.'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Error al actualizar la categoría.'
            ];
        }

        header('Location: index.php?controller=Category&action=index');
    }

    // Método para eliminar una categoría
    public function delete() {
        if (!isset($_GET['id'])) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'ID de categoría no proporcionado.'
            ];
            header('Location: index.php?controller=Category&action=index');
            return;
        }

        $categoryId = $_GET['id'];
        
        // Verificar si la categoría está siendo utilizada
        $isInUse = $this->categoryModel->isCategoryInUse($categoryId);
        
        if ($isInUse) {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'No se puede eliminar la categoría porque está siendo utilizada en gastos.'
            ];
            header('Location: index.php?controller=Category&action=index');
            return;
        }
        
        // Eliminar la categoría
        $success = $this->categoryModel->deleteCategory($categoryId);

        if ($success) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Categoría eliminada correctamente.'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Error al eliminar la categoría.'
            ];
        }

        header('Location: index.php?controller=Category&action=index');
    }
}
?>