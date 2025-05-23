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
    padding: 12px;
    font-size: 1em;
    border-radius: 10px;
    cursor: pointer;
    border: 2px solid #8b5e3c; /* ayuda visual */
    z-index: 10;
    position: relative;
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
    width: 70px;
    height: auto;
}

.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    flex-wrap: wrap;
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

     .cards-row {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap; /* para que en pantallas pequeñas se acomoden una debajo de la otra */
    margin: 30px auto;
    padding: 0 20px;
}

.card-laura, .card-angie, .card-juliana {
    background-color: #fff8e1;
    border: 2px solid #d4af37;
    border-radius: 12px;
    box-shadow: 4px 4px 10px rgba(139, 94, 60, 0.3);
    padding: 12px;
    text-align: center;
    width: auto;
    max-width: 300px;
    flex: 1;
    min-width: 220px;
}

.card-laura img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

.card-angie img {
    width: 100%;         /* Aumenta el ancho al 100% del contenedor */
    max-height: 500px;   /* Aumenta la altura */
    object-fit: cover;   /* Asegura un buen encuadre sin distorsión */
    border-radius: 10px;
}

.card-juliana img {
    width: 100%;
    max-height: 350px; /* Aumenta la altura máxima */
    object-fit: cover;  /* Cambia 'contain' por 'cover' para llenar mejor el espacio */
    border-radius: 10px;
}

.card-laura h3,
.card-angie h3,
.card-juliana h3 {
    margin-top: 10px;
    color: #5c4033;
    font-size: 1.2em;
}

.card-laura p,
.card-angie p,
.card-juliana p {
    font-size: 0.95em;
    color: #5c4033;
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

<div class="header-flex">
    <h1>🎨 Concurso de Ilustraciones</h1>
    <div class="corner-images">
        <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935311/logo_cenigraf-02_pa61ux.png" class="img-pagina1">
        <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745935416/logo_evento_diaArte_Mesa_de_trabajo_1_anparc.png" class="img-pagina2">
    </div>
</div>

     <div class="cards-row">
    <div class="card-laura">
        <a href="https://www.youtube.com/watch?v=NgOWLtYV_OU" target="_blank">
            <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745947457/poster_3D_web_Mesa_de_trabajo_1_wnj8wa.jpg" alt="Video 1">
            <h3>Youtube: Modelo iglesia San Francisco</h3>
            <p>Hecho por: Laura Sánchez, Andrés Garzón, Gabriela Ramírez, José Flórez, Juan Palacios</p>
        </a>
    </div>

    <div class="card-angie">
        <a href="https://youtu.be/krdpA6b81rM" target="_blank">
            <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745962705/032e435c-49d5-4034-972c-2389bbcca3e5_q14wt2.jpg" alt="Video 2">
            <h3>Youtube: Video sobre el barroco</h3>
            <p>Hecho por: Angie Lorena Sierra</p>
        </a>
    </div>

    <div class="card-juliana">
        <a href="https://open.spotify.com/episode/1awE8AypZGbGWhahdxgIah?si=VPccaIa8RRCEdaYUQbl2qQ" target="_blank">
            <img src="https://res.cloudinary.com/dsktdsxik/image/upload/v1745947299/arte_de_barroco_1_g1nbbi.png" alt="Spotify Track">
            <h3>Título 3</h3>
            <p>Hecho por: Juliana Letrado, Yuliana Sánchez</p>
        </a>
    </div>
</div>



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
                <p><strong>Autor:</strong> <?php echo $row['autor']; ?></p>
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
<footer style="text-align: center; padding: 20px; background-color: rgba(255, 248, 225, 0.8); color: #5c4033; font-family: Georgia, serif; font-size: 0.9em; border-top: 2px solid #d4af37;">
    Realizado por Nicolás Ríos
</footer>
</body>
</html>
