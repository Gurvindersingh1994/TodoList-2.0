
<?php
include('./config.php');
$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$id = $_POST["id"] ?? null;


if (!$id) {
    echo 'id is not selected';
    header('Location:index.php');
    exit;
}

$statement = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();

header("Location: index.php");
