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
    font-family: Calibri, sans-serif;
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
            cursor: pointer;
        }

        @media (min-width: 768px) {
            .zoom-container:hover img {
                transform: scale(1.5);
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

        .ficha {
            font-size: 0.9em;
            color: #5c4033;
            margin-top: 8px;
            background: #fff3e0;
            border-radius: 10px;
            padding: 10px;
            text-align: left;
        }

        .ficha p {
            margin: 3px 0;
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

        #imageModal {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.85);
            justify-content: center;
            align-items: center;
        }

        #imageModal img {
            max-width: 90%;
            max-height: 80%;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
        }

        #closeModal {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        .img-pagina1 {
    width: 70px;   /* ajusta a lo que te parezca mejor */
    height: auto;
}

.img-pagina2 {
    width: 60px;
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
    max-height: 20vh;
    max-width: 30vw;
    height: auto;
    width: auto;
    opacity: 0.9;
    transition: transform 0.3s ease;
}






    </style>
</head>
<body>
<div class="corner-images">
    <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935311/logo_cenigraf-02_pa61ux.png" class="img-pagina1">
    <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935416/logo_evento_diaArte_Mesa_de_trabajo_1_anparc.png" class="img-pagina2">
</div>



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
                <img src="<?php echo $row['imagen1']; ?>" alt="Ilustración" class="preview-img">
                <span class="zoom-icon">🔍</span>
            </div>
            <p><strong><?php echo $row['titulo']; ?></strong></p>
            <div class="ficha">
                <p><strong>Año:</strong> <?php echo $row['anio']; ?></p>
                <p><strong>Técnica:</strong> <?php echo $row['tecnica']; ?></p>
                <p><strong>Dimensiones:</strong> <?php echo $row['dimensiones']; ?></p>
            </div>
            <p><strong>Votos:</strong> <?php echo $row['votos']; ?></p>
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
    <a href="index.php"><button>📝 ¿Nuevo? Regístrate</button></a>
</div>

<!-- Modal de imagen -->
<div id="imageModal">
    <span id="closeModal">&times;</span>
    <img id="modalImage" src="" alt="Vista ampliada">
</div>

<script>
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    const closeBtn = document.getElementById("closeModal");

    document.querySelectorAll(".preview-img").forEach(img => {
        img.addEventListener("click", () => {
            modal.style.display = "flex";
            modalImg.src = img.src;
        });
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
</script>

</body>
</html>
