<?php
session_start();
if(isset($_POST['pass_but']) && isset($_SESSION['userId'])) {
    require '../helpers/init_conn_db.php';

    $mobile_flag = false;
    $flight_id = $_POST['flight_id'];
    $passengers = $_POST['passengers'];

    // Validate Mobile Numbers
    foreach ($_POST['mobile'] as $mobile) {
        if (strlen($mobile) !== 11) {
            $mobile_flag = true;
            break;
        }
    }

    if ($mobile_flag) {
        header('Location: ../pass_form.php?error=moblen');
        exit();
    }

    // Validate Date Format and Future Dates
    foreach ($_POST['date'] as &$dob) { 
        // Convert MM/DD/YYYY to YYYY-MM-DD for MySQL
        $dob = date("Y-m-d", strtotime($dob));

        if (strtotime($dob) > time()) { // Check if DOB is in the future
            header('Location: ../pass_form.php?error=invdate');
            exit();
        }
    }

    // Check if passenger already exists
    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT * FROM Passenger_profile WHERE flight_id = ? AND user_id = ?';
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../pass_form.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'ii', $flight_id, $_SESSION['userId']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $pass_id = null;

        while ($row = mysqli_fetch_assoc($result)) {
            $pass_id = $row['passenger_id'];
        }
    }

    if (is_null($pass_id)) {
        $pass_id = 0;
        $sql = 'ALTER TABLE Passenger_profile AUTO_INCREMENT = 1';
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location: ../pass_form.php?error=sqlerror');
            exit();
        } else {
            mysqli_stmt_execute($stmt);
        }
    }

    // Insert Passenger Data
    $stmt = mysqli_stmt_init($conn);
    foreach ($_POST['date'] as $index => $dob) {
        $sql = 'INSERT INTO Passenger_profile (user_id, mobile, dob, f_name, m_name, l_name, flight_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location: ../pass_form.php?error=sqlerror');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'iissssi', $_SESSION['userId'],
                $_POST['mobile'][$index], $dob, $_POST['firstname'][$index],
                $_POST['midname'][$index], $_POST['lastname'][$index], $flight_id);
            mysqli_stmt_execute($stmt);
        }
    }

    // Store session variables and redirect
    $_SESSION['flight_id'] = $flight_id;
    $_SESSION['class'] = $_POST['class'];
    $_SESSION['passengers'] = $passengers;
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['ret_date'] = $_POST['ret_date'];
    $_SESSION['pass_id'] = $pass_id + 1;

    header('Location: ../payment.php');
    exit();
} else {
    header('Location: ../pass_form.php');
    exit();
}
?>
