<?php 
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_name'], $_POST['task_priority'], $_POST['task_status'])) {
        $taskID = $_POST['tbl_task_id'];
        $taskName = $_POST['task_name'];
        $taskPriority = $_POST['task_priority'];
        $taskStatus = $_POST['task_status'];
        $dateUpdated = date("Y-m-d");

        try {
            $stmt = $conn->prepare("UPDATE tbl_task SET task_name = :task_name, task_priority = :task_priority, task_status = :task_status, date_updated = :date_updated WHERE tbl_task_id = :tbl_task_id");

            $stmt->bindParam(":tbl_task_id", $taskID, PDO::PARAM_INT);
            $stmt->bindParam(":task_name", $taskName, PDO::PARAM_STR);
            $stmt->bindParam(":task_priority", $taskPriority, PDO::PARAM_STR);
            $stmt->bindParam(":task_status", $taskStatus, PDO::PARAM_STR);
            $stmt->bindParam(":date_updated", $dateUpdated, PDO::PARAM_STR);

            $stmt->execute();
            echo "
                <script>
                    alert('Task Updated Successfully');
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
