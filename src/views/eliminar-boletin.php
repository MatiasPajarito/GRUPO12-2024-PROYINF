<?php
if (isset($_POST['id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "boletines_db";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM boletines WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir de vuelta a la página principal
        header("Location: estado-boletines.php");
        exit();
    } catch(PDOException $e) {
        echo "Error al eliminar el boletín: " . $e->getMessage();
    }
    $conn = null;
}
?>