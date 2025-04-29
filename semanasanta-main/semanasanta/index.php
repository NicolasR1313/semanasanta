<?php
include "includes/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar si el usuario ya existe
    $check_user = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        $mensaje = "⚠️ El usuario ya existe. <a href='login.php'>Inicia sesión</a>.";
    } else {
        // Registrar el usuario
        $query = "INSERT INTO usuarios (usuario, password, rol) VALUES ('$usuario', '$password', 'user')";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "❌ Error al registrarse. Intenta de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Semana Santa</title>
    <style>
        body {
            background: url('fondos/fondoinicio.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            color: #4b2e1e;
           
        }

        .container {
    background-color: rgba(255, 248, 225, 0.95);
    max-width: 450px; /* antes 500px */
    
    margin: 100px auto;
    padding: 40px;
    border-radius: 20px;
    border: 5px double #d4af37;
    box-shadow: 0 0 20px rgba(139, 94, 60, 0.4);
    text-align: center;
}


        h2 {
            margin-bottom: 20px;
            font-size: 2.2em;
            color: #8b5e3c;
            text-shadow: 1px 1px #fff;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding-bottom: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            font-size: 1.1em;
            text-align: left;
            color: #5a3b27;
        }

        input {
            padding: 10px;
            border-radius: 8px;
            border: 2px solid #d4af37;
            font-size: 1em;
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

        .mensaje {
            background-color: #fff0d9;
            color: #8b4513;
            padding: 12px;
            border-radius: 10px;
            margin: 15px 0;
            border: 1px solid #cba135;
        }

        .login-link {
            margin-top: 20px;
            font-size: 0.95em;
        }

        .login-link a {
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

       .header-flex h1 {
    font-size: 2.2em;
    color: white;
    margin: 10px 0;
    border-bottom: 4px double #d4af37;
    padding-bottom: 10px;
    font-family: Calibri, sans-serif;
    flex: 1;
    min-width: 200px;
    text-align: left;
    display: block; /* Cambio de inline-block a block */
}
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center; /* Esto asegura que estén al mismo nivel verticalmente */
    padding: 10px 20px;
    flex-wrap: wrap;
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
<body>
    <div class="header-flex">
    <h1>🎨 Registrate</h1>
    <div class="corner-images">
        <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935311/logo_cenigraf-02_pa61ux.png" class="img-pagina1">
        <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935416/logo_evento_diaArte_Mesa_de_trabajo_1_anparc.png" class="img-pagina2">
    </div>
</div>




    <div class="container">
        <h2>Registro de Usuario</h2>

        <?php if (!empty($mensaje)) { ?>
            <p class="mensaje"><?php echo $mensaje; ?></p>
        <?php } ?>

        <form method="POST">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" placeholder="Ingresa tu usuario" required>

            <label for="password">Contraseña</label>
            <input type="password" name="password" placeholder="Ingresa tu contraseña" required>

            <button type="submit">Registrarse</button>
        </form>

        <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>

</body>
</html>
