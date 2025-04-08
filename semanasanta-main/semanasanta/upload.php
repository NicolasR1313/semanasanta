<?php
session_start();
include "includes/db.php"; // Asegúrate que este archivo se conecta bien a tu BD en Railway

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $cloud_name = 'dsktdsxik'; // Reemplaza esto con tu cloud_name real
    $upload_preset = 'imagenes';

    $tmp_path = $_FILES["archivo"]["tmp_name"];
    $titulo = $_POST['titulo'];

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

        // Guardar en la base de datos
        $stmt = $conn->prepare("INSERT INTO ilustraciones (titulo, imagen) VALUES (?, ?)");
        $stmt->bind_param("ss", $titulo, $url);
        $stmt->execute();

        $_SESSION['mensaje'] = "✅ Ilustración subida con éxito.";
        header("Location: index.php");
        exit;
    } else {
        echo "❌ Error al subir a Cloudinary:<br><pre>" . print_r($res, true) . "</pre>";
    }
} else {
    echo "❌ No se recibió ninguna imagen.";
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
    </style>
</head>
<body>
    <div class="container">
        <h2>🖌️ Subir Ilustración</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="archivo" required>
            <button type="submit">Subir imagen</button>
        </form>

        <?php if ($mensaje): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>

        <a href="index.php">← Volver a la galería</a>
    </div>
</body>
</html>
