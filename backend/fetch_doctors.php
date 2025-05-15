<?php
require "connection.php";

if (isset($_POST['specialization'])) {
    $sp = $_POST['specialization'];
    $sql = "SELECT doctor_id, doctor_name FROM doctors WHERE doctor_specialization = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<option value='' disabled selected>Select Doctor</option>";
        while ($row = $result->fetch_assoc()) {
            $doctor_id = htmlspecialchars($row['doctor_id']);
            $doctor_name = htmlspecialchars($row['doctor_name']);
            echo "<option value='{$doctor_id}|{$doctor_name}'>{$doctor_name}</option>";
        }
    } else {
        echo "<option value='' disabled>No doctors found</option>";
    }

    $stmt->close();
} else {
    echo "<option value='' disabled>Select specialization first</option>";
}
?>
