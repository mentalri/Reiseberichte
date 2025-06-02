<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$id_feld = 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE comments (' . $id_feld .
    ' rateable_id INTEGER,' .
    ' owner_id INTEGER,' .
    ' text VARCHAR(1000),' .
    ' FOREIGN KEY (rateable_id) REFERENCES rateable(id),' .
    ' FOREIGN KEY (owner_id) REFERENCES users(id) );';
$db->exec($sql);
echo 'Comments Tabelle angelegt.<br/>';

$sql = "INSERT INTO comments (rateable_id, owner_id, text) VALUES (1, 1, 'Das ist ein super Kommentar!');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Comment mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des ersten Comments.<br/>';
}

$sql = "INSERT INTO comments (rateable_id, owner_id, text) VALUES (2, 2, 'Hier ist noch ein Testkommentar.');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Comment mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des zweiten Comments.<br/>';
}

echo 'Comments Tabelle fertig';
?>
