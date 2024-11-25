<?php
require_once '../includes/conexion.php';

// Función para sanitizar inputs
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para convertir el período a meses
function convertirPeriodoAMeses($periodo) {
    $partes = explode('_', $periodo);
    $cantidad = intval($partes[0]);
    $unidad = $partes[1];
    
    switch($unidad) {
        case 'mes':
        case 'meses':
            return $cantidad;
        case 'año':
        case 'años':
            return $cantidad * 12;
        default:
            return 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener conexión
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Sanitizar y validar datos
        $titulo = sanitizeInput($_POST['titulo']);
        $temas = sanitizeInput($_POST['temas']); // String CSV de temas
        $plazo = convertirPeriodoAMeses($_POST['span']);
        $descripcion = sanitizeInput($_POST['indicaciones']);
        $estado = 'Registrado'; // Estado inicial

        // Validaciones
        if (strlen($titulo) > 50) {
            throw new Exception("El título no puede exceder los 50 caracteres");
        }

        if (strlen($temas) > 100) {
            throw new Exception("Los temas no pueden exceder los 100 caracteres");
        }

        if (strlen($descripcion) > 200) {
            throw new Exception("La descripción no puede exceder los 200 caracteres");
        }

        // Preparar la consulta (ahora incluye temas)
        $stmt = $conn->prepare("
            INSERT INTO boletines (titulo, temas, plazo, estado, descripcion_extra, fecha_registro) 
            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ");

        $stmt->bind_param("ssiss", $titulo, $temas, $plazo, $estado, $descripcion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a index.html con mensaje de éxito
            header("Location: index.html?success=1");
            exit();
        } else {
            throw new Exception("Error al guardar el boletín");
        }

    } catch (Exception $e) {
        // Redirigir con mensaje de error
        header("Location: formulario.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si no es POST, redirigir al formulario
    header("Location: formulario.php");
    exit();
}
?>