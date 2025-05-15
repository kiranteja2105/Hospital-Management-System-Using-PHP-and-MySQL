<?php
session_start();
require "connection.php"; 

$email = $_POST['Loginemail'];
$password = $_POST['Loginpassword'];

$sql_patient = "SELECT * FROM patient WHERE patient_email='$email' AND password='$password'";
$result_patient = $conn->query($sql_patient);

$sql_admin = "SELECT * FROM admin WHERE admin_email='$email' AND password='$password'";
$result_admin = $conn->query($sql_admin);

$sql_doctor = "SELECT * FROM doctors WHERE doctor_email='$email' AND password='$password'";
$result_doctor = $conn->query($sql_doctor);

if ($result_patient && $result_patient->num_rows > 0) {
    $row = $result_patient->fetch_assoc();
    $_SESSION["pid"] = $row["patient_id"];
    $_SESSION["pname"] = $row["patient_name"];
?>
    <script>
        alert('Login Successful!');
        window.location.href = '../backend/patient1.php';
    </script>
<?php
} 
else if ($result_admin && $result_admin->num_rows > 0) {
?>
    <script>
        alert('Login Successful!');
        window.location.href = '../backend/admin.php';
    </script>
<?php
} 
else if ($result_doctor && $result_doctor->num_rows > 0) {
    $row = $result_doctor->fetch_assoc();
    $_SESSION["did"] = $row["doctor_id"];
    $_SESSION["dname"] = $row["doctor_name"];
    ?>
        <script>
            alert('Login Successful!');
            window.location.href = '../backend/doctor.php';
        </script>
    <?php
    } 
else {
?>
    <script>
        alert('Invalid Email or Password!');
        window.history.back();
    </script>
 <?php
}

$conn->close();
?>
