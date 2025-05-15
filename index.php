<?php
// Configuración de rutas para incluir todos los controladores y modelos
spl_autoload_register(function ($class_name) {
    if (file_exists('controllers/' . $class_name . '.php')) {
        require_once 'controllers/' . $class_name . '.php';
    } elseif (file_exists('models/' . $class_name . '.php')) {
        require_once 'models/' . $class_name . '.php';
    }
});

// Parámetros por defecto
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'income';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Construir el nombre de la clase del controlador
$controllerName = ucfirst($controller) . 'Controller';

// Verificar si el controlador existe
if (class_exists($controllerName)) {
    $controllerInstance = new $controllerName();
    
    // Verificar si el método existe
    if (method_exists($controllerInstance, $action)) {
        // Llamar al método con el ID si existe
        if ($id !== null) {
            $controllerInstance->$action($id);
        } else {
            $controllerInstance->$action();
        }
    } else {
        // Método no encontrado
        echo "Error: Acción '$action' no encontrada.";
    }
} else {
    // Controlador no encontrado
    echo "Error: Controlador '$controllerName' no encontrado.";
}
?>
