<?php

writeLog("shell_exec habilitado: " . (function_exists('shell_exec') ? "SÍ" : "NO"));
writeLog("Funciones deshabilitadas: " . ini_get('disable_functions'));

header('Content-Type: application/json');

// Log function
function writeLog($message) {
    $logFile = 'api_requests.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

writeLog("Iniciando nueva consulta API");

// Recibir y loguear datos del POST
$rawData = file_get_contents('php://input');
writeLog("Datos recibidos: " . $rawData);

$data = json_decode($rawData, true);
writeLog("Datos decodificados: " . print_r($data, true));

// Validar datos recibidos
$temas = isset($data['temas']) ? $data['temas'] : '';
$plazo = isset($data['plazo']) ? intval($data['plazo']) : 1;

if (empty($temas)) {
    writeLog("Error: No se especificaron temas");
    echo json_encode([
        'status' => 'error',
        'message' => 'No se especificaron temas'
    ]);
    exit;
}

writeLog("Ejecutando búsqueda para temas: $temas, plazo: $plazo meses");

// Ejecutar script Python con path completo
$pythonScript = __DIR__ . "/ScrapingAPI-NewsCatcher.py";
$command = escapeshellcmd("python3 \"$pythonScript\" \"$temas\" $plazo");
writeLog("Comando a ejecutar: " . $command);

$output = shell_exec($command);
writeLog("Salida raw del script Python: " . print_r($output, true));

if (empty($output)) {
    writeLog("Error: No se recibió respuesta del script Python");
    echo json_encode([
        'status' => 'error',
        'message' => 'No se recibió respuesta del script Python'
    ]);
    exit;
}

// Validar y devolver resultado
$result = json_decode($output, true);
writeLog("Resultado del json_decode: " . print_r($result, true));

if ($result === null) {
    writeLog("Error: Fallo al decodificar la respuesta del script Python. Error de JSON: " . json_last_error_msg());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al procesar los resultados: ' . json_last_error_msg()
    ]);
} else {
    writeLog("Consulta completada. Status: " . $result['status']);
    if (isset($result['status']) && $result['status'] === 'success') {
        writeLog("Noticias encontradas: " . $result['count']);
    }
    echo $output;
}
?>