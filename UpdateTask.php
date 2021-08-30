<!-- Setting up the Database -->
<?php
include('./config.php');
$pdo = new PDO($dsn, $username, $password);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET["id"] ?? null;

if (!$id) {
    echo 'id is not selected';
    header('Location:index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM tasks WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$description = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];

$taskDescription = $pdo->prepare('SELECT * FROM `tasks` ORDER BY `id` DESC');
$taskDescription->execute();
$description = $taskDescription->fetchAll(PDO::FETCH_ASSOC);  // fetching the data from the database


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // code to save data in the database
    $TaskValue = $_POST['TaskValue'];

    if (!$TaskValue) {
        $errors[] = 'Enter the task';
    }

    if (empty($errors)) {

        $statement = $pdo->prepare("UPDATE `tasks` SET `current_task`='$TaskValue' WHERE id = :id");

        $statement->bindValue(':id', $id);
        $statement->execute();

        header("Location: index.php");
    }
}
?>

<!-- //HTML Code -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title> Todo List Web App</title>
</head>

<body>

    <div class="container">
        <!-- NAV BAR -->
        <nav id="navbar-example2" class="navbar navbar-light bg-light px-2">
            <a class="navbar-brand" href="index.php"> <b> Todo List Web App</b> </a>

        </nav>

        <!-- Display the error message here -->
        <!-- if statement to check validation for errors -->
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) : ?>
                    <div>
                        <?php echo $error ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!--  submit the task -->
        <form action="" method="post">

            <div class="mb-2">
                <label class="form-label" class="submitButton"> <b>Edit the task </b> </label>
                <input class="form-control" name="TaskValue">
            </div>

            <div class="contentCenter">
                <button type="submit" class="btn btn-primary">Update </button>
                <a href="index.php" class="btn btn-primary">
                    Back to Main Page
                </a>
            </div>
        </form>



        <div class="taskList">

            <!-- Current Tasks -->

            <ul class="list-group">
                <h2>Task List</h2>

                <?php foreach ($description as $i => $desc) { ?>
                    <li class="list-group-item">
                        <?php echo $i + 1 ?>
                        <?php echo '.' ?>
                        <?php echo $desc['current_task'] ?>
                    </li>
                <?php } ?>
            </ul>
        </div>


        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </div>
</body>

</html>