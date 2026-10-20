<?php
    session_start();
    $nev = isset($_SESSION['nev']) && is_string($_SESSION['nev']) ? $_SESSION['nev'] : '';
    if ($nev === '') {
        header('Location: index.php');
        exit();
    }

    require_once __DIR__ . '/feladat1_words.php';

    $szavak = feladat1LoadWords();
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $answers = [];
        foreach (array_keys($szavak) as $key) {
            $answer = isset($_POST[$key]) && is_string($_POST[$key]) ? $_POST[$key] : '';
            if (trim($answer) === '') {
                http_response_code(400);
                exit('Please fill in all answers before submitting.');
            }
            $answers[$key] = $answer;
        }

        if (feladat1SavePlayerAnswersOnce($nev, $answers)) {
            $_SESSION['feladat1_message'] = 'Válaszaid sikeresen mentve.';
        } else {
            $_SESSION['feladat1_message'] = 'Már beküldted a válaszaidat, ezek nem módosíthatók.';
        }

        header('Location: feladat1.php');
        exit();
    }

    $playerAnswers = feladat1LoadPlayerAnswers($nev);
    if (isset($_SESSION['feladat1_message'])) {
        $message = $_SESSION['feladat1_message'];
        unset($_SESSION['feladat1_message']);
    }
?>
<link rel="stylesheet" href="style.css">
<title><?php echo htmlspecialchars($nev, ENT_QUOTES, 'UTF-8'); ?></title>
<form action="" method="post" class="word-form">
    <?php if ($message !== ''): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <?php foreach ($szavak as $key => $word): ?>
        <div class="word-row">
            <label for="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($word, ENT_QUOTES, 'UTF-8'); ?>:</label>
            <input type="text" name="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" id="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Írd be a szót" value="<?php echo htmlspecialchars($playerAnswers[$key] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required<?php echo $playerAnswers !== null ? ' readonly' : ''; ?>>
        </div>
    <?php endforeach; ?>
    <?php if ($playerAnswers === null): ?>
        <button type="submit">Beküldés</button><br>
    <?php endif; ?>
</form>