<?php
include "includes/db.php";
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado. Solo administradores.";
    exit();
}

// Editar título de ilustración
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado. Solo administradores.";
    exit();
}

// Editar detalles de la ilustración
if (isset($_POST['edit_details'])) {
    $id = $_POST['id'];
    $new_title = mysqli_real_escape_string($conn, $_POST['new_title']);
    $new_tecnica = mysqli_real_escape_string($conn, $_POST['new_tecnica']);
    $new_dimensiones = mysqli_real_escape_string($conn, $_POST['new_dimensiones']);
    $new_anio = mysqli_real_escape_string($conn, $_POST['new_anio']);
    $new_autor = mysqli_real_escape_string($conn, $_POST['new_autor']);

    // Actualizar en la base de datos
    mysqli_query($conn, "UPDATE ilustraciones SET 
        titulo = '$new_title', 
        tecnica = '$new_tecnica', 
        dimensiones = '$new_dimensiones', 
        anio = '$new_anio' 
        autor = '$new_autor' 
        WHERE id = $id");
}

// Eliminar ilustración
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Buscar la imagen
    $getImage = mysqli_query($conn, "SELECT imagen FROM ilustraciones WHERE id = $id");
    if ($row = mysqli_fetch_assoc($getImage)) {
        $filePath = "uploads/" . $row['imagen'];
        if (file_exists($filePath)) {
            @unlink($filePath); // Ignorar errores de permisos
        }
    }

    // Eliminar votos relacionados (clave foránea)
    mysqli_query($conn, "DELETE FROM votos WHERE ilustracion_id = $id");

    // Eliminar la ilustración
    mysqli_query($conn, "DELETE FROM ilustraciones WHERE id = $id");

    header("Location: admin.php");
    exit();
}

// Filtros
$filtro_usuario = $_GET['usuario'] ?? '';
$orden = $_GET['orden'] ?? 'DESC';

// Query dinámica
$sql = "
    SELECT ilustraciones.*, usuarios.usuario 
    FROM ilustraciones 
    LEFT JOIN usuarios ON ilustraciones.usuario_id = usuarios.id
";
$condiciones = [];
if (!empty($filtro_usuario)) {
    $filtro_usuario = mysqli_real_escape_string($conn, $filtro_usuario);
    $condiciones[] = "usuarios.usuario = '$filtro_usuario'";
}
if (count($condiciones) > 0) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}
$sql .= " ORDER BY ilustraciones.votos $orden";
$result = mysqli_query($conn, $sql);

// Obtener usuarios únicos para el filtro
$usuarios_result = mysqli_query($conn, "
    SELECT DISTINCT usuarios.usuario 
    FROM usuarios 
    JOIN ilustraciones ON usuarios.id = ilustraciones.usuario_id
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsivo -->
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
            max-width: 1100px;
            margin: 120px auto;
            background-color: rgba(255, 248, 225, 0.97);
            border: 5px double #d4af37;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 25px rgba(139, 94, 60, 0.4);
        }

        h2 {
            font-size: 2.5em;
            color: #8b5e3c;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px #fff;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }

        .artwork {
            background-color: #fffdf5;
            border: 2px solid #cba135;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            border: 2px solid #d2b48c;
        }

        form {
            margin-top: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #a67c52;
            margin-bottom: 10px;
        }

        button {
            background-color: #a67c52;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            background-color: #8c6239;
        }

        button[style*="background-color: #c0392b;"] {
            background-color: #c0392b;
            margin-top: 10px;
        }

        button[style*="background-color: #c0392b;"]:hover {
            background-color: #96281b;
        }

        select {
            padding: 8px;
            border: 1px solid #a67c52;
            border-radius: 5px;
            margin-right: 10px;
            margin-top: 10px;
            width: 100%;
        }

        a {
            color: #6f4e37;
            text-decoration: underline;
            font-weight: bold;
        }

        form[method="GET"] {
            margin-bottom: 30px;
            background: #fffaf0;
            padding: 15px;
            border-radius: 10px;
            border: 2px dashed #d4af37;
        }

        form[method="GET"] label {
            display: block;
            margin-bottom: 5px;
            margin-top: 10px;
        }

        form[method="GET"] button {
            width: 100%;
            margin-top: 15px;
        }

        @media screen and (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            h2 {
                font-size: 1.8em;
            }

            .gallery {
                grid-template-columns: 1fr;
            }

            input[type="text"],
            select,
            button {
                width: 100%;
                font-size: 1em;
            }

            button {
                padding: 10px;
            }

            a {
                display: block;
                margin: 10px 0;
                text-align: center;
            }
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

    <div class="container">
        <h2>🎨 Panel de Administración</h2>
        <a href="inicio.php">← Volver a la galería</a>

        <!-- Filtros -->
        <form method="GET">
            <label for="usuario">Filtrar por usuario:</label>
            <select name="usuario" id="usuario">
                <option value="">Todos</option>
                <?php while ($user = mysqli_fetch_assoc($usuarios_result)) { ?>
                    <option value="<?php echo $user['usuario']; ?>" <?php if ($filtro_usuario == $user['usuario']) echo 'selected'; ?>>
                        <?php echo $user['usuario']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="orden">Ordenar por votos:</label>
            <select name="orden" id="orden">
                <option value="DESC" <?php if ($orden == 'DESC') echo 'selected'; ?>>Más votados</option>
                <option value="ASC" <?php if ($orden == 'ASC') echo 'selected'; ?>>Menos votados</option>
            </select>

            <button type="submit">Aplicar</button>
        </form>

        <!-- Ilustraciones -->
        <div class="gallery">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="artwork">
                    <img src="<?php echo $row['imagen1']; ?>" alt="Ilustración">

                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <label for="new_title">Título:</label>
                        <input type="text" name="new_title" value="<?php echo htmlspecialchars($row['titulo']); ?>" required>

                        <label for="new_tecnica">Técnica:</label>
                        <input type="text" name="new_tecnica" value="<?php echo htmlspecialchars($row['tecnica']); ?>" required>

                        <label for="new_dimensiones">Dimensiones:</label>
                        <input type="text" name="new_dimensiones" value="<?php echo htmlspecialchars($row['dimensiones']); ?>" required>

                        <label for="new_anio">Año:</label>
                        <input type="text" name="new_anio" value="<?php echo htmlspecialchars($row['anio']); ?>" required>

                        <label for="new_autor">Autor:</label>
                        <input type="text" name="new_autor" value="<?php echo htmlspecialchars($row['autor']); ?>" required>

                        <button type="submit" name="edit_details">Editar Detalles</button>
                    </form>
                    <p><strong>Autor:</strong> <?php echo $row['usuario'] ?? 'Anónimo'; ?></p>
                    <p><strong>Votos:</strong> <?php echo $row['votos']; ?></p>
                    <a href="admin.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('¿Eliminar esta ilustración?')">
                        <button style="background-color: #c0392b;">Eliminar</button>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>

