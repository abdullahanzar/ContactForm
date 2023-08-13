<?php include 'config/database.php'; ?>

<?php
$name = $phone = $email = $subject = $message = '';
$nameErr = $phoneErr = $emailErr = $subjectErr = $messageErr = '';

$confirmation = "";
$isMailSuccess = false;
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

function sendMail($name, $phone, $email)
{
    $to = "anzar.sapiens@gmail.com";
    $subject = "Email Notification";
    $message = "$name user has successfully submitted the contact form with the email address $email and phone number $phone.";
    $headers = "From: abdullahanzar@gmail.com";
    try {
        $sendMail = mail($to, $subject, $message, $headers);
        if ($sendMail) {
            global $isMailSuccess;
            $isMailSuccess = true;
        }
    } catch
    (Exception $err) {
        echo "There was some problem with generating mail.$err";
    }
}


function postToDatabase($name, $phone, $email, $subject, $message)
{
    global $conn;
    $clientIP = $_SERVER['REMOTE_ADDR'];
    $sql = "INSERT INTO contact_form (id, name, phone, email, subject, message, ip_address, timestamp) VALUES 
        (null, '$name', '$phone', '$email', '$subject', '$message', '$clientIP', CURRENT_TIMESTAMP);";
    try {
        $query = "SELECT * FROM contact_form WHERE email='$email'";
        $doesEmailExist = $conn->query($query);
        if ($doesEmailExist->num_rows >= 1) {
            global $confirmation;
            $confirmation = "A user already exists with the given email in the Database. Please try with a different email.";
        } else {
            try {
                if (mysqli_query($conn, $sql)) {
                    global $confirmation;
                    $confirmation = "Form submitted to database successfully.";
                    sendMail($name, $phone, $email);
                } else {
                    global $confirmation;
                    $confirmation = "There was some error with the database.";
                }
            } catch (Exception $e) {
                global $confirmation;
                $confirmation = "There was some error with the database.";
            }
        }
    } catch (Exception $e) {
        error_log("There was some problem with the database $e");
    }
}

//Validation on the Backend Through PHP
if (isset($_POST['submit'])) {
    if (empty($_POST['name']))
        $nameErr = "Please enter a name.";
    else
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($_POST['email']))
        $emailErr = "Please enter an email.";
    else
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (empty($_POST['phone']))
        $phoneErr = "Please enter a phone number.";
    else
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($_POST['subject']))
        $subjectErr = "Please enter a subject.";
    else
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($_POST['message']))
        $messageErr = "Please enter a message.";
    else
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($emailErr == "") {
        if (strpos($email, "@") === false)
            $emailErr = "Please include @gmail etc. in the email.";
        if (strpos($email, ".") === false)
            $emailErr = "Please include .com etc. in the email.";
    }
    if ($phoneErr == "") {
        if (strlen($phone) > 20)
            $phoneErr = "Phone number cannot be of more than 20 digits.";
        if (strlen($phone) < 10)
            $phoneErr = "India requires a phone number of 10 digits.";
        if (preg_match('/[a-zA-Z]/', $phone))
            $phoneErr = "You cannot have a letter in phone number.";
        if (preg_match('/[^\w\s]/', $phone))
            $phoneErr = "You cannot have a symbol in phone number.";

        if (empty($nameErr) && empty($phoneErr) && empty($emailErr) && empty($subjectErr) && empty($messageErr)) {
            postToDatabase($name, $phone, $email, $subject, $message);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Contact-Form</title>
</head>

<body>
    <div class="contact-form">
        <div class="details">
            <h4>Contact Form</h4>
            <p>Please fill the form.</p>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div>
                <label for="name">Full Name: </label>
                <input class="<?php echo $nameErr ? "err_input" : null ?>" type="text" name="name" maxlength="255"
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                    placeholder="Abdullah Anzar" />
            </div>
            <div class="error">
                <?php echo $nameErr ?>
            </div>
            <div>
                <label for="phone">Phone Number: </label>
                <input class="<?php echo $phoneErr ? "err_input" : null ?>" type="tel" name="phone" maxlength="20"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                    placeholder="8318585296" />
            </div>
            <div class="error">
                <?php echo $phoneErr ?>
            </div>
            <div>
                <label for="email">Email: </label>
                <input class="<?php echo $emailErr ? "err_input" : null ?>" type="text" name="email" maxlength="255"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    placeholder="abdullahanzar789@gmail.com" />
            </div>
            <div class="error">
                <?php echo $emailErr ?>
            </div>
            <div>
                <label for="subject">Subject: </label>
                <input class="<?php echo $subjectErr ? "err_input" : null ?>" type="text" name="subject" maxlength="255"
                    value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                    placeholder="Feedback" />
            </div>
            <div class="error">
                <?php echo $subjectErr ?>
            </div>
            <div>
                <label for="message">Message: </label>
                <textarea class="<?php echo $messageErr ? "err_input" : null ?>" name="message" id="message"
                    placeholder="There is always some room for improvement."><?php echo isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : ''; ?></textarea>
            </div>
            <div class="error">
                <?php echo $messageErr ?>
            </div>
            <div class="confirmation <?php echo strpos($confirmation, "already exists") ||
                strpos($confirmation, "error") ? htmlspecialchars("confirmation_error") : null ?>">
                <?php echo $confirmation ?>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>

</html>