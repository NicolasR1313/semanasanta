<?php
include "../includes/db.php";
session_start();

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../index.php");
}

$query = "SELECT * FROM ilustraciones";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head><title>Panel de Administración</title></head>
<body>
    <h2>Panel de Administración</h2>
    <table border="1">
        <tr><th>Ilustración</th><th>Título</th><th>Eliminar</th></tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><img src="../uploads/<?php echo $row['imagen']; ?>" width="100"></td>
                <td><?php echo $row['titulo']; ?></td>
                <td>
                    <form action="../delete.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
