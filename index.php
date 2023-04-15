<?php
include_once 'config.php';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$connection) {
    throw new Exception("Database connection unsuccessful");
}

$queryTaskUpcoming = "SELECT * FROM tasks WHERE complete = 0 ORDER BY date";
$resultTaskUpcoming = mysqli_query($connection, $queryTaskUpcoming);

$queryTaskComplete = "SELECT * FROM tasks WHERE complete = 1 ORDER BY date";
$resultTaskComplete = mysqli_query($connection, $queryTaskComplete);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ToDo/Tasks</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto+Slab">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

    <style>
        body {
            margin-top: 30px;
        }

        #main {
            padding: 0px 150px 0px 150px;
        }

        #action {
            width: 150px;
        }
    </style>
</head>

<body>
    <div class="container" id="main">
        <h1>Task Manager</h1>
        <p>
            This is a simple project to manage all tasks
        </p>

        <?php
        if (mysqli_num_rows($resultTaskComplete) > 0) {
        ?>

            <h4>Completed Tasks:</h4>

            <table>
                <thead>
                    <tr>
                        <td></td>
                        <td>Id</td>
                        <td>Task</td>
                        <td>Date</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($data = mysqli_fetch_assoc($resultTaskComplete)) {
                        $timestamp = strtotime($data['date']);
                        $date = date("jS M, Y", $timestamp);
                    ?>

                        <tr>
                            <td><input class="label-inline" type="checkbox" value="<?php echo $data['id']; ?>"></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['task']; ?></td>
                            <td><?php echo $date; ?></td>
                            <td><a href="#">Delete</a></td>
                        </tr>

                    <?php
                    }
                    ?>

                </tbody>
            </table>

            <p>...</p>

        <?php
        }

        if (mysqli_num_rows($resultTaskUpcoming) == 0) {
        ?>

            <p>No Task Found</p>

        <?php
        } else {
        ?>

            <h4>Upcoming Tasks:</h4>
            <table>
                <thead>
                    <tr>
                        <td></td>
                        <td>Id</td>
                        <td>Task</td>
                        <td>Date</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($data = mysqli_fetch_assoc($resultTaskUpcoming)) {
                        $timestamp = strtotime($data['date']);
                        $date = date("jS M, Y", $timestamp);
                    ?>

                        <tr>
                            <td><input class="label-inline" type="checkbox" value="<?php echo $data['id']; ?>"></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['task']; ?></td>
                            <td><?php echo $date; ?></td>
                            <td><a href="#">Delete</a> | <a href="#">Complete</a></td>
                        </tr>

                    <?php
                    }
                    mysqli_close($connection);
                    ?>

                </tbody>
            </table>

            <select id="action">
                <option value="0">With Selected</option>
                <option value="del">Delete</option>
                <option value="complete">Mark as Complete</option>
            </select>
            <input class="button-primary" type="submit" value="Submit">
        <?php
        }
        ?>

        <p>...</p>

        <h4>Add Tasks</h4>
        <form method="post" action="tasks.php">
            <fieldset>
                <?php
                $added = $_GET['added'];

                if ($added) {
                    echo "<p>Task added successfully</p>";
                }

                ?>
                <label for="task">Task</label>
                <input type="text" placeholder="Task details" id="task" name="task">
                <label for="date">Date</label>
                <input type="text" placeholder="Date" id="date" name="date">

                <input type="submit" value="Add Task" class="button-primary">

                <input type="hidden" value="add" name="action">
            </fieldset>
        </form>
    </div>
</body>

</html>