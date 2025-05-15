<?php
require "connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pid = $_POST["patient_id"];
  $pname = $_POST["patient_name"];
  $did = $_POST["doctor_id"];
  $dname = $_POST["doctor_name"];
  $disease = $_POST["disease"];
  $prescription = $_POST["prescription"];
  $appointment_id = $_POST["appointment_id"];

  $sql = "INSERT INTO prescription (patient_id, patient_name, doctor_id, doctor_name, disease, prescription) 
          VALUES ('$pid', '$pname', '$did', '$dname', '$disease', '$prescription')";
          
  if ($conn->query($sql) === TRUE) {
    $sql1 = "UPDATE appointments SET status = -1 WHERE appointment_id = $appointment_id";
    $conn->query($sql1);
    echo "<script>alert('Prescribed successfully');
              window.location.href = '../backend/doctor.php';
        </script>";
  } else {
    echo "<script>alert('Failed to prescribe');
          window.history.back();
        </script>";
  }
}
?>
