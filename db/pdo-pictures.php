<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$id_feld = 'id INTEGER PRIMARY KEY AUTOINCREMENT,';
$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE pictures (' . $id_feld .
    ' report_id INTEGER,' .
    ' path VARCHAR(500),' .
    ' FOREIGN KEY (report_id) REFERENCES report(id) );';
$db->exec($sql);
echo 'Pictures Tabelle angelegt.<br/>';

$sql = "INSERT INTO pictures (report_id, path) VALUES (1, '');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Picture mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des ersten Pictures.<br/>';
}

$sql = "INSERT INTO pictures (report_id, path) VALUES (2, '');";
if ($db->exec($sql)) {
    $id = $db->lastInsertId();
    echo "Picture mit der ID $id eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen des zweiten Pictures.<br/>';
}

echo 'Pictures Tabelle fertig';
?>
