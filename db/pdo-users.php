<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$id_feld = 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE users (' . $id_feld .
    ' username VARCHAR(255),' .
    ' email VARCHAR(255),' .
    ' password VARCHAR(255),' .
    ' profile_picture VARCHAR(500),' .
    ' description TEXT,' .
    ' created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP );';
$db->exec($sql);
echo 'Users Tabelle angelegt.<br/>';

$sql = "INSERT INTO users (username, email, password, description) VALUES ('maxmuster', 'max@example.com', 'geheim123', 'Ich bin Max Mustermann');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "User mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des ersten Users.<br/>';
}

$sql = "INSERT INTO users (username, email, password, description) VALUES ('anna_test', 'anna@test.com', 'passwort456', 'Anna aus Hamburg');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "User mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des zweiten Users.<br/>';
}

echo 'Users Tabelle fertig';
?>

