<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$id_feld = 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE report (' . $id_feld .
    ' rateable_id INTEGER,' .
    ' title VARCHAR(255),' .
    ' location VARCHAR(255),' .
    ' description TEXT,' .
    ' FOREIGN KEY (rateable_id) REFERENCES rateable(id) );';
$db->exec($sql);
echo 'Report Tabelle angelegt.<br/>';

$sql = "INSERT INTO report (rateable_id, title, location, description) VALUES (1, 'Schöner Park', 'Hamburg Stadtpark', 'Ein wunderschöner Park zum Spazieren');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Report mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des ersten Reports.<br/>';
}

$sql = "INSERT INTO report (rateable_id, title, location, description) VALUES (2, 'Tolles Restaurant', 'Berlin Mitte', 'Excellentes Essen und Service');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Report mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des zweiten Reports.<br/>';
}

echo 'Report Tabelle fertig';
?>
