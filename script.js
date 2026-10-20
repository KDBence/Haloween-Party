// Közös segédfüggvények (a PHP-s feladat1_words.php helyett)
// FIGYELEM: az adatok a böngésző localStorage-ában vannak, nem osztottak a felhasználók között.
const FELADAT1_KEYS = ['szo1', 'szo2', 'szo3', 'szo4', 'szo5', 'szo6'];

function getName() {
  return sessionStorage.getItem('nev') || '';
}

function requireLogin() {
  const nev = getName();
  if (!nev) location.href = 'index.html';
  return nev;
}

function loadJSON(key, fallback) {
  try {
    const v = JSON.parse(localStorage.getItem(key));
    return v === null || typeof v !== 'object' ? fallback : v;
  } catch (e) {
    return fallback;
  }
}

function feladat1LoadWords() {
  const saved = loadJSON('feladat1_words', {});
  const words = {};
  FELADAT1_KEYS.forEach(k => {
    words[k] = typeof saved[k] === 'string' ? saved[k] : '';
  });
  return words;
}

function feladat1SaveWords(words) {
  localStorage.setItem('feladat1_words', JSON.stringify(words));
}

function feladat1LoadAllAnswers() {
  return loadJSON('feladat1_player_answers', {});
}

function feladat1LoadPlayerAnswers(name) {
  const all = feladat1LoadAllAnswers();
  const key = 'p:' + name;
  return Object.prototype.hasOwnProperty.call(all, key) ? all[key] : null;
}

// Egyszer menthető: ha már van válasz, false-t ad vissza
function feladat1SavePlayerAnswersOnce(name, answers) {
  const all = feladat1LoadAllAnswers();
  const key = 'p:' + name;
  if (Object.prototype.hasOwnProperty.call(all, key)) return false;
  all[key] = answers;
  localStorage.setItem('feladat1_player_answers', JSON.stringify(all));
  return true;
}
