<?php
    session_start();
    $nev = $_SESSION['nev'];
?>
<link rel="stylesheet" href="style.css">
<title><?php echo $nev; ?></title>
<h1>Feladat kiválasztása</h1>
<h2>Neved: <?php echo $nev; ?></h2>
<div class="feladatok">
    <div class="feladat" onclick="location.href='adminfeladat1.php'">Szó nem kitalálás</div> <!-- Feladat 1 -->
    <div class="feladat" onclick="location.href='adminfeladat2.php'">Feladat 2</div> <!-- Feladat 2 -->
    <div class="feladat" onclick="location.href='adminfeladat3.php'">Feladat 3</div> <!-- Feladat 3 -->
    <div class="feladat" onclick="location.href='adminfeladat4.php'">Feladat 4</div> <!-- Feladat 4 -->
    <div class="feladat" onclick="location.href='adminfeladat5.php'">Feladat 5</div> <!-- Feladat 5 -->
    <div class="feladat" onclick="location.href='adminfeladat6.php'">Feladat 6</div> <!-- Feladat 6 -->
</div>