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
            max-width: 1300px;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #fff;
            margin: 50px auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 1200px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #2c3e50;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #e74c3c;
        }

        .modal-title {
            text-align: center;
            color: #2c3e50;
            font-size: 3em;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .content-box {
            background: #f7f6f9;
            border: 1px solid #e1e8ed;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .content-box h3 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .inspect-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.3s ease;
            color: #2c3e50;
        }

        .inspect-btn:hover {
            background-color: rgba(44, 62, 80, 0.1);
            transform: scale(1.1);
        }

        .content-item {
    background: #ffffff;
    margin-bottom: 25px;
    padding: 22px;
    border: 1px solid #e1e8ed;
    border-radius: 12px;
    position: relative;
}

.content-item::before {
    content: '';
    position: absolute;
    top: 8px;
    left: 8px;
    right: -8px;
    bottom: -8px;
    background: #f8fafc;
    border-radius: 12px;
    z-index: -1;
}

.content-item:hover::before {
    background: linear-gradient(45deg, #f1f5f9, #f8fafc);
}


.content-item:last-child {
    margin-bottom: 0;
}

.content-item h4 {
    color: #2c3e50;
    font-size: 1.5em;
    margin-bottom: 20px;
    font-weight: 600;
}

.content-item p {
    color: #4a5568;
    font-family: 'Open Sans', sans-serif;
    font-size: 1.1em;
    font-weight: 500;
    line-height: 1.9;
    margin: 12px 0;
    text-align: justify;
}

.content-item a {
    color: #3498db;
    font-family: 'Roboto', sans-serif;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    margin-top: 10px;
    font-size: 0.9em;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
    background: rgba(52, 152, 219, 0.1);
    transition: all 0.2s ease;
}

.content-item a:hover {
    background: rgba(52, 152, 219, 0.2);
    color: #2980b9;
    text-decoration: none;
}

/* Eliminar la línea hr y usar un diseño más moderno */
.content-item hr {
    display: none;
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
                            noticias,
                            patentes,
                            pub_cientificas,
                            eventos,
                            proyectos
                        FROM boletines
                        ORDER BY fecha_registro DESC";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Inspeccionar</th>
                        <th>Título</th>
                        <th>Temas</th>
                        <th>Noticas</th>
                        <th>Publicaciones Cientificas</th>
                        <th>Patentes</th>
                        <th>Eventos</th>
                        <th>Proyectos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $fila) { ?>
                        <tr>
                            <td>
                                <button type="button" 
                                        class="inspect-btn" 
                                        onclick="cargarDatosModal(<?php echo $fila['id']; ?>)"  
                                        title="Inspeccionar boletín">
                                    <i class="fas fa-search"></i>
                                </button>
                            </td>
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
                            <td><?php echo countTuples($fila['noticias']); ?></td>
                            <td><?php echo countTuples($fila['pub_cientificas']); ?></td>
                            <td><?php echo countTuples($fila['patentes']); ?></td>
                            <td><?php echo countTuples($fila['eventos']); ?></td>
                            <td><?php echo countTuples($fila['proyectos']); ?></td>
                            
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

    <div id="inspectionModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h1 id="modalTitle" class="modal-title"></h1>
            
            <div class="content-box">
                <h3>Noticias</h3>
                <!-- Contenido para noticias -->
            </div>

            <div class="content-box">
                <h3>Publicaciones Científicas</h3>
                <!-- Contenido para publicaciones científicas -->
            </div>

            <div class="content-box">
                <h3>Patentes</h3>
                <!-- Contenido para patentes -->
            </div>

            <div class="content-box">
                <h3>Eventos</h3>
                <!-- Contenido para eventos -->
            </div>

            <div class="content-box">
                <h3>Proyectos</h3>
                <!-- Contenido para proyectos -->
            </div>
        </div>
    </div>

    <script>

// En inspeccionar-boletines.php
function procesarCSV(csvString) {
    if (!csvString) return '<p>No hay datos disponibles</p>';
    
    let html = '';
    
    try {
        // Nueva expresión regular mejorada que maneja múltiples tuplas
        const regex = /\("([^"]*?)","([^"]*?)","([^"]*?)"\)/g;
        const matches = Array.from(csvString.matchAll(regex));

        if (matches.length === 0) {
            return '<p>No hay datos disponibles</p>';
        }

        for (const match of matches) {
            // match[1] es título, match[2] es descripción, match[3] es url
            const [_, titulo, descripcion, url] = match;
            
            console.log('Procesando tupla:', { titulo, descripcion, url }); // Para debugging
            
            html += `
                <div class="content-item">
                    <h4>${titulo}</h4>
                    <p>${descripcion}</p>
                    <a href="${url}" target="_blank">
                        Ver más <i class="fas fa-external-link-alt" style="margin-left: 6px; font-size: 0.9em;"></i>
                    </a>
                </div>
            `;
        }
        
        return html;
    } catch (error) {
        console.error('Error procesando CSV:', error);
        console.log('CSV recibido:', csvString); // Para debugging
        return '<p>Error al procesar los datos</p>';
    }
}

// Agregar una función de prueba para verificar el parsing
function testParsing(csvString) {
    const regex = /\("([^"]*?)","([^"]*?)","([^"]*?)"\)/g;
    const matches = Array.from(csvString.matchAll(regex));
    console.log('Matches encontrados:', matches.length);
    matches.forEach((match, index) => {
        console.log(`Tupla ${index + 1}:`, {
            full: match[0],
            titulo: match[1],
            descripcion: match[2],
            url: match[3]
        });
    });
}

    // Función para cargar los datos y abrir el modal
    function cargarDatosModal(id) {
    fetch(`cargar-datos.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            // Actualizar título principal
            document.getElementById('modalTitle').textContent = data.titulo;
            
            // Actualizar contenido de cada caja
            const contenedores = {
                'noticias': 0,
                'pub_cientificas': 1,
                'patentes': 2,
                'eventos': 3,
                'proyectos': 4
            };
            
            for (const [tipo, index] of Object.entries(contenedores)) {
                const box = document.getElementsByClassName('content-box')[index];
                // Crear o actualizar un div para el contenido
                let contentDiv = box.querySelector('.content-dynamic');
                if (!contentDiv) {
                    contentDiv = document.createElement('div');
                    contentDiv.className = 'content-dynamic';
                    box.appendChild(contentDiv);
                }
                contentDiv.innerHTML = procesarCSV(data[tipo]);
            }
            
            document.getElementById('inspectionModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => console.error('Error:', error));
}



        // Funciones para el modal (antigua)
        function openModal(title) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('inspectionModal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Previene el scroll del body
        }

        function closeModal() {
            document.getElementById('inspectionModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restaura el scroll del body
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            var modal = document.getElementById('inspectionModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

<?php
// Función PHP para contar tuplas
function countTuples($csvString) {
    if (empty($csvString)) return 0;
    return preg_match_all('/\([^)]*\)/', $csvString, $matches);
}
?>

</body>
</html>