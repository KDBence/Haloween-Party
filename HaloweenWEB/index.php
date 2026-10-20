<?php
    session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nev = $_POST['nev'];
    $_SESSION['nev'] = $nev;

    if ($_POST['nev'] === 'admin') {
        header('Location: admin.php');
        exit();
    } else {
        header('Location: feladatselect.php');
        exit();
    }   
}
    
?>
<form method="post" action="">
    <link rel="stylesheet" href="style.css">
    <title>Haloween party bejelentkezés</title>
    <H1>Haloween party bejelentkezés</H1><br>
    <label for="nev">Név:</label>
    <input type="text" id="nev" name="nev" required>
    <button type="submit">Bejelentkezés</button><br>
</form>