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

function validarFormatoNoticias($noticias) {
    if (empty($noticias)) return true;
    
    // Verificar que comience con ( y termine con )
    if (!preg_match('/^\(.*\)$/', $noticias)) {
        return false;
    }
    
    // Verificar que cada registro tenga 3 campos entre comillas
    $registros = explode('),(', trim($noticias, '()'));
    foreach ($registros as $registro) {
        $campos = explode('","', trim($registro, '"'));
        if (count($campos) !== 3) {
            return false;
        }
    }
    
    return true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener conexión
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Sanitizar y validar datos básicos
        $titulo = sanitizeInput($_POST['titulo']);
        $temas = sanitizeInput($_POST['temas']);
        $plazo = convertirPeriodoAMeses($_POST['span']);
        $descripcion = sanitizeInput($_POST['indicaciones']);
        $estado = 'Registrado';

        // Procesar noticias (nuevo campo)
        $noticias = isset($_POST['noticias']) ? trim($_POST['noticias']) : '';

        // Validaciones de longitud
        if (strlen($titulo) > 50) {
            throw new Exception("El título no puede exceder los 50 caracteres");
        }

        if (strlen($temas) > 100) {
            throw new Exception("Los temas no pueden exceder los 100 caracteres");
        }

        if (strlen($descripcion) > 200) {
            throw new Exception("La descripción no puede exceder los 200 caracteres");
        }

        if (strlen($noticias) > 3000) {
            throw new Exception("Las noticias exceden el límite permitido");
        }

        if (!validarFormatoNoticias($noticias)) {
            throw new Exception("Formato de noticias inválido");
        }

        // Preparar la consulta incluyendo el campo noticias
        $stmt = $conn->prepare("
            INSERT INTO boletines (
                titulo, 
                temas, 
                plazo, 
                estado, 
                descripcion_extra, 
                fecha_registro,
                noticias
            ) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?)
        ");

        $stmt->bind_param("ssisss", 
            $titulo, 
            $temas, 
            $plazo, 
            $estado, 
            $descripcion,
            $noticias
        );

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Obtener el ID del boletín insertado
            $boletin_id = $conn->insert_id;
            
            // Procesar las publicaciones científicas, patentes, eventos y proyectos
            // Por ahora los dejamos vacíos ya que mencionaste que son campos varchar
            $stmt = $conn->prepare("
                UPDATE boletines 
                SET pub_cientificas = '',
                    patentes = '',
                    eventos = '',
                    proyectos = ''
                WHERE id = ?
            ");
            
            $stmt->bind_param("i", $boletin_id);
            $stmt->execute();

            // Redirigir a index.html con mensaje de éxito
            header("Location: index.html?success=1");
            exit();
        } else {
            throw new Exception("Error al guardar el boletín: " . $conn->error);
        }

    } catch (Exception $e) {
        // Registrar el error en un archivo de log
        error_log("Error en guardar-boletin.php: " . $e->getMessage());
        
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