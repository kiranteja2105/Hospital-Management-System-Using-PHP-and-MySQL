<?php
require "connection.php";
session_start();
$did=$_SESSION["did"];
$dname=$_SESSION["dname"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard</title>
  <link rel="stylesheet" href="../assest/CSS/doctor.css">
  <style>
    .cards-container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .prescription-form {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .prescription-form label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }

    .prescription-form input,
    .prescription-form textarea {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .prescription-form button {
      width: 100%;
      padding: 10px;
      background-color: #7a90e3;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .prescription-form button:hover {
      background-color: #5c6fd1;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>K Hospitals Doctor Panel</h2>
    <ul>
      <li onclick="showSection('dashboard')">Dashboard</li>
      <li onclick="showSection('appointments')">Display Appointments</li>
      <li onclick="showSection('prescriptions')">Display Prescriptions</li>
    </ul>
  </div>

  <div class="main-content">
    <div id="dashboard" class="section active">
    <h1>Welcome Dr.<?php echo "".$dname.""?></h1>
      <div class="cards-container">
        <div class="top-card">
          <div class="card" onclick="showSection('appointments')">
            <h2>Display Appointments</h2>
          </div>
          <div class="card" onclick="showSection('prescriptions')">
            <h2>Prescriptions</h2>
            <p>Click to view prescriptions</p>
          </div>
        </div>
      </div>
    </div>

    <div id="appointments" class="section">
      <h2>Appointments</h2>
      <?php
        $sql = "SELECT * FROM appointments WHERE doctor_id='$did'";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Patient Name</th>
          <th>Appointment Date</th>
          <th>Appointment Time</th>
          <th>Appointment Status</th>
          <th>Prescribe</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["patient_name"] ?></td>
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
              <button onclick="showPrescribeForm('<?php echo $row['patient_id']; ?>', '<?php echo htmlspecialchars($row['patient_name'], ENT_QUOTES); ?>', '<?php echo $row['appointment_id']; ?>')">Prescribe</button>
            <?php
            }
            else if($row["status"]==-1)
            {
              echo "Prescribed";
            }
            else{
            ?>
              <button>-</button>
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
      <h2>Prescriptions</h2>
      <?php
        $sql = "SELECT * FROM prescription WHERE doctor_id='$did'";
        $result = $conn->query($sql);
      ?>
      <table>
        <tr>
          <th>Patient Name</th>
          <th>Disease</th>
          <th>Prescription</th>
        </tr>
      <?php
        while($row=$result->fetch_assoc())
        {
      ?>
      <tr>
        <td><?php echo $row["patient_name"] ?></td>
        <td><?php echo $row["disease"] ?></td>
        <td><?php echo $row["prescription"] ?></td>
      </tr>
      <?php
        }
      ?>
      </table>
    </div>

    
    <div id="prescribeFormSection" class="section">
      <h2>Prescribe for <span id="patientNameDisplay"></span></h2>
      <div class="cards-container">
        <form action="save_prescription.php" method="post" class="prescription-form">
          <input type="hidden" name="patient_id" id="patientIdInput" />
          <input type="hidden" name="patient_name" id="patientNameInput" />
          <input type="hidden" name="doctor_id" value="<?php echo $did; ?>" />
          <input type="hidden" name="doctor_name" value="<?php echo $dname; ?>" />
          <input type="hidden" name="appointment_id" id="appointmentIdInput" />
          
          <label for="disease">Disease:</label><br>
          <input type="text" name="disease" id="disease" required><br><br>
          
          <label for="prescription">Prescription:</label><br>
          <textarea name="prescription" id="prescription" rows="4" required></textarea><br><br>
          
          <button type="submit">Submit</button>
        </form>
      </div>
  </div>
  </div>

  <script>
    function showSection(id) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.getElementById(id).classList.add('active');
    }

    function showPrescribeForm(patientId, patientName, appointmentId) {
      document.getElementById('patientIdInput').value = patientId;
      document.getElementById('patientNameInput').value = patientName;
      document.getElementById('appointmentIdInput').value = appointmentId;
      document.getElementById('patientNameDisplay').innerText = patientName;
      showSection('prescribeFormSection');
    }


  </script>

</body>
</html>
