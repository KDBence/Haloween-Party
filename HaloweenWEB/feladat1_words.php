<?php
function feladat1LoadWords()
{
    $defaults = array_fill_keys(['szo1', 'szo2', 'szo3', 'szo4', 'szo5', 'szo6'], '');
    $path = __DIR__ . '/feladat1_words.json';

    if (!is_file($path)) {
        return $defaults;
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Could not read the saved task words.');
    }

    $savedWords = json_decode($contents, true);
    if (!is_array($savedWords)) {
        throw new RuntimeException('The saved task words file contains invalid JSON.');
    }

    foreach ($defaults as $key => $value) {
        if (isset($savedWords[$key]) && is_string($savedWords[$key])) {
            $defaults[$key] = $savedWords[$key];
        }
    }

    return $defaults;
}

function feladat1SaveWords(array $words)
{
    $contents = json_encode($words, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($contents === false) {
        throw new RuntimeException('Could not encode the task words as JSON.');
    }

    $path = __DIR__ . '/feladat1_words.json';
    if (file_put_contents($path, $contents, LOCK_EX) === false) {
        throw new RuntimeException('Could not save the task words.');
    }
}

function feladat1OpenPlayerAnswersFile()
{
    $path = __DIR__ . '/data/feladat1_player_answers.json';
    $file = fopen($path, 'c+');

    if ($file === false) {
        throw new RuntimeException('Could not open the player answers file.');
    }

    return $file;
}

function feladat1ReadPlayerAnswersFile($file)
{
    rewind($file);
    $contents = stream_get_contents($file);

    if ($contents === false) {
        throw new RuntimeException('Could not read the player answers file.');
    }

    if ($contents === '') {
        return [];
    }

    $answers = json_decode($contents, true);
    if (!is_array($answers)) {
        throw new RuntimeException('The player answers file contains invalid JSON.');
    }

    return $answers;
}

function feladat1LoadPlayerAnswers($name)
{
    $file = feladat1OpenPlayerAnswersFile();
    if (!flock($file, LOCK_SH)) {
        fclose($file);
        throw new RuntimeException('Could not lock the player answers file.');
    }

    try {
        $allAnswers = feladat1ReadPlayerAnswersFile($file);
        return isset($allAnswers[$name]) && is_array($allAnswers[$name])
            ? $allAnswers[$name]
            : null;
    } finally {
        flock($file, LOCK_UN);
        fclose($file);
    }
}

function feladat1SavePlayerAnswersOnce($name, array $answers)
{
    $file = feladat1OpenPlayerAnswersFile();
    if (!flock($file, LOCK_EX)) {
        fclose($file);
        throw new RuntimeException('Could not lock the player answers file.');
    }

    try {
        $allAnswers = feladat1ReadPlayerAnswersFile($file);
        if (isset($allAnswers[$name])) {
            return false;
        }

        $allAnswers[$name] = $answers;
        $contents = json_encode($allAnswers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($contents === false) {
            throw new RuntimeException('Could not encode the player answers as JSON.');
        }

        rewind($file);
        if (!ftruncate($file, 0)) {
            throw new RuntimeException('Could not prepare the player answers file for saving.');
        }

        $written = fwrite($file, $contents);
        if ($written === false || $written !== strlen($contents) || !fflush($file)) {
            throw new RuntimeException('Could not save the player answers.');
        }

        return true;
    } finally {
        flock($file, LOCK_UN);
        fclose($file);
    }
}
