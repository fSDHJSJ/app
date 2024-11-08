<?php
// Incluir archivo de conexión
include 'conexion.php';

// Mostrar los usuarios
$usuarios_resultado = $mysqli->query("SELECT * FROM usuarios");

// Agregar nuevo usuario
if (isset($_POST['agregar_usuario'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Inserción en la base de datos
    $stmt = $mysqli->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php"); // Redirigir después de agregar
    exit();
}

// Eliminar usuario
if (isset($_GET['eliminar_id'])) {
    $id_usuario = $_GET['eliminar_id'];

    // Primero eliminamos las encuestas asociadas, si es necesario
    $delete_encuestas = "DELETE FROM encuesta WHERE id_usuario = ?";
    $stmt_encuestas = $mysqli->prepare($delete_encuestas);
    $stmt_encuestas->bind_param("i", $id_usuario);
    $stmt_encuestas->execute();
    $stmt_encuestas->close();

    // Eliminar usuario
    $stmt_usuario = $mysqli->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_usuario->bind_param("i", $id_usuario);
    $stmt_usuario->execute();
    $stmt_usuario->close();
    header("Location: admin_dashboard.php"); // Redirigir después de eliminar
    exit();
}

// Editar usuario
if (isset($_POST['editar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Actualizar en la base de datos
    $stmt = $mysqli->prepare("UPDATE usuarios SET username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $role, $id_usuario);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php"); // Redirigir después de actualizar
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Admin</title>
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
    </style>
</head>
<body>

<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
</nav>

<div class="container">
    <!-- Formulario para agregar usuario -->
    <h2 class="text-center">Agregar Nuevo Usuario</h2>
    <form method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Rol:</label>
            <select class="form-control" name="role" required>
                <option value="admin">Admin</option>
                <option value="paciente">Paciente</option>
                <option value="doctor">Doctor</option>
            </select>
        </div>
        <button type="submit" name="agregar_usuario" class="btn btn-success">Agregar Usuario</button>
    </form>

    <h2 class="text-center mt-4">Usuarios Registrados</h2>
    <!-- Mostrar usuarios -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = $usuarios_resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['username']; ?></td>
                    <td><?php echo $usuario['role']; ?></td>
                    <td>
                        <!-- Editar -->
                        <button class="btn btn-warning" data-toggle="modal" data-target="#editarModal<?php echo $usuario['id']; ?>">Editar</button>
                        <!-- Eliminar -->
                        <a href="?eliminar_id=<?php echo $usuario['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>

                <!-- Modal para editar usuario -->
                <div class="modal fade" id="editarModal<?php echo $usuario['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                    <div class="form-group">
                                        <label for="username">Username:</label>
                                        <input type="text" class="form-control" name="username" value="<?php echo $usuario['username']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Rol:</label>
                                        <select class="form-control" name="role" required>
                                            <option value="admin" <?php if ($usuario['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                            <option value="paciente" <?php if ($usuario['role'] == 'paciente') echo 'selected'; ?>>Paciente</option>
                                            <option value="doctor" <?php if ($usuario['role'] == 'doctor') echo 'selected'; ?>>Doctor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" name="editar_usuario" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
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
