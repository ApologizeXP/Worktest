<?php
session_start();
include('server.php');
$errors = array();
$userid = $_SESSION['userid'];
$requestid = $_POST['requestid'];
if(isset($_POST['request_edit'])){
    if (isset($_FILES['file'])) {
        echo "<pre>";
        print_r($_FILES['file']);
        echo "</pre>";

        $file_name = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];
        $tmp_name = $_FILES['file']['tmp_name'];
        $error = $_FILES['file']['error'];


        
        if ($file_size > 1000000) {
            array_push($errors, "ไฟล์มีขนาดใหญ่เกิน");
            $_SESSION['error'] = "ไฟล์มีขนาดใหญ่เกิน";
        } else {
            $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_ex_lc = strtolower($file_ex);

            $allowd_exs = array("jpg", "jpeg", "png", "pdf","");
            if (in_array($file_ex_lc, $allowd_exs)) {
                $new_file_name = $file_name;
                $file_upload_path = '../files/' . $new_file_name;
                move_uploaded_file($tmp_name, $file_upload_path);
            } else {
                array_push($errors, "คุณไม่สามารถลงไฟล์นามสกุลนี้ได้");
                $_SESSION['error'] = "คุณไม่สามารถลงไฟล์นามสกุลนี้ได้";
            }
        }
    
    }

    $request = mysqli_real_escape_string($conn,$_POST['request']);
    $number1 = mysqli_real_escape_string($conn,$_POST['number1']);
    $number2 = mysqli_real_escape_string($conn,$_POST['number2']);
    $number3 = mysqli_real_escape_string($conn,$_POST['number3']);
    $number4 = mysqli_real_escape_string($conn,$_POST['number4']);
    $number5 = mysqli_real_escape_string($conn,$_POST['number5']);
    $amount1 = mysqli_real_escape_string($conn,$_POST['amount1']);
    $amount2 = mysqli_real_escape_string($conn,$_POST['amount2']);
    $amount3 = mysqli_real_escape_string($conn,$_POST['amount3']);
    $amount4 = mysqli_real_escape_string($conn,$_POST['amount4']);
    $amount5 = mysqli_real_escape_string($conn,$_POST['amount5']);
    

}
    if (empty($request)) {
        array_push($errors,"กรุณากรอกคำเสนอแนะ");
        $_SESSION['error'] = "กรุณากรอกคำเสนอแนะ";
    }
    if(count($errors)==0){
        if($file_size != 0){
            $sql ="UPDATE request SET request='$request',namefile='$new_file_name',minefile='$file_type',file='$new_file_name',number1='$number1',number2='$number2',number3='$number3',number4='$number4',number5='$number5',amount1='$amount1',amount2='$amount2',amount3='$amount3',amount4='$amount4',amount5='$amount5',time = CURRENT_TIMESTAMP,requeststatus='W'
            WHERE requestid = '$requestid'AND userid='$userid'";
            mysqli_query($conn,$sql);
        }else{
            $sql ="UPDATE request SET request='$request',number1='$number1',number2='$number2',number3='$number3',number4='$number4',number5='$number5',amount1='$amount1',amount2='$amount2',amount3='$amount3',amount4='$amount4',amount5='$amount5',time = CURRENT_TIMESTAMP,requeststatus='W'
            WHERE requestid = '$requestid'AND userid='$userid'";
            mysqli_query($conn,$sql);
        }
        header('location: show_request.php');
       
    }else{
        
        header('location: edit_request.php?id='.$requestid.'');
    }
