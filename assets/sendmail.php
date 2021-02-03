 <?php
  require 'assets/PHPMailer.php';
  require 'assets/SMTP.php';
  require 'assets/Exception.php';


  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  // Build POST request to get the reCAPTCHA v3 score from Google
  $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
  $recaptcha_secret = ''; // Insert your secret key here
  $recaptcha_response = $_POST['recaptcha_response'];

  // Make the POST request
  $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);

  if (isset($_POST['recaptcha_response'])) {
    $name  =  $_REQUEST['name'];
    $email  =  $_REQUEST['email'];
    $subject =  $_REQUEST['subject'];
    $message     =  $_REQUEST['message'];
    $msg = "from: " . $email . " - " . $name . "<br><p>" . $message;

    $recaptcha = json_decode($recaptcha);


    // Take action based on the score returned
    if ($recaptcha->success == true && $recaptcha->score >= 0.5 && $recaptcha->action == 'contact') {
      $mail = new PHPMailer(true);
      try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = '';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = '';                     // SMTP username
        $mail->Password   = '';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('', 'Contact Inquiry');
        $mail->addAddress('', 'Recipient');     // Add a recipient



        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->CharSet = 'utf-8';
        $mail->Subject = $subject;
        $mail->Body    = $msg;
        $mail->AltBody = $msg;
        $mail->send();
        echo '1';
      } catch (Exception $e) {
        echo '2';
      }
    } else {
      // Score less than 0.5 indicates suspicious activity. Return an error
      $error_output = "Something went wrong. Please try again later";
    }
  }
