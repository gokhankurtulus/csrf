<?php
/**
 * @author Gökhan Kurtuluş @gokhankurtulus
 * Date: 18.06.2023 Time: 09:07
 */

use Csrf\Csrf;

require_once '../vendor/autoload.php';
session_start();

// Create a new token and generate the form
$tokenName = 'csrf_token';
$formToken = Csrf::createInput($tokenName);

echo '<pre>';
var_dump(['session' => $_SESSION, 'post' => $_POST]);
echo '</pre>';

// Verify the token when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sentToken = $_POST[$tokenName] ?? null;
    $isValid = Csrf::verify($tokenName, true, $sentToken);

    if ($isValid) {
        echo 'Token verified!';
    } else {
        echo 'Token verification failed!';
    }
}
echo '<pre>';
var_dump(['session' => $_SESSION, 'post' => $_POST]);
echo '</pre>';
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSRF Example</title>
</head>
<body>
<form method="POST" action="">
    <?php echo $formToken; ?>
    <input type="submit" value="Submit">
</form>
</body>
</html>