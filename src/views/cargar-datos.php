<?php
header('Content-Type: application/json');
header('Content-Type: text/html; charset=UTF-8');

$id = $_GET['id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boletines_db";

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username, 
        $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4")
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT titulo, noticias, pub_cientificas, patentes, eventos, proyectos 
            FROM boletines WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Función para convertir al formato correcto
    function formatearCSV($csvString) {
        if (empty($csvString)) return '';
        
        // Log para debugging
        error_log("CSV original: " . $csvString);
        
        // Convertir el formato si es necesario
        if (strpos($csvString, '","') === false) {
            // El CSV está en el formato antiguo, convertir al nuevo
            $csvString = preg_replace(
                '/\(([^,]*),([^,]*),([^)]*)\)/',
                '("$1","$2","$3")',
                $csvString
            );
        }
        
        error_log("CSV formateado: " . $csvString);
        return $csvString;
    }
    
    // Procesar cada campo
    $campos_csv = ['noticias', 'pub_cientificas', 'patentes', 'eventos', 'proyectos'];
    foreach ($campos_csv as $campo) {
        if (isset($datos[$campo])) {
            $datos[$campo] = formatearCSV($datos[$campo]);
        }
    }
    
    // Log para debugging
    error_log("Datos a enviar: " . print_r($datos, true));
    
    echo json_encode($datos, 
        JSON_UNESCAPED_UNICODE | 
        JSON_UNESCAPED_SLASHES | 
        JSON_HEX_QUOT | 
        JSON_HEX_APOS
    );
    
} catch(PDOException $e) {
    error_log("Error en la base de datos: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
$conn = null;
?>