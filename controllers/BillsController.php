<?php
class BillsController {
    private $billModel;
    private $categoryModel;
    private $incomeModel; // Añadir el modelo de ingresos
    
    public function __construct() {
        $this->billModel = new BillModel();
        $this->categoryModel = new CategoryModel();
        $this->incomeModel = new IncomeModel(); // Instanciar el modelo de ingresos
    }
    
    // Mostrar la lista de gastos
    public function index() {
        $bills = $this->billModel->getAllBills();
        require_once 'views/bills/index.php';
    }
    
    // Mostrar el formulario para crear un nuevo gasto
    public function create() {
        // Obtener los objetos Category del modelo
        $categoriesObjects = $this->categoryModel->getAllCategories();
        
        // Convertir los objetos a un formato array asociativo para la vista
        $categories = [];
        foreach ($categoriesObjects as $category) {
            $categories[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'percentage' => $category->getPercentage()
            ];
        }
        
        $reports = $this->billModel->getAllReports();
        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $currentYear = date('Y');
        $years = range($currentYear - 2, $currentYear + 2);

        require_once 'views/bills/create.php';
    }
    
    // Procesar la creación de un nuevo gasto
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = $_POST['value'] ?? 0;
            $idCategory = $_POST['idCategory'] ?? 0;
            $month = $_POST['month'] ?? '';
            $year = $_POST['year'] ?? '';
            
            // Validar que el valor sea mayor que cero
            if (!is_numeric($value) || $value <= 0) {
                $_SESSION['error'] = "El valor del gasto debe ser mayor que cero.";
                header('Location: index.php?controller=bills&action=create');
                exit;
            }
            
            // Validar que se haya seleccionado una categoría válida
            if (!is_numeric($idCategory) || $idCategory <= 0) {
                $_SESSION['error'] = "Debe seleccionar una categoría válida.";
                header('Location: index.php?controller=bills&action=create');
                exit;
            }
            
            // Validar que se haya seleccionado un mes y año
            if (empty($month) || !is_numeric($year)) {
                $_SESSION['error'] = "Debe seleccionar un mes y año válidos.";
                header('Location: index.php?controller=bills&action=create');
                exit;
            }
            
            // Verificar si existe un ingreso para este mes y año
            $reportId = $this->billModel->getReportId($month, $year);
            if (!$reportId) {
                // Si no existe un reporte para este mes y año, lo creamos
                $reportId = $this->billModel->createReport($month, $year);
                if (!$reportId) {
                    $_SESSION['error'] = "Error al crear el reporte para {$month} de {$year}.";
                    header('Location: index.php?controller=bills&action=create');
                    exit;
                }
            }
            
            // Verificar si existe un ingreso para este mes y año usando el modelo de ingresos
            $income = $this->incomeModel->getIncomeByReportId($reportId);
            if (!$income) {
                $_SESSION['error'] = "No existe un ingreso registrado para {$month} de {$year}. Debe registrar primero el ingreso.";
                header('Location: index.php?controller=bills&action=create');
                exit;
            }
            
            // Proceder a crear el gasto
            $created = $this->billModel->createBill($value, $idCategory, $month, $year);
            
            if ($created) {
                $_SESSION['success'] = "Gasto registrado correctamente.";
                header('Location: index.php?controller=bills&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Hubo un error al registrar el gasto.";
                header('Location: index.php?controller=bills&action=create');
                exit;
            }
        }
    }
    
    // Mostrar el formulario para editar un gasto
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = "ID de gasto no válido.";
            header('Location: index.php?controller=bills&action=index');
            exit;
        }
        
        $bill = $this->billModel->getBillById($id);
        
        if (!$bill) {
            $_SESSION['error'] = "El gasto no existe.";
            header('Location: index.php?controller=bills&action=index');
            exit;
        }
        
        // Obtener los objetos Category del modelo
        $categoriesObjects = $this->categoryModel->getAllCategories();
        
        // Convertir los objetos a un formato array asociativo para la vista
        $categories = [];
        foreach ($categoriesObjects as $category) {
            $categories[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'percentage' => $category->getPercentage()
            ];
        }
        
        require_once 'views/bills/edit.php';
    }
    
    // Procesar la actualización de un gasto
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $value = $_POST['value'] ?? 0;
            $idCategory = $_POST['idCategory'] ?? 0;
            
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                $_SESSION['error'] = "ID de gasto no válido.";
                header('Location: index.php?controller=bills&action=index');
                exit;
            }
            
            // Validar que el valor sea mayor que cero
            if (!is_numeric($value) || $value <= 0) {
                $_SESSION['error'] = "El valor del gasto debe ser mayor que cero.";
                header("Location: index.php?controller=bills&action=edit&id={$id}");
                exit;
            }
            
            // Validar que se haya seleccionado una categoría válida
            if (!is_numeric($idCategory) || $idCategory <= 0) {
                $_SESSION['error'] = "Debe seleccionar una categoría válida.";
                header("Location: index.php?controller=bills&action=edit&id={$id}");
                exit;
            }
            
            // Proceder a actualizar el gasto
            $updated = $this->billModel->updateBill($id, $value, $idCategory);
            
            if ($updated) {
                $_SESSION['success'] = "Gasto actualizado correctamente.";
                header('Location: index.php?controller=bills&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Hubo un error al actualizar el gasto.";
                header("Location: index.php?controller=bills&action=edit&id={$id}");
                exit;
            }
        }
    }
    
    // Eliminar un gasto
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                $_SESSION['error'] = "ID de gasto no válido.";
                header('Location: index.php?controller=bills&action=index');
                exit;
            }
            
            // Proceder a eliminar el gasto
            $deleted = $this->billModel->deleteBill($id);
            
            if ($deleted) {
                $_SESSION['success'] = "Gasto eliminado correctamente.";
            } else {
                $_SESSION['error'] = "Hubo un error al eliminar el gasto.";
            }
            
            header('Location: index.php?controller=bills&action=index');
            exit;
        }
    }
}
?>