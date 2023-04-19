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

        #bulkaction {
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
                            <td></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['task']; ?></td>
                            <td><?php echo $date; ?></td>
                            <td><a data-taskid="<?php echo $data['id']; ?>" href="#" class="delete">Delete</a> | <a data-taskid="<?php echo $data['id']; ?>" href="#" class="incomplete">Incomplete</a></td>
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
                <form action="tasks.php" method="POST">
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
                                <td><input name="taskids[]" class="label-inline" type="checkbox" value="<?php echo $data['id']; ?>"></td>
                                <td><?php echo $data['id']; ?></td>
                                <td><?php echo $data['task']; ?></td>
                                <td><?php echo $date; ?></td>
                                <td><a data-taskid="<?php echo $data['id']; ?>" href="#" class="delete">Delete</a> | <a data-taskid="<?php echo $data['id']; ?>" href="#" class="complete">Complete</a></td>
                            </tr>

                        <?php
                        }

                        mysqli_close($connection);
                        ?>

                    </tbody>
            </table>

            <select id="bulkaction" name="action">
                <option value="0">With Selected</option>
                <option value="bulkdelete">Delete</option>
                <option value="bulkcomplete">Mark as Complete</option>
            </select>

            <input class="button-primary" id="bulksubmit" type="submit" value="Submit">
            </form>
        <?php
        }

        ?>

        <p>...</p>

        <h4>Add Tasks</h4>
        <form method="POST" action="tasks.php">
            <fieldset>
                <?php
                $added = $_GET['added'] ?? '';

                if ($added) {
                    echo "<p>Task added successfully</p>";
                }

                ?>

                <label for="task">Task</label>
                <input type="text" placeholder="Task details" id="task" name="task">
                <label for="date">Date</label>
                <input type="text" placeholder="Date" id="date" name="date">

                <input type="submit" value="Add Task" class="button-primary">

                <input type="hidden" name="action" value="add">
            </fieldset>
        </form>
    </div>

    <form action="tasks.php" method="POST" id="completeform">
        <input type="hidden" id="caction" name="action" value="complete">
        <input type="hidden" id="taskid" name="taskid">
    </form>

    <form action="tasks.php" method="POST" id="deleteform">
        <input type="hidden" id="caction" name="action" value="delete">
        <input type="hidden" id="dtaskid" name="dtaskid">
    </form>

    <form action="tasks.php" method="POST" id="incompleteform">
        <input type="hidden" id="caction" name="action" value="incomplete">
        <input type="hidden" id="itaskid" name="itaskid">
    </form>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script>
    ;
    (function($) {
        $(document).ready(function() {
            $(".complete").on('click', function() {
                var id = $(this).data("taskid");
                $("#taskid").val(id);
                $("#completeform").submit();
            });

            $(".delete").on('click', function() {
                if (confirm("Are you sure you want to delete this task?")) {
                    var id = $(this).data("taskid");
                    $("#dtaskid").val(id);
                    $("#deleteform").submit();
                }
            });

            $(".incomplete").on('click', function() {
                var id = $(this).data("taskid");
                $("#itaskid").val(id);
                $("#incompleteform").submit();
            });

            $("#bulksubmit").on('click', function() {
                if ($("#bulkaction").val() == "bulkdelete") {
                    if (!confirm("Are you sure to delete this tasks?")) {
                        return false;
                    }
                }
            });
        });
    })(jQuery);
</script>

</html>