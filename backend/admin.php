<?php
require "connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addDoctor'])) {
    $doctor = $_POST['doctorName'];
    $specialization = $_POST['doctorSpecialization'];
    $mobile = $_POST['doctorMobile'];
    $email = $_POST['doctorEmail'];
    $password = $_POST['doctorPassword'];
    $sql = "INSERT INTO doctors (doctor_name, doctor_specialization, doctor_mobile, doctor_email, password)
            VALUES ('$doctor', '$specialization', '$mobile', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Doctor Added successfully');
              window.location.href = '../backend/admin.php';
        </script>";
    } else {
        echo "<script>alert('Failed to book appointment');
          window.history.back();
        </script>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteDoctor'])) {
    $email = $_POST['email'];
    $sql = "DELETE FROM doctors WHERE doctor_email = '$email'";


    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Doctor Deleted successfully');
              window.location.href = '../backend/admin.php';
        </script>";
    } else {
        echo "<script>alert('Failed to book appointment');
          window.history.back();
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assest/CSS/admin.css">
  <style>
    table {
    width: 100%;
    margin-top:10px;
    border-collapse: separate;
    border-spacing: 0 0px; 
    background-color: #f9fafb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    th, td {
        text-align: center;
        padding: 12px 16px;
        background-color: #ffffff; 
        border: 1px solid #e5e7eb; 
        border-radius: 8px; 
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    th {
        background-color: #f3f4f6;
        font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>K Hospitals</h2>
    <ul>
      <li onclick="showSection('dashboard')">Dashboard</li>
      <li onclick="showSection('displayAppointments')">Display Appointments</li>
      <li onclick="showSection('displayPrescriptions')">Display Prescriptions</li>
      <li onclick="showSection('displayDoctors')">Display Doctors</li>
      <li onclick="showSection('addDoctor')">Add Doctor</li>
      <li onclick="showSection('deleteDoctor')">Delete Doctor</li>
      <li onclick="showSection('displayPatients')">Display Patients</li>
    </ul>
  </div>

  <div class="main-content">
    <!-- Dashboard -->
    <div id="dashboard" class="section active">
      <h1>Welcome Admin</h1>
      <div class="cards-container">
        <div class="top-card">
          <div class="card" onclick="showSection('displayDoctors')">
            <h2>Display Doctors</h2>
          </div>
        </div>
        <div class="bottom-cards">
          <div class="card" onclick="showSection('displayAppointments')">
            <h2>Display Appointments</h2>
          </div>
          <div class="card" onclick="showSection('displayPrescriptions')">
            <h2>Display Prescriptions</h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Display Appointments -->
    <div id="displayAppointments" class="section">
      <h1>Display Appointments</h1>
      <?php
        $sql = "SELECT * FROM appointments";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Patient Name</th>
          <th>Doctor Name</th>
          <th>Appointment Date</th>
          <th>Appointment Time</th>
          <th>Status</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["patient_name"] ?></td>
        <td><?php echo $row["doctor_name"] ?></td>
        <td><?php echo $row["appointment_date"] ?></td>
        <td><?php echo $row["appointment_time"] ?></td>
        <td>
          <?php
            if($row["status"]==1)
            {
              echo "Active";
            }
            else if($row["status"]==-1)
            {
              echo "Prescribed";
            }
            else{
              echo "InActive";
            }
          ?>
        </td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>

    <!-- Display Prescriptions -->
    <div id="displayPrescriptions" class="section">
      <h1>Display Prescriptions</h1>
      <?php
        $sql = "SELECT * FROM prescription";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Patient Name</th>
          <th>Doctor Name</th>
          <th>Disease</th>
          <th>Prescription</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["patient_name"] ?></td>
        <td><?php echo $row["doctor_name"] ?></td>
        <td><?php echo $row["disease"] ?></td>
        <td><?php echo $row["prescription"] ?></td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>

    <!-- Display Doctors -->
    <div id="displayDoctors" class="section">
      <h1>Display Doctors</h1>
      <?php
        $sql = "SELECT * FROM doctors";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Doctor Name</th>
          <th>Doctor Specialization</th>
          <th>Doctor Mobile</th>
          <th>Doctor Email</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["doctor_name"] ?></td>
        <td><?php echo $row["doctor_specialization"] ?></td>
        <td><?php echo $row["doctor_mobile"] ?></td>
        <td><?php echo $row["doctor_email"] ?></td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>

    <!-- Add Doctor -->
    <div id="addDoctor" class="section">
      <h1>Add Doctor</h1>
      <form method="POST" action="">
        <input type="text" name="doctorName" placeholder="Doctor Name" required>
        <select name="doctorSpecialization" id="doctorSpecialization" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; font-size: 16px;">
            <option value="" disabled selected>Select Specialization</option>
            <option value="cardiology">Cardiology</option>
            <option value="General">General</option>
            <option value="Nerve">Nerve</option>
        </select>
        <input type="tel" name="doctorMobile" placeholder="Doctor Mobile Number" pattern="[0-9]{10}" required>
        <input type="email" name="doctorEmail" placeholder="Doctor Email ID" required>
        <input type="password" name="doctorPassword" placeholder="Password" required>
        <button type="submit" name="addDoctor">Add Doctor</button>
      </form>
    </div>

    <!-- Delete Doctor -->
    <div id="deleteDoctor" class="section">
      <h1>Delete Doctor</h1>
      <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter Doctor Email to Delete" required>
        <button type="submit" name="deleteDoctor">Delete Doctor</button>
       </form>

    </div>

    <!-- Display Patients -->
    <div id="displayPatients" class="section">
      <h1>Display Patients</h1>
      <?php
        $sql = "SELECT * FROM patient";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Patient Name</th>
          <th>Patient Email</th>
          <th>Patient Mobile</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["patient_name"] ?></td>
        <td><?php echo $row["patient_email"] ?></td>
        <td><?php echo $row["patient_mobile"] ?></td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>
  </div>

  <script>
    function showSection(id) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.getElementById(id).classList.add('active');
    }
  </script>
</body>
</html>

<?php $conn->close(); ?>
