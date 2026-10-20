<?php
    session_start();
    $nev = $_SESSION['nev'];
    require_once __DIR__ . '/feladat1_words.php';

    $szavak = feladat1LoadWords();
    $saved = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach (array_keys($szavak) as $key) {
            $szavak[$key] = isset($_POST[$key]) && is_string($_POST[$key]) ? $_POST[$key] : '';
        }
        feladat1SaveWords($szavak);
        $saved = true;
    }
?>
<link rel="stylesheet" href="style.css">
<title><?php echo $nev; ?></title>
<h1>Admin oldal</h1>
<?php if ($saved): ?>
    <p>Mentés sikeres.</p>
    <script>
        location.href = 'feladatselect.php';
    </script>
<?php endif; ?>
<form action="" method="post">
    <input type="text" name="szo1" id="szo1" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($szavak['szo1'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    <input type="text" name="szo2" id="szo2" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($szavak['szo2'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    <input type="text" name="szo3" id="szo3" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($szavak['szo3'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    <input type="text" name="szo4" id="szo4" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($szavak['szo4'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    <input type="text" name="szo5" id="szo5" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($szavak['szo5'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    <input type="text" name="szo6" id="szo6" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($szavak['szo6'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
    <button type="submit">Mentés</button><br>
</form>