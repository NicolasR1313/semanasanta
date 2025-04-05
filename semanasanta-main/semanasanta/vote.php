<?php
include "includes/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['mensaje'] = "⚠️ Debes iniciar sesión para votar.";
    header("Location: inicio.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    // Verificar si el usuario ya ha votado por cualquier ilustración
    $check_vote = "SELECT * FROM votos WHERE usuario_id = '$user_id'";
    $result = mysqli_query($conn, $check_vote);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['mensaje'] = "❌ Ya has votado por otra ilustración.";
        header("Location: inicio.php");
        exit();
    }

    // Registrar el voto
    $insert_vote = "INSERT INTO votos (usuario_id, ilustracion_id) VALUES ('$user_id', '$id')";
    mysqli_query($conn, $insert_vote);

    // Actualizar el conteo de votos
    $update_votes = "UPDATE ilustraciones SET votos = votos + 1 WHERE id = '$id'";
    mysqli_query($conn, $update_votes);

    $_SESSION['mensaje'] = "✅ ¡Gracias por tu voto!";
    header("Location: inicio.php");
    exit();
}
?>

<!-- Esto va después, cuando ya no hay redirecciones ni lógica de sesión -->
<link rel="stylesheet" href="css/styles.css">
