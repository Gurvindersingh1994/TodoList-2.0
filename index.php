<!-- Setting up the Database -->


<?php

$dsn = 'mysql:host=mi-linux.wlv.ac.uk;dbname=db2039106';
$username = '2039106';
$password = 'Simran2039106@';
include('./config.php');
$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];

$taskDescription = $pdo->prepare('SELECT * FROM `tasks` ORDER BY `id` DESC');
$taskDescription->execute();
$description = $taskDescription->fetchAll(PDO::FETCH_ASSOC); // fetching the data from the database


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // code to save data in the database
    $TaskValue = $_POST['TaskValue'];


    if (!$TaskValue) {
        $errors[] = 'Enter the task';
    }

    if (empty($errors)) {

        $statement = $pdo->prepare("INSERT INTO `tasks`(`current_task`) VALUES ('$TaskValue')");

        //$statement->bindValue('$TaskValue', $TaskValue, PDO::PARAM_INT);
        $statement->bindValue('$TaskValue', $TaskValue, PDO::PARAM_STR);
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
    <!-- NAV BAR -->

    <div class="container">


        <nav id="navbar-example2" class="navbar navbar-light bg-light px-3 backcolor">
            <a class="navbar-brand" href="#"> <b> Todo List Web App</b> </a>

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
                <label class="form-label" class="submitButton"> <b>Enter Your Task</b> </label>
                <input class="form-control" name="TaskValue">
            </div>

            <div class="contentCenter">
                <button type="submit" class="btn btn-primary">Submit</button>
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

                        <div class="listItems" id="button">



                            <form action="dataDelete.php" style="display:inline-block" method="post">
                                <input type="hidden" name="id" value="<?php echo $desc['id'] ?>">
                                <button type="submit" class="btn-danger btn-sm">Delete</button>
                            </form>

                            <a href="UpdateTask.php?id=<?php echo $desc['id'] ?>" type="button" class="btn btn-info btn-sm">Update </a>

                        </div>
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