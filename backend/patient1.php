<?php
require "connection.php";
session_start();
$pid=$_SESSION["pid"];
$pname=$_SESSION["pname"];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor'], $_POST['appointment_date'], $_POST['appointment_time'])) {
  $specialization = $_POST['specialization'];
  list($doctor, $doctorname) = explode('|', $_POST['doctor']);
  $date = $_POST['appointment_date'];
  $time = $_POST['appointment_time'];

  $sql="INSERT INTO appointments (patient_id,patient_name, doctor_id, doctor_name, specialization, appointment_date, appointment_time) 
        VALUES ('$pid','$pname','$doctor','$doctorname','$specialization','$date','$time')";
    if ($conn->query($sql)) {
        echo "<script>alert('Appointment booked successfully');
              window.location.href = '../backend/patient1.php';
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
  <title>Patient Dashboard</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assest/CSS/patient.css">
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
  button{
    color: red;
    border:none;
    border-radius:5px;
    font-weight: bold;
    background: transparent;
  }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>K Hospitals Patient Panel</h2>
    <ul>
      <li onclick="showSection('dashboard')">Dashboard</li>
      <li onclick="showSection('book')">Book</li>
      <li onclick="showSection('appointments')">Appointments</li>
      <li onclick="showSection('prescriptions')">Prescriptions</li>
      <!-- <li onclick="showSection('logout')">Logout</li> -->
    </ul>
  </div>

  <div class="main-content">
    <div id="dashboard" class="section active">
      <h1>Welcome <?php echo "".$pname.""?></h1>
      <div class="cards-container">
        <div class="top-card">
          <div class="card" onclick="showSection('book')">
            <h2>Book Appointment</h2>
          </div>
        </div>
        <div class="bottom-cards">
          <div class="card" onclick="showSection('appointments')">
            <h2>Appointments</h2>
          </div>
          <div class="card" onclick="showSection('prescriptions')">
            <h2>Prescriptions</h2>
          </div>
        </div>
      </div>
    </div>

    <div id="book" class="section">
      <h1 style="margin-bottom: 20px;">Book Appointment</h1>
      <form action="" method="post">
        <select name="specialization" id="specialization" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; font-size: 16px;">
            <option value="" disabled selected>Select Specialization</option>
            <option value="cardiology">Cardiology</option>
            <option value="General">General</option>
            <option value="Nerve">Nerve</option>
        </select>
        <select name="doctor" id="doctor" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; font-size: 16px;">
            <option value="" disabled selected>Select Doctor</option>
        </select>
        <input type="date" name="appointment_date" required>
        <input type="time" name="appointment_time" required>
        <button type="submit">Book</button>
      </form>
    </div>

    <div id="appointments" class="section">
      <h1>Your Appointments</h1>
      <?php
        $sql = "SELECT * FROM appointments WHERE patient_id='$pid'";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Patient Name</th>
          <th>Doctor Name</th>
          <th>Appointment Date</th>
          <th>Appointment Time</th>
          <th>Appointment Status</th>
          <th>Action</th>
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
        <input type="text" hidden value="">
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
        <td>
          <?php
            if($row["status"]==1)
            {
            ?>
              <button onclick="cancel(<?= $row['appointment_id'] ?>)">Cancel</button>
            <?php
            }
            else if($row["status"]==-1)
            {
            ?>
              <button style="color:green;">Prescribed</button>
            <?php
            }
            else{
            ?>
              <button style="color:black;">Cancelled</button>
            <?php
            }
          ?>
        </td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>

    <div id="prescriptions" class="section">
      <h1>Your Prescriptions</h1>
      <?php
        $sql = "SELECT * FROM prescription WHERE patient_id='$pid'";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Doctor Name</th>
          <th>Disease</th>
          <th>Prescription</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["doctor_name"] ?></td>
        <td><?php echo $row["disease"] ?></td>
        <td><?php echo $row["prescription"] ?></td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>
  </div>

  <script>
    function cancel(val) {
    fetch('cancel_appointment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'appointment_id=' + encodeURIComponent(val)
    })
    .then(response => response.text())
    .then(data => {
        alert(data); 
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

    function showSection(id) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.getElementById(id).classList.add('active');
    }

    document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("specialization").addEventListener("change", function() {
        var specialization = this.value;
        fetch("fetch_doctors.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "specialization=" + encodeURIComponent(specialization)
        })
        .then(response => response.text())
        .then(data => {
          document.getElementById("doctor").innerHTML = data;
        })
        .catch(error => {
          console.error("Error fetching doctors:", error);
        });
      });
    });
  </script>

</body>
</html>
