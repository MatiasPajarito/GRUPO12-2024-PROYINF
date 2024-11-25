<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de los Boletines</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Todo el CSS existente se mantiene igual */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .main-wrapper {
            width: 90%;
            max-width: 1700px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Header alineado con la caja */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 0;
        }

        .header-title {
            color: #2c3e50;
            font-size: 1.5em;
            font-weight: 600;
        }

        .logo-space {
            width: 200px;
            height: 130px;
            /* background-color: #f0f0f0; /* Comentado - solo para visualizar el espacio */
        }
        /* Contenedor para los logos */
        .logos-container {
            display: flex;
            gap: 1px;  /* Espacio entre los logos */
            align-items: center;
        }

        /* Ajustar el tamaño de los espacios para los logos */
        .logo-space {
            width: 150px;  /* Ajustado para que ambos logos quepan */
            height: 130px;
        }

        /* Ajuste responsivo */
        @media (max-width: 600px) {
            .logos-container {
                gap: 10px;
            }

            .logo-space {
                width: 100px;
                height: 90px;
            }
        }

        .header-title h1 {
            font-size: 1.8em;
            font-weight: 600;
            margin: 0;
        }

        .header-title h2 {
            font-size: 1.2em;
            font-weight: 500;
            color: #546e7a;
            margin: 0;
        }

        .logos-container {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .logo-space {
            width: 150px;
            height: 130px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #34495e;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f5f9fc;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .tag {
            background-color: rgba(52, 152, 219, 0.1);
            color: #2980b9;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .nav-footer {
            text-align: center;
            margin-top: 20px;
        }

        .return-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            color: #2c3e50;
            text-decoration: none;
            font-size: 0.9em;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .return-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .return-button:active {
            transform: translateY(0);
        }

        .delete-btn {
            color: #777;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            color: #ff4444;
            background-color: rgba(255, 68, 68, 0.1);
            transform: scale(1.1);
        }

        .delete-form {
            margin: 0;
            padding: 0;
            display: inline;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header-content">
            <div class="header-title">
                <h1>Gobierno de Chile</h1>
                <h2>Ministerio de Agricultura</h2>
            </div>
            
            <div class="logos-container">
                <div class="logo-space">
                    <img src="/GRUPO12-2024-PROYINF/src/assets/img/logo-min-agricultura.png" alt="Logo Ministerio de Agricultura" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            </div>
        </div>

        <!-- Contenedor principal -->
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 30px;">Estado de los Boletines</h2>

            <?php
            // Conexión a la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "boletines_db";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Consulta SQL ajustada a la estructura real de la tabla
                $sql = "SELECT 
                            id,
                            titulo,
                            temas,
                            plazo,
                            estado,
                            descripcion_extra,
                            fecha_registro,
                            DATEDIFF(CURRENT_DATE, fecha_registro) as dias_transcurridos
                        FROM boletines
                        ORDER BY fecha_registro DESC";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Temas</th>
                        <th>Plazo</th>
                        <th>Estado</th>
                        <th>Descripción Extra</th>
                        <th>Fecha de Registro</th>
                        <th>Días Transcurridos</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $fila) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['id']); ?></td>
                            <td><?php echo htmlspecialchars($fila['titulo']); ?></td>
                            <td>
                                <div class="tag-list">
                                    <?php
                                    $temas = explode(',', $fila['temas']);
                                    foreach ($temas as $tema) {
                                        echo '<span class="tag">' . htmlspecialchars(trim($tema)) . '</span>';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($fila['plazo']); ?> días</td>
                            <td><?php echo htmlspecialchars($fila['estado']); ?></td>
                            <td><?php echo htmlspecialchars($fila['descripcion_extra']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($fila['fecha_registro'])); ?></td>
                            <td><?php echo $fila['dias_transcurridos']; ?> días</td>
                            <td>
                                <form action="eliminar-boletin.php" method="POST" class="delete-form" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este boletín?');">
                                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                    <button type="submit" class="delete-btn" title="Eliminar boletín">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php
            } catch(PDOException $e) {
                echo "<p>Error al cargar los boletines: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            $conn = null;
            ?>
        </div>

        <!-- Botón de retorno -->
        <div class="nav-footer">
            <a href="index.html" class="return-button">
                Volver al Menú Principal
            </a>
        </div>
    </div>
</body>
</html>