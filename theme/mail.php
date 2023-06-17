<?php
    echo "Running Mail Php!";
    
    // require './vendor/phpmailer/phpmailer/src/PHPMailer-master/src/Exception.php';
    // require './vendor/phpmailer/phpmailer/src/PHPMailer-master/src/PHPMailer.php';
    // require './vendor/phpmailer/phpmailer/src/PHPMailer-master/src/SMTP.php';

    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    // require 'vendor/autoload.php';

    $files_path = './uploaded_files/';
    $file = $_FILES['file'];
    $userPage = $_POST["user_page"];

    function prepareMailServer () {
        $mail = new PHPMailer;
        $mail->IsSMTP();                                     
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = 'tkarmokar32@gmail.com';
        $mail->Password = 'sohvqjqfilplcwkd';
        $mail->SMTPSecure = 'ssl';
        $mail->Priority = 1;
        $mail->AddCustomHeader("X-MSMail-Priority: High");
        $mail->WordWrap = 50;    
        $mail->IsHTML(true);
        $mail->From = 'tkarmokar32@gmail.com';
        $mail->FromName = "Tanmoy";
        return $mail;
    }

    function sendEmailAndRedirectUser ($mailServer, $mailSubject, $mailBody, $receiverEmailId, $userPage, $file, $files_path) {
        $mailServer->AddAddress($receiverEmailId, 'TAN');
        $mailServer->Subject = $mailSubject;
        $mailServer->Body = $mailBody;

        if($file != null) {
            $file_path = $files_path.$file['name'];
            // echo '<p>file_path: '.$file_path.'</p>';
            $mailServer->addAttachment($file_path);
        }

        if(!$mailServer->send()) {
            // echo "Error while sending Email.";
            var_dump($mailServer);
        }
        else {
            // echo "\nEmail sent successfully";
            if ($userPage == "contact") {
                // header("Location: /theme/contact.html?form_success=1");
                echo '<meta http-equiv="refresh" content="0; URL=/theme/contact.html?form_success=1">';
            }
            else {
                // header("Location: /theme/service.html?form_success=1");
                echo '<meta http-equiv="refresh" content="0; URL=/theme/service.html?form_success=1">';
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if ($_POST["user_page"] == "contact") {
            $user_name = $_POST["user_name"];
            $user_email = $_POST["user_email"];
            $user_subject = $_POST["user_subject"];
            $mailBody = '<div>'.$_POST["user_message"].'</div>';

            // echo '<p>user_name: '.$user_name.'</p>';
            // echo '<p>user_email: '.$user_email.'</p>';
            // echo '<p>user_subject: '.$user_subject.'</p>';

            if ($user_name == null || $user_email == null || $user_subject == null) {
                // echo 'form is incomplete';
                // header("Location: http://127.0.0.1:81/theme/contact.html?form_alert=1");
                echo '<meta http-equiv="refresh" content="0; URL=/theme/contact.html?form_alert=1">';
            }
        }
        else {
            var_dump($_POST);
            $user_name = $_POST["user_name"];
            $user_email = $_POST["user_email"];
            $user_subject = "Need Service Support";
            $company_name = $_POST["user_company_name"];
            $user_product_model = $_POST["user_product_model"];
            $user_product_serial_number = $_POST["user_product_serial_number"];
            $user_product_requirement = $_POST["user_product_requirement"];
            $mailBody = '<div><p>I have a query regarding a product with the details below:</p><p>Product Model: '.$user_product_model.'</p><p>Product Requirement: '.$user_product_requirement.'</p></div>';

            // echo '<p>user_name: '.$user_name.'</p>';
            // echo '<p>user_email: '.$user_email.'</p>';
            // echo '<p>user_subject: '.$user_subject.'</p>';
            // echo '<p>company_name: '.$company_name.'</p>';
            // echo '<p>user_product_model: '.$user_product_model.'</p>';
            // echo '<p>user_product_serial_number: '.$user_product_serial_number.'</p>';
            // echo '<p>user_product_requirement: '.$user_product_requirement.'</p>';
            // echo '</br>';
            // echo '<p>mailBody: '.$mailBody.'</p>';

            if ($user_name == null || $user_email == null || $company_name == null 
                || $user_product_model == null || $user_product_serial_number == null || 
                $user_product_requirement == null) {
                // echo 'form is incomplete';
                // header("Location: http://127.0.0.1:81/theme/service.html?form_alert=1");
                echo '<meta http-equiv="refresh" content="0; URL=/theme/service.html?form_alert=1">';
            }
            else {
                if(isset($file['name']) && !empty($file['name'])) {
                    $file_path = $files_path.$file['name'];
                    // echo $file_path;
                    file_put_contents($file_path, $file);
                }
                else {
                    // header("Location: http://127.0.0.1:81/theme/service.html?file_alert=1");
                    echo '<meta http-equiv="refresh" content="0; URL=/theme/service.html?file_alert=1">';
                }
            }
        }
        // exit;

        $subject = $user_subject;
        $mailServer = prepareMailServer();
        $receiverEmailId = 'tkarmokar32@gmail.com';
        // var_dump($mailServer);
        sendEmailAndRedirectUser(
            $mailServer, $subject, $mailBody, 
            $receiverEmailId, $userPage, $file,
            $files_path
        );
    }
?>
