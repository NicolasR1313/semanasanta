<?php
session_start();
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['rol'] = $row['rol'];

            // Redirigir según el rol
            if ($row['rol'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: inicio.php");
            }
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Semana Santa</title>
    <style>
        body {
    background: url('fondos/fondoinicio.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Georgia', serif;
    margin: 0;
    padding: 0;
    color: #4b2e1e;
}


        .login-container {
            background-color: rgba(255, 248, 225, 0.95);
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            border-radius: 20px;
            border: 5px double #d4af37;
            box-shadow: 0 0 20px rgba(139, 94, 60, 0.4);
            text-align: center;
        }

        h2 {
            font-size: 2.2em;
            color: #8b5e3c;
            margin-bottom: 25px;
            text-shadow: 1px 1px #fff;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding-bottom: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
            border: 2px solid #d4af37;
            background-color: #fffef5;
        }

        button {
            background-color: #cba135;
            color: white;
            padding: 12px;
            font-size: 1em;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #a57c1b;
        }

        .error {
            background-color: #fff0d9;
            color: #8b4513;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid #cba135;
        }

        .link {
            margin-top: 20px;
            font-size: 0.95em;
        }

        .link a {
            color: #8b5e3c;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Iniciar Sesión</h2>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <div class="link">
            <a href="index.php">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>
</body>
</html>
