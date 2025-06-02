<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$id_feld = 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE rateable (' . $id_feld .
    ' user_id INTEGER,' .
    ' created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,' .
    ' FOREIGN KEY (user_id) REFERENCES users(id) );';
$db->exec($sql);
echo 'Rateable Tabelle angelegt.<br/>';

$sql = "INSERT INTO rateable (user_id) VALUES (1);";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Rateable mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des ersten Rateable.<br/>';
}

$sql = "INSERT INTO rateable (user_id) VALUES (2);";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Rateable mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des zweiten Rateable.<br/>';
}

echo 'Rateable Tabelle fertig';
?>
