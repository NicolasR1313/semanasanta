<?php
session_start();
include "includes/db.php";

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {

    $cloud_name = 'dsktdsxik'; // Tu Cloudinary cloud_name
    $upload_preset = 'imagenes';

    $tmp_path = $_FILES["archivo"]["tmp_name"];

    $cloudinary_url = "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";

    $data = [
        'file' => new CURLFile($tmp_path),
        'upload_preset' => $upload_preset
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $cloudinary_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($response, true);

    if (isset($res['secure_url'])) {
        $url = $res['secure_url'];

        // Captura los datos del formulario
        $titulo = isset($_POST['titulo']) ? mysqli_real_escape_string($conn, $_POST['titulo']) : 'Sin título';
        $tecnica = isset($_POST['tecnica']) ? mysqli_real_escape_string($conn, $_POST['tecnica']) : '';
        $dimensiones = isset($_POST['dimensiones']) ? mysqli_real_escape_string($conn, $_POST['dimensiones']) : '';
        $anio = isset($_POST['anio']) ? mysqli_real_escape_string($conn, $_POST['anio']) : '';
        $autor = isset($_POST['autor']) ? mysqli_real_escape_string($conn, $_POST['autor']) : '';

        // Inserta en la base de datos
        $query = "INSERT INTO ilustraciones (titulo, tecnica, dimensiones, anio, imagen1, imagen, autor) 
          VALUES ('$titulo', '$tecnica', '$dimensiones', '$anio', '$url', '$url', '$autor')";



        if (mysqli_query($conn, $query)) {
            $_SESSION['mensaje'] = "✅ Imagen y ficha técnica registradas correctamente.";
            header("Location: inicio.php");
            exit;
        } else {
            $mensaje = "⚠️ Error al guardar en la base de datos: " . mysqli_error($conn);
        }
    } else {
        $mensaje = "⚠️ Error al subir la imagen a Cloudinary.";
    }
} elseif (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
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
            margin: 120px auto;
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
        .mensaje {
            margin-top: 20px;
            font-weight: bold;
            color: #6f4e37;
        }
        .img-pagina1 {
    width: 60px;   /* ajusta a lo que te parezca mejor */
    height: auto;
}

.img-pagina2 {
    width: 50px;
    height: auto;
}
.header-flex h1 {
    font-size: 2.2em;
    color: white;
    margin: 20px 0;
    border-bottom: 4px double #d4af37;
    display: inline-block;
    padding-bottom: 10px;
    font-family: Calibri, sans-serif;
    text-align: left;
    flex: 1;
    min-width: 200px;
}

.corner-images {
    display: flex;
    gap: 1vw;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
}

.corner-images img {
    max-height: 10vh;
    max-width: 25vw;
    height: auto;
    width: auto;
    opacity: 0.9;
    transition: transform 0.3s ease;
}

@media (max-width: 768px) {
    .header-flex {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .corner-images {
        justify-content: center;
        margin-top: 10px;
    }

    .corner-images img {
        max-width: 40vw;
    }
}

    
    </style>
</head>
<div class="header-flex">
    <h1>🎨 Subir Imagen</h1>
    <div class="corner-images">
        <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935311/logo_cenigraf-02_pa61ux.png" class="img-pagina1">
        <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935416/logo_evento_diaArte_Mesa_de_trabajo_1_anparc.png" class="img-pagina2">
    </div>
</div>

    <div class="container">
        <h2>🖌️ Subir Ilustración</h2>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Título de la ilustración" required>
            <input type="text" name="tecnica" placeholder="Técnica (Ej: Acuarela, Digital, etc.)">
            <input type="text" name="dimensiones" placeholder="Dimensiones (Ej: 20x30 cm)">
            <input type="text" name="anio" placeholder="Año de creación (Ej: 2025)">
            <input type="text" name="autor" placeholder="Autor (Ej: Tu Nombre)"> 
            <input type="file" name="archivo" required>
            <button type="submit">Subir imagen</button>
        </form>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>

        <a href="index.php">← Volver a la galería</a>
    </div>
</body>
</html>
