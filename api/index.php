<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}


header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: text/plain");

$mail = new PHPMailer(true);
//Server settings
$mail->isSMTP();
$mail->Host       = $_ENV["SMTP_HOST"];
$mail->SMTPAuth   = true;
$mail->Username   = $_ENV["SMTP_USERNAME"];
$mail->Password   = $_ENV["SMTP_PASSWORD"];
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port       = $_ENV["SMTP_PORT"];

function set_SMTP($from, $name, $subject, $template)
{
    global $mail;

    print_r($mail);
    $mail->setFrom($from, $name);
    $mail->addAddress($_ENV["SEND_ADDRESS"], 'Idance Studio Team');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $template;

    try {
        $mail->send();
        echo 'success';
    } catch (Exception $ex) {
        echo "Message could not be sent.";
    }
}

set_SMTP("from", "name", "subject", "template");

if (isset($_POST["execution"]) && $_POST["execution"] == "contact") {
    if (isset($_POST["name"]) && !empty($_POST["name"])) {
        if (isset($_POST["topic"]) && $_POST["topic"] != "0" && !empty($_POST["topic"])) {
            if (isset($_POST["email"]) && !empty($_POST["email"])) {
                //
                if (isset($_POST["subject"]) && !empty($_POST["subject"])) {
                    if (isset($_POST["message"]) && !empty($_POST["message"])) {
                        $name = $_POST["name"];
                        if ($_POST["topic"] == 1) {
                            $topic = "Anmeldung für Probestunde";
                        } else if ($_POST["topic"] == 2) {
                            $topic = "Fragen zu Kursen und Zeiten";
                        } else if ($_POST["topic"] == 3) {
                            $topic = "Anfrage für private Tanzstunden";
                        } else if ($_POST["topic"] == 4) {
                            $topic = "Feedback geben";
                        } else if ($_POST["topic"] == 5) {
                            $topic = "Anderes";
                        }
                        $email = $_POST["email"];
                        $subject = $_POST["subject"];
                        $message = $_POST["message"];
                        ob_start();
                        require_once "contact.php";
                        $template = ob_get_clean();
                        // set_SMTP($email, $name, "Contacting Idance Studio", $template);
                        $mail->setFrom($email, $name);
                        $mail->addAddress($_ENV["SEND_ADDRESS"], 'Idance Studio Team');
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body    = $template;
                        $mail->send();
                        echo 'success';
                    } else {
                        echo ("please add your message");
                    }
                } else {
                    echo ("please add a subject");
                }
                //
            } else {
                echo ("you must add your email to continue");
            }
        } else {
            echo ("please select a topic");
        }
    } else {
        echo ("you must add your name to continue");
    }
}


if (isset($_POST["execution"]) && $_POST["execution"] == "register") {
    if (isset($_POST["first_name"]) && !empty($_POST["first_name"])) {
        if (isset($_POST["last_name"]) && !empty($_POST["last_name"])) {
            if (isset($_POST["class_category"]) && !empty($_POST["class_category"]) && $_POST["class_category"] != "0") {
                if (isset($_POST["mobile_number"]) && !empty($_POST["mobile_number"])) {
                    if (isset($_POST["email"]) && !empty($_POST["mobile_number"])) {
                        $first_name = $_POST["first_name"];
                        $last_name = $_POST["last_name"];
                        $class_category = "";
                        if ($_POST["class_category"] == 1) {
                            $class_category = "Minis";
                        } else if ($_POST["class_category"] == 2) {
                            $class_category = "Kids";
                        } else if ($_POST["class_category"] == 3) {
                            $class_category = "Teens";
                        } else if ($_POST["class_category"] == 4) {
                            $class_category = "Custom";
                        }
                        $mobile_number = $_POST["mobile_number"];
                        $email = $_POST["email"];
                        $message = "";
                        if (isset($_POST["message"]) && !empty($_POST["message"])) {
                            $message = $_POST["message"];
                        } else {
                            $message = "Message is empty!";
                        }
                        ob_start();
                        require_once "register.php";
                        $template = ob_get_clean();
                        // set_SMTP($email, "$first_name $last_name", "Class Registration", $template);
                        $mail->setFrom($email, "$first_name $last_name");
                        $mail->addAddress($_ENV["SEND_ADDRESS"], 'Idance Studio Team');
                        $mail->isHTML(true);
                        $mail->Subject = "Class Registration";
                        $mail->Body    = $template;
                        $mail->send();
                        echo 'success';
                    } else {
                        echo ("please add a email to continue");
                    }
                } else {
                    echo ("please add a mobile number to continue");
                }
            } else {
                echo ("please add your first name to continue");
            }
        } else {
            echo ("please add your last name to continue");
        }
    } else {
        echo ("please add your first name to continue");
    }
}
