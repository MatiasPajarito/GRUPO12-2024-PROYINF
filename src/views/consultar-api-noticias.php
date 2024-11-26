<?php

writeLog("shell_exec habilitado: " . (function_exists('shell_exec') ? "SÍ" : "NO"));
writeLog("Funciones deshabilitadas: " . ini_get('disable_functions'));

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // Si necesitas CORS
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log function con mejoras para debugging
function writeLog($message) {
    $logFile = 'api_requests.log';
    $timestamp = date('Y-m-d H:i:s');
    $messageFormatted = is_array($message) || is_object($message) 
        ? print_r($message, true) 
        : $message;
    file_put_contents($logFile, "[$timestamp] $messageFormatted\n", FILE_APPEND);
}

writeLog("Iniciando nueva consulta API");

// Recibir y loguear datos del POST
$rawData = file_get_contents('php://input');
writeLog("Datos recibidos (raw): " . $rawData);

// Validación del JSON recibido
$data = json_decode($rawData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    writeLog("Error decodificando JSON: " . json_last_error_msg());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en el formato JSON recibido: ' . json_last_error_msg()
    ]);
    exit;
}

writeLog("Datos decodificados:", $data);

// Validar datos recibidos con mensajes más específicos
if (!isset($data['temas'])) {
    writeLog("Error: Campo 'temas' no encontrado en la solicitud");
    echo json_encode([
        'status' => 'error',
        'message' => 'El campo "temas" es requerido'
    ]);
    exit;
}

$temas = trim($data['temas']);
$plazo = isset($data['plazo']) ? intval($data['plazo']) : 1;

// Validación adicional de los parámetros
if (empty($temas)) {
    writeLog("Error: El campo 'temas' está vacío");
    echo json_encode([
        'status' => 'error',
        'message' => 'El campo "temas" no puede estar vacío'
    ]);
    exit;
}

if ($plazo < 1 || $plazo > 12) {  // Añadimos límite superior razonable
    writeLog("Error: Plazo fuera de rango: $plazo");
    echo json_encode([
        'status' => 'error',
        'message' => 'El plazo debe estar entre 1 y 12 meses'
    ]);
    exit;
}

writeLog("Parámetros validados: temas='$temas', plazo=$plazo meses");

// Ejecutar script Python con path completo y mejor escape
$pythonScript = escapeshellarg(__DIR__ . "/ScrapingAPI-NewsCatcher.py");
$escapedTemas = escapeshellarg($temas);
$escapedPlazo = escapeshellarg($plazo);
$command = "python3 $pythonScript $escapedTemas $escapedPlazo";

writeLog("Comando a ejecutar: $command");

// Ejecutar y capturar tanto salida como errores
$output = shell_exec($command . " 2>&1");  // Captura también stderr

if ($output === null || $output === false) {
    writeLog("Error: Fallo al ejecutar el script Python");
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al ejecutar el script de búsqueda'
    ]);
    exit;
}

writeLog("Salida raw del script Python:", $output);

// Validar y procesar el resultado
$result = json_decode($output, true);

if ($result === null) {
    writeLog("Error decodificando resultado. JSON error: " . json_last_error_msg());
    writeLog("Output que causó el error: " . $output);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al procesar los resultados: ' . json_last_error_msg()
    ]);
    exit;
}

// Validar estructura del resultado
if (!isset($result['status'])) {
    writeLog("Error: Respuesta mal formada (falta campo 'status')");
    echo json_encode([
        'status' => 'error',
        'message' => 'Respuesta mal formada del servicio de búsqueda'
    ]);
    exit;
}

// Procesar resultado exitoso
if ($result['status'] === 'success') {
    writeLog("Búsqueda exitosa. Noticias encontradas: " . $result['count']);
    // Validar formato CSV
    if (isset($result['noticias']) && !empty($result['noticias'])) {
        // Verificar formato básico del CSV
        if (preg_match('/^\(".*"\,".*"\,".*"\)/', $result['noticias'])) {
            writeLog("Formato CSV válido");
            writeLog($result['noticias']);
        } else {
            writeLog("Advertencia: Formato CSV potencialmente incorrecto");
        }
    }
}

// Devolver resultado original
echo $output;
?>