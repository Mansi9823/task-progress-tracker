<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Progress Tracker</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <!-- Style CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: radial-gradient(circle 248px at center, #16d9e3 0%, #30c7ec 47%, #46aef7 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 40px 30px 40px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: rgba(0, 0, 0, 0.3) 0 5px 15px;
            width: 70%;
            height: 700px;
            position: absolute;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            border-bottom: 1px solid;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-container > h1 {
            font-weight: 500;
        }

        .tasks-container {
            position: relative;
            width: 100%;
        }

        .action-button {
            display: flex;
            justify-content: center;
        }
        
        .action-button > button {
            width: 25px;
            height: 25px;
            font-size: 17px;
            display: flex !important;
            justify-content: center;
            align-items: center;
            margin: 0px 2px;
        }

        .dataTables_wrapper .dataTables_info {
            position: absolute !important;
            bottom: 20px !important;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="container">
            <div class="header-container">
                <h2>Task Progress Tracker</h2>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    Add Task
                </button>
            </div>

            <div class="tasks-container">
                <table class="table table-striped table-hover" id="taskTable">
                    <thead>
                        <tr>
                            <th scope="col">Task ID</th>
                            <th scope="col">Task Name</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date Added</th>
                            <th scope="col">Date Updated</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include('./conn/conn.php');

                            $stmt = $conn->prepare("SELECT * FROM tbl_task");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                $taskID = $row['tbl_task_id'];
                                $taskName = $row['task_name'];
                                $taskPriority = $row['task_priority'];
                                $taskStatus = $row['task_status'];
                                $dateAdded = $row['date_added'];
                                $dateUpdated = $row['date_updated'];
                                ?>

                                <tr>
                                    <th><?= $taskID ?></th>
                                    <td id="taskName-<?= $taskID ?>"><?= $taskName ?></td>
                                    <td id="taskPriority-<?= $taskID ?>"><?= $taskPriority ?></td>
                                    <td id="taskStatus-<?= $taskID ?>"><?= $taskStatus ?></td>
                                    <td id="dateAdded-<?= $taskID ?>"><?= $dateAdded ?></td>
                                    <td id="dateUpdated-<?= $taskID ?>"><?= $dateUpdated ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-secondary" onclick="updateTask(<?= $taskID ?>)">&#128393;</button>
                                            <button class="btn btn-danger" onclick="deleteTask(<?= $taskID ?>)">X</button>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTask" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTask">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-task.php" method="POST">
                        <div class="mb-3">
                            <label for="taskName" class="form-label">Task Name:</label>
                            <input type="text" class="form-control" id="taskName" name="task_name">
                        </div>
                        <div class="mb-3">
                            <label for="taskPriority" class="form-label">Task Priority:</label>
                            <select class="form-control" name="task_priority" id="taskPriority">
                                <option>-select-</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskStatus" class="form-label">Task Status:</label>
                            <select class="form-control" name="task_status" id="taskStatus">
                                <option>-select-</option>
                                <option value="Pending">Pending</option>
                                <option value="In-Progress">In-Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateTaskModal" tabindex="-1" aria-labelledby="updateTask" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTask">Update Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/update-task.php" method="POST">
                        <input type="hidden" id="updateTaskID" name="tbl_task_id">
                        <div class="mb-3">
                            <label for="updateTaskName" class="form-label">Update Task Name:</label>
                            <input type="text" class="form-control" id="updateTaskName" name="task_name">
                        </div>
                        <div class="mb-3">
                            <label for="updateTaskPriority" class="form-label">Update Task Priority:</label>
                            <select class="form-control" name="task_priority" id="updateTaskPriority">
                                <option>-select-</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateTaskStatus" class="form-label">Update Task Status:</label>
                            <select class="form-control" name="task_status" id="updateTaskStatus">
                                <option>-select-</option>
                                <option value="Pending">Pending</option>
                                <option value="In-Progress">In-Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready( function () {
            $('#taskTable').DataTable();
        });
        
        function updateTask(id) {
            $("#updateTaskModal").modal("show");

            let updateTaskName = $("#taskName-" + id).text();
            let updateTaskPriority = $("#taskPriority-" + id).text();
            let updateTaskStatus = $("#taskStatus-" + id).text();

            $("#updateTaskID").val(id);
            $("#updateTaskName").val(updateTaskName);
            $("#updateTaskPriority option").each(function() {
                let priority = $(this).text();
                if (priority === updateTaskPriority) {
                    $(this).prop("selected", true);
                    return false;
                }
            });
            $("#updateTaskStatus option").each(function() {
                let status = $(this).text();
                if (status === updateTaskStatus) {
                    $(this).prop("selected", true);
                    return false;
                }
            });
        }

        function deleteTask(id) {
            if (confirm("Do you want to delete this task?")) {
                window.location = "./endpoint/delete-task.php?task=" + id;
            }
        }
    </script>
</body>
</html>
