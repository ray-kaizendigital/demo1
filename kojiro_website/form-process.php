<?php
if (isset($_POST['email'])) {

    // Set your desired email address and subject line
    $email_to = "kojiromosman@gmail.com";
    $email_subject = "You've got a new submission";

    function problem($error)
    {
        echo "<div class='error-message'>Oh looks like there is some problem with your form data: <br><br>";
        echo $error . "<br><br>";
        echo "Please fix those to proceed.</div>";
        die();
    }

    // Ensure all expected fields exist
    if (
        !isset($_POST['firstname']) ||
        !isset($_POST['lastname']) ||
        !isset($_POST['email']) ||
        !isset($_POST['phone']) ||
        !isset($_POST['subject']) ||
        !isset($_POST['message'])
    ) {
        problem('Some required form data is missing.');
    }

    $firstname = $_POST['firstname']; // required
    $lastname = $_POST['lastname']; // required
    $email = $_POST['email']; // required
    $phone = $_POST['phone']; // required
    $subject = $_POST['subject']; // required
    $message = $_POST['message']; // required

    // Validate form data
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email)) {
        $error_message .= 'Email address does not seem valid.<br>';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $firstname) || !preg_match($string_exp, $lastname)) {
        $error_message .= 'First name or Last name does not seem valid.<br>';
    }

    if (strlen($phone) < 10 || !ctype_digit($phone)) {
        $error_message .= 'Phone number should be at least 10 digits and numeric.<br>';
    }

    if (strlen($message) < 2) {
        $error_message .= 'Message should not be less than 2 characters.<br>';
    }

    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    // Prepare email content
    $email_message = "Form details are as follows:\n\n";
    function clean_string($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }

    $email_message .= "First Name: " . clean_string($firstname) . "\n";
    $email_message .= "Last Name: " . clean_string($lastname) . "\n";
    $email_message .= "Email: " . clean_string($email) . "\n";
    $email_message .= "Phone: " . clean_string($phone) . "\n";
    $email_message .= "Subject: " . clean_string($subject) . "\n";
    $email_message .= "Message: " . clean_string($message) . "\n";

    // Create email headers
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $email_subject, $email_message, $headers);

    // Success message after form submission
    $success_message = "Thanks for contacting us, we will get back to you as soon as possible.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php if (!empty($success_message)) : ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form class="formbox" action="form-process.php" method="post" class="formcarry-form">
        <div class="namebox">
            <div class="first_name">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>

            <div class="last_name">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
        </div>

        <div class="contactbox">
            <div class="email">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="phone">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
        </div>

        <div class="subject">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>
        </div>

        <div class="message">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
        </div>

        <div class="submit">
            <button type="submit">Send</button>
        </div>
    </form>
</body>
</html>
