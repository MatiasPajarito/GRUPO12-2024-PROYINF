<?php
header('Content-Type: application/json');

$id = $_GET['id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boletines_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT titulo, noticias, pub_cientificas, patentes, eventos, proyectos 
            FROM boletines WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($datos);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
$conn = null;
?>