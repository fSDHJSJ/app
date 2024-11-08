<?php
session_start();
include_once "conexion.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['user_id'];
    $pregunta_1 = $_POST['pregunta_1'];
    $pregunta_2 = $_POST['pregunta_2'];
    $pregunta_3 = $_POST['pregunta_3'];
    $pregunta_4 = $_POST['pregunta_4'];
    $pregunta_5 = $_POST['pregunta_5'];

    $sql = "INSERT INTO encuesta (id_usuario, pregunta_1, pregunta_2, pregunta_3, pregunta_4, pregunta_5) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("isssss", $id_usuario, $pregunta_1, $pregunta_2, $pregunta_3, $pregunta_4, $pregunta_5);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p>Respuestas guardadas exitosamente.</p>";
    } else {
        echo "<p>Error al guardar las respuestas.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta</title>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: Arial, sans-serif;
            color: white;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
        }
        .form-control {
            margin-bottom: 20px;
        }
        .input, .login-button {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            margin-top: 5px;
        }
        .login-button {
            background-color: #2575fc;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        .login-button:hover {
            background-color: #6a11cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Encuesta: Preguntas que Salvan Vidas</h2>
        <form method="POST">
            <div class="form-control">
                <label for="pregunta_1">¿Has pensado suicidarte?</label>
                <select name="pregunta_1" class="input" required>
                    <option value="">Seleccione</option>
                    <option value="Sí, lo he pensado.">Sí, lo he pensado.</option>
                    <option value="Lo he pensado en algunas ocasiones.">Lo he pensado en algunas ocasiones.</option>
                    <option value="No, no lo he pensado.">No, no lo he pensado.</option>
                </select>
            </div>

            <div class="form-control">
                <label for="pregunta_2">¿Quieres hablar?</label>
                <select name="pregunta_2" class="input" required>
                    <option value="">Seleccione</option>
                    <option value="Sí, quiero hablar.">Sí, quiero hablar.</option>
                    <option value="Quizás, podría hablar un poco.">Quizás, podría hablar un poco.</option>
                    <option value="No, prefiero no hablar ahora.">No, prefiero no hablar ahora.</option>
                </select>
            </div>

            <div class="form-control">
                <label for="pregunta_3">¿Puedo acompañarte?</label>
                <select name="pregunta_3" class="input" required>
                    <option value="">Seleccione</option>
                    <option value="Sí, me gustaría que me acompañes.">Sí, me gustaría que me acompañes.</option>
                    <option value="Tal vez, prefiero compañía de vez en cuando.">Tal vez, prefiero compañía de vez en cuando.</option>
                    <option value="No, prefiero estar solo/a en este momento.">No, prefiero estar solo/a en este momento.</option>
                </select>
            </div>

            <div class="form-control">
                <label for="pregunta_4">¿Quieres recibir ayuda?</label>
                <select name="pregunta_4" class="input" required>
                    <option value="">Seleccione</option>
                    <option value="Sí, quiero recibir ayuda profesional.">Sí, quiero recibir ayuda profesional.</option>
                    <option value="Estoy considerando recibir ayuda.">Estoy considerando recibir ayuda.</option>
                    <option value="No, por ahora no quiero recibir ayuda.">No, por ahora no quiero recibir ayuda.</option>
                </select>
            </div>

            <div class="form-control">
                <label for="pregunta_5">¿Cómo te has sentido?</label>
                <select name="pregunta_5" class="input" required>
                    <option value="">Seleccione</option>
                    <option value="Me he sentido mejor últimamente.">Me he sentido mejor últimamente.</option>
                    <option value="He tenido días buenos y días malos.">He tenido días buenos y días malos.</option>
                    <option value="Me he sentido peor.">Me he sentido peor.</option>
                </select>
            </div>

            <button type="submit" class="login-button">Enviar Respuestas</button>
            <button type="button" onclick="mostrarMensajeAyuda()" class="login-button">Necesito ayuda</button>
        </form>
    </div>

    <script>
    function mostrarMensajeAyuda() {
        alert("1. Promete no hacer nada ahora.\n2. Evita las drogas y el alcohol.\n3. Habla con alguien en quien confíes.\n\nEstamos contactando a tu médico de confianza.");
    }
    </script>
</body>
</html>
