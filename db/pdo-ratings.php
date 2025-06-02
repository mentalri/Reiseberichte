<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$id_feld = 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE ratings (' . $id_feld .
    ' owner_id INTEGER,' .
    ' rating INTEGER,' .
    ' FOREIGN KEY (owner_id) REFERENCES users(id) );';
$db->exec($sql);
echo 'Ratings Tabelle angelegt.<br/>';

$sql = "INSERT INTO ratings (owner_id, rating) VALUES (1, 5);";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Rating mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des ersten Ratings.<br/>';
}

$sql = "INSERT INTO ratings (owner_id, rating) VALUES (2, 4);";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Rating mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des zweiten Ratings.<br/>';
}

echo 'Ratings Tabelle fertig';
?>
