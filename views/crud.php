<?php

$host = "localhost";
$dbname = "crud_database";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$editando = false;
$id = $first = $last = $email = $phone = $location = $rol = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, location, rol) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['location'], $_POST['rol']]);
    header("Location: crud.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $stmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, location=?, rol=? WHERE id=?");
    $stmt->execute([$_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['location'], $_POST['rol'], $_POST['id']]);
    header("Location: crud.php");
    exit();
}


if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: crud.php");
    exit();
}


if (isset($_GET['editar'])) {
    $editando = true;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_GET['editar']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $id = $row['id'];
        $first = $row['first_name'];
        $last = $row['last_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $location = $row['location'];
        $rol = $row['rol'];
    }
}


if (isset($_GET['exportar']) && $_GET['exportar'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=crud_data.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'First', 'Last', 'Email', 'Phone', 'Location', 'Rol'));
    
    $rows = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        fputcsv($output, $r);
    }
    fclose($output);
    exit();
}


$usuarios = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Database</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; color: #333; }
        h1 { font-size: 28px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e0e0e0; font-size: 14px; }
        th { background-color: #fff; font-weight: bold; border-bottom: 2px solid #ccc; }
        tr:hover { background-color: #f1f1f1; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-block; }
        .btn-edit { background-color: #ffc107; color: #000; }
        .btn-del { background-color: #dc3545; color: #fff; }
        .btn-csv { background-color: #007bff; color: #fff; }
        .btn-add { background-color: #28a745; color: #fff; }
        .form-container { background: #fff; padding: 20px; border: 1px solid #ccc; margin-bottom: 25px; border-radius: 5px; width: 500px; }
        .form-group { margin-bottom: 10px; display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; font-weight: bold; margin-bottom: 3px; }
        .form-group input { padding: 6px; border: 1px solid #ccc; border-radius: 3px; }
    </style>
</head>
<body>

    <h1>CRUD Database</h1>

    
    <div class="form-container">
        <h3><?php echo $editando ? 'Editar Registro' : 'Agregar Nuevo Item'; ?></h3>
        <form action="crud.php" method="POST">
            <input type="hidden" name="accion" value="<?php echo $editando ? 'actualizar' : 'crear'; ?>">
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php endif; ?>

            <div class="form-group"><label>First Name</label><input type="text" name="first_name" value="<?php echo htmlspecialchars($first); ?>" required></div>
            <div class="form-group"><label>Last Name</label><input type="text" name="last_name" value="<?php echo htmlspecialchars($last); ?>" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>"></div>
            <div class="form-group"><label>Location</label><input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>"></div>
            <div class="form-group"><label>Rol</label><input type="text" name="rol" value="<?php echo htmlspecialchars($rol); ?>"></div>
            
            <button type="submit" class="btn btn-add" style="margin-top: 10px;"><?php echo $editando ? 'Guardar Cambios' : 'Add Item'; ?></button>
            <?php if ($editando): ?>
                <a href="crud.php" class="btn" style="background:#6c757d; color:#fff;">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

  
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First</th>
                <th>Last</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Rol</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['phone']); ?></td>
                    <td><?php echo htmlspecialchars($u['location']); ?></td>
                    <td><?php echo htmlspecialchars($u['rol']); ?></td>
                    <td>
                        <a href="crud.php?editar=<?php echo $u['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="crud.php?eliminar=<?php echo $u['id']; ?>" class="btn btn-del" onclick="return confirm('¿Seguro que deseas borrar este registro?');">Del</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No hay registros cargados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <a href="crud.php?exportar=csv" class="btn btn-csv">Download CSV</a>

</body>
</html>