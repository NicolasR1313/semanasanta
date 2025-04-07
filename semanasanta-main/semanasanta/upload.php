<?php
include "includes/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $imagen = basename($_FILES['imagen']['name']);
    $ruta = __DIR__ . "/uploads/" . $imagen;



    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO ilustraciones (titulo, imagen, votos, usuario_id) VALUES ('$titulo', '$imagen', 0, $user_id)";
        mysqli_query($conn, $query);
        header("Location: index.php");
        exit();
    } else {
        echo "⚠️ Error al subir la imagen. Verifica permisos o el montaje del volumen.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Ilustración</title>
    <style>
        body {
            background: url('fondos/fondoinicio.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Georgia', serif;
            color: #4b2e1e;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            background-color: rgba(255, 248, 225, 0.95);
            border: 5px double #d4af37;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(139, 94, 60, 0.4);
            text-align: center;
        }

        h2 {
            font-size: 2.2em;
            color: #8b5e3c;
            margin-bottom: 25px;
            text-shadow: 1px 1px #fff;
        }

        form input[type="text"],
        form input[type="file"] {
            width: 90%;
            padding: 10px;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid #a67c52;
            background-color: #fffdf5;
            font-size: 1em;
        }

        button {
            background-color: #a67c52;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #8c6239;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #6f4e37;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🖌️ Subir Ilustración</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Título" required>
            <input type="file" name="imagen" required>
            <button type="submit">Subir</button>
        </form>
        <a href="index.php">← Volver a la galería</a>
    </div>
</body>
</html>
