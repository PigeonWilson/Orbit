<?php
require_once ('orbitLoader.php');
// flush session
if (isset($_REQUEST['flush']) && $_REQUEST['flush'] == 'true')
{
    $_->session->flush();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erreur</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="css/error.css" rel="stylesheet">
</head>
<body>

<div class="error-container">
    <h1>Error: <?php if (isset($_REQUEST['reason'])) $_->toolset->echo($_REQUEST['reason']); ?></h1>
</div>

</body>
</html>
