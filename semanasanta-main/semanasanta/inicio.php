<?php
session_start();
include "includes/db.php";

// Ruta estática del fondo
$fondo_url = "fondos/fondoinicio.jpg";

// Obtener ilustraciones ordenadas por votos
$query = "SELECT * FROM ilustraciones ORDER BY votos DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - Semana Santa</title>
    <style>
        body {
            background: url('<?php echo $fondo_url; ?>') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Georgia', serif;
            color: #5c4033;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            font-size: 2.2em;
            color: white;
            margin-top: 20px;
            border-bottom: 4px double #d4af37;
            display: inline-block;
            padding-bottom: 10px;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 30px auto;
            max-width: 1200px;
            padding: 10px;
        }

        .artwork {
            background: #fff8e1;
            border: 3px solid #d4af37;
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(139, 94, 60, 0.4);
            margin: 10px;
            padding: 15px;
            width: 90%;
            max-width: 250px;
            text-align: center;
        }

        .zoom-container {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }

        .zoom-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            border: 2px solid #cba135;
            transition: transform 0.3s ease;
        }

        @media (min-width: 768px) {
            .zoom-container:hover img {
                transform: scale(1.5); /* Efecto lupa solo en pantallas grandes */
            }
        }

        .zoom-icon {
            position: absolute;
            bottom: 8px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            padding: 5px;
            font-size: 1.2em;
            pointer-events: none;
        }

        .artwork p {
            font-size: 1em;
            margin-top: 10px;
        }

        button {
            background-color: #cba135;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        button:hover {
            background-color: #a57c1b;
        }

        .message {
            background-color: #fce8d5;
            color: #8b4513;
            padding: 10px;
            border: 1px solid #cba135;
            text-align: center;
            width: 90%;
            margin: 20px auto;
            border-radius: 10px;
        }

        .nav {
            text-align: center;
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 0 10px 30px;
        }

        .nav a button {
            width: 100%;
            max-width: 300px;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="message">
        <?php 
            echo $_SESSION['mensaje']; 
            unset($_SESSION['mensaje']);
        ?>
    </div>
<?php endif; ?>

<h1>🎨 Concurso de Ilustraciones</h1>

<div class="gallery">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="artwork">
            <div class="zoom-container">
                <img src="uploads/<?php echo $row['imagen']; ?>" alt="Ilustración">
                <span class="zoom-icon">🔍</span>
            </div>
            <p><strong><?php echo $row['titulo']; ?></strong></p>
            <p>Votos: <?php echo $row['votos']; ?></p>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <form action="vote.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Votar</button>
                </form>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<div class="nav">
    <a href="upload.php"><button>📤 Subir Ilustración</button></a>
    <a href="logout.php"><button>🔒 Cerrar Sesión</button></a>
    <a href="register.php"><button>📝 ¿Nuevo? Regístrate</button></a>
</div>

</body>
</html>
