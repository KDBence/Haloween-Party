// ====== ÁLLÍTSD BE: a Firebase Realtime Database URL-je (a végén NE legyen perjel) ======
const DB_URL = 'https://haloween-party-default-rtdb.europe-west1.firebasedatabase.app';
// =======================================================================================

const FELADAT1_KEYS = ['szo1', 'szo2', 'szo3', 'szo4', 'szo5', 'szo6'];

function getName() {
  return sessionStorage.getItem('nev') || '';
}

function requireLogin() {
  const nev = getName();
  if (!nev) location.href = 'index.html';
  return nev;
}

async function dbGet(path) {
  const r = await fetch(`${DB_URL}/${path}.json`);
  if (!r.ok) throw new Error('Adatbázis hiba (olvasás): ' + r.status);
  return r.json(); // üres esetén null
}

async function dbPut(path, data) {
  const r = await fetch(`${DB_URL}/${path}.json`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (!r.ok) throw new Error('Adatbázis hiba (írás): ' + r.status);
}

// A Firebase kulcsokban nem lehet . $ # [ ] /
function nameKey(name) {
  return encodeURIComponent(name).replace(/\./g, '%2E');
}

async function feladat1LoadWords() {
  const saved = (await dbGet('feladat1/words')) || {};
  const words = {};
  FELADAT1_KEYS.forEach(k => {
    words[k] = typeof saved[k] === 'string' ? saved[k] : '';
  });
  return words;
}

async function feladat1SaveWords(words) {
  await dbPut('feladat1/words', words);
}

async function feladat1LoadAllAnswers() {
  return (await dbGet('feladat1/answers')) || {};
}

async function feladat1LoadPlayerAnswers(name) {
  const entry = await dbGet('feladat1/answers/' + nameKey(name));
  return entry && entry.answers ? entry.answers : null;
}

// Egyszer menthető: ha már van válasz, false-t ad vissza
async function feladat1SavePlayerAnswersOnce(name, answers) {
  if (await feladat1LoadPlayerAnswers(name)) return false;
  await dbPut('feladat1/answers/' + nameKey(name), { name, answers });
  return true;
}
