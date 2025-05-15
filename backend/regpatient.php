<?php
require "connection.php";
$patient_name = $_POST['patient_name'];
$patient_mobile = $_POST['patient_mobile'];
$patient_email = $_POST['patient_email'];
$patient_password = $_POST['patient_password'];
$sql = "INSERT INTO patient (patient_name, patient_mobile, patient_email, password) 
        VALUES ('$patient_name', '$patient_mobile', '$patient_email', '$patient_password')";
if ($conn->query($sql) === TRUE) {
?>
    <script>
        alert("Registration Successfull");
        window.location.href = '../webpages/index.html?registered=1';
    </script>
<?php
} else {
?>
    <script>
        alert("Registration Failed");
        window.history.back();
    </script>
<?php
}
$conn->close();
?>
