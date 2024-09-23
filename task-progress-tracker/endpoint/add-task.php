<?php 
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_name'], $_POST['task_priority'], $_POST['task_status'])) {
        $taskName = $_POST['task_name'];
        $taskPriority = $_POST['task_priority'];
        $taskStatus = $_POST['task_status'];
        $dateAdded = date("Y-m-d");


        try {
            $stmt = $conn->prepare("INSERT INTO tbl_task (task_name, task_priority, task_status, date_added, date_updated) VALUES (:task_name, :task_priority, :task_status, :date_added, :date_updated)");

            $stmt->bindParam(":task_name", $taskName, PDO::PARAM_STR);
            $stmt->bindParam(":task_priority", $taskPriority, PDO::PARAM_STR);
            $stmt->bindParam(":task_status", $taskStatus, PDO::PARAM_STR);
            $stmt->bindParam(":date_added", $dateAdded, PDO::PARAM_STR);
            $stmt->bindParam(":date_updated", $dateAdded, PDO::PARAM_STR);

            $stmt->execute();

            echo "
                <script>
                    alert('Task Added Successfully');
                    window.location.href = 'http://localhost/task-progress-tracker/';
                </script>
            ";

        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/task-progress-tracker/';
            </script>
        ";
    }
}

?>