<?php
require 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM contactos WHERE id = ?');
$stmt->execute([$id]);
$contacto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    if (empty($nombre) || empty($telefono) || empty($email) || empty($direccion) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor, complete todos los campos obligatorios y asegúrese de que el email sea válido.";
    } else {
        $stmt = $pdo->prepare('UPDATE contactos SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?');
        if ($stmt->execute([$nombre, $telefono, $email, $direccion, $id])) {
            $success = "Conexión correcta y datos actualizados con éxito.";
        } else {
            $error = "Ocurrió un problema al actualizar los datos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Contacto</h1>
    <form method="post" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($contacto['nombre']) ?>" required><br>
        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?= htmlspecialchars($contacto['telefono']) ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($contacto['email']) ?>" required><br>
        <label>Dirección:</label><br>
        <textarea name="direccion" required><?= htmlspecialchars($contacto['direccion']) ?></textarea><br>
        <button type="submit">Guardar</button>
    </form>
    <?php if (isset($error)): ?>
    <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
    <p style="color:green"><?= $success ?></p>
    <?php endif; ?>
    <a href="index.php">Volver</a>
</body>
</html>
