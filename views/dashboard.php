<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $type = $_POST['type'];
    $photo = $_FILES['photo'];

    $photoPath = '../uploads/' . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photoPath);

    $stmt = $pdo->prepare("INSERT INTO animals (name, age, type, photo) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $age, $type, $photoPath]);
}

$animals = $pdo->query("SELECT * FROM animals ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Registro de Animales</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="number" name="age" placeholder="Edad" required>
        <input type="text" name="type" placeholder="Tipo" required>
        <input type="file" name="photo" required>
        <button type="submit">Registrar Animal</button>
    </form>

    <h2>Lista de Animales</h2>
    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($animals as $animal): ?>
                <tr>
                    <td><img src="<?= $animal['photo'] ?>" alt="<?= $animal['name'] ?>" height="50"></td>
                    <td><?= $animal['name'] ?></td>
                    <td><?= $animal['age'] ?></td>
                    <td><?= $animal['type'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $animal['id'] ?>">Editar</a>
                        <a href="delete.php?id=<?= $animal['id'] ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../logout.php">Cerrar sesión</a>
</body>
</html>
