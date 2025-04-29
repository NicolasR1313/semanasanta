<?php
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO usuarios (usuario, password, rol) VALUES ('$usuario', '$password', 'user')";
    mysqli_query($conn, $query);
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Semana Santa</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        * {
            box-sizing: border-box; /* Esto evita que el padding se salga del contenedor */
        }

        body {
            background: url('fondos/fondoinicio.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            color: #4b2e1e;
        }

        .register-container {
            background-color: rgba(255, 248, 225, 0.95);
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            border-radius: 20px;
            
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

        input,
        button {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border-radius: 8px;
        }

        input {
            border: 2px solid #d4af37;
            background-color: #fffef5;
        }

        button {
            background-color: #cba135;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #a57c1b;
        }

        .link {
            margin-top: 20px;
            font-size: 0.95em;
        }

        .link a {
            color: #8b5e3c;
            text-decoration: underline;
        }
          .img-pagina1 {
    width: 60px;   /* ajusta a lo que te parezca mejor */
    height: auto;
}

.img-pagina2 {
    width: 50px;
    height: auto;
}

.corner-images {
    position: fixed;
    top: 1vw;
    right: 1vw;
    display: flex;
    gap: 1vw;
    z-index: 1000;
}

.corner-images img {
    max-height: 15vh;
    max-width: 25vw;
    height: auto;
    width: auto;
    opacity: 0.9;
    transition: transform 0.3s ease;
}
        @media (max-width: 768px) {
    .corner-images {
        top: auto;
        bottom: 1vw; /* cambia la posición vertical */
        right: 1vw;
        flex-direction: column; /* una imagen encima de la otra si lo prefieres */
        align-items: flex-end;
    }
}
    </style>
</head>
<body>
    <div class="corner-images">
    <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935311/logo_cenigraf-02_pa61ux.png" class="img-pagina1">
    <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935416/logo_evento_diaArte_Mesa_de_trabajo_1_anparc.png" class="img-pagina2">
</div>

    <div class="register-container">
        <h2>📝 Registro</h2>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>

        <div class="link">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>
