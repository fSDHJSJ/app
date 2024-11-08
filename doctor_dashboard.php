<?php
// Incluir archivo de conexión
include 'conexion.php';

// Mostrar los pacientes (usuarios con rol "paciente")
$pacientes_resultado = $mysqli->query("SELECT * FROM usuarios WHERE role = 'paciente'");

// Ver encuestas de los pacientes
$encuestas_resultado = $mysqli->query("SELECT * FROM encuesta");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Doctor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar a {
            color: #fff;
        }
        .container {
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn {
            margin: 5px;
        }
        .form-container {
            margin-top: 20px;
        }
        .modal-body table {
            width: 100%;
        }
        .modal-body th, .modal-body td {
            text-align: left;
        }
    </style>
</head>
<body>

<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Dashboard de Doctor</a>
</nav>

<div class="container">
    <h2 class="text-center">Pacientes Registrados</h2>
    <!-- Mostrar pacientes -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($paciente = $pacientes_resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $paciente['id']; ?></td>
                    <td><?php echo $paciente['username']; ?></td>
                    <td>
                        <!-- Ver encuestas del paciente -->
                        <button class="btn btn-info" data-toggle="modal" data-target="#verEncuestasModal<?php echo $paciente['id']; ?>">Ver Encuestas</button>
                    </td>
                </tr>

                <!-- Modal para ver encuestas de un paciente -->
                <div class="modal fade" id="verEncuestasModal<?php echo $paciente['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Encuestas de <?php echo $paciente['username']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5>Resultados de las Encuestas:</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Pregunta</th>
                                            <th>Respuesta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Mostrar todas las encuestas para el paciente
                                        $encuesta = $mysqli->query("SELECT * FROM encuesta WHERE id_usuario = {$paciente['id']}");
                                        while ($resultados_encuesta = $encuesta->fetch_assoc()) {
                                            echo "<tr><td>P1: ¿Has pensado en el suicidio?</td><td>" . htmlspecialchars($resultados_encuesta['pregunta_1']) . "</td></tr>";
                                            echo "<tr><td>P2: ¿Quieres hablar con alguien?</td><td>" . htmlspecialchars($resultados_encuesta['pregunta_2']) . "</td></tr>";
                                            echo "<tr><td>P3: ¿Quieres compañía?</td><td>" . htmlspecialchars($resultados_encuesta['pregunta_3']) . "</td></tr>";
                                            echo "<tr><td>P4: ¿Quieres recibir ayuda profesional?</td><td>" . htmlspecialchars($resultados_encuesta['pregunta_4']) . "</td></tr>";
                                            echo "<tr><td>P5: ¿Te has sentido mejor últimamente?</td><td>" . htmlspecialchars($resultados_encuesta['pregunta_5']) . "</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<!-- Script de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php $mysqli->close(); ?>
