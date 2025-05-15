<?php
require "connection.php";

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    $appointment_id = intval($appointment_id);

    $sql = "UPDATE appointments SET status = 0 WHERE appointment_id = $appointment_id";

    if ($conn->query($sql) === TRUE) {
        echo "Appointment cancelled successfully.";
    } else {
        echo "Error cancelling appointment: " . $conn->error;
    }

    $conn->close();
} else {
    echo "No appointment ID provided.";
}
?>
