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

// Transaktion starten
$db->beginTransaction();

// Statement vorbereiten
$stmt = $db->prepare("INSERT INTO report (rateable_id, title, location, description)
                      VALUES (:rateable_id, :title, :location, :description)");

$fehler = false;

// Erster Eintrag
if ($stmt->execute([
    ':rateable_id' => 1,
    ':title'       => 'Schöner Park',
    ':location'    => 'Hamburg Stadtpark',
    ':description' => 'Ein wunderschöner Park zum Spazieren'
])) {
    echo "Report 1 mit ID " . $db->lastInsertId() . " eingetragen.<br/>";
} else {
    echo "Fehler beim Eintragen von Report 1<br/>";
    $fehler = true;
}

// Zweiter Eintrag
if ($stmt->execute([
    ':rateable_id' => 2,
    ':title'       => 'Tolles Restaurant',
    ':location'    => 'Berlin Mitte',
    ':description' => 'Exzellentes Essen und Service'
])) {
    echo "Report 2 mit ID " . $db->lastInsertId() . " eingetragen.<br/>";
} else {
    echo "Fehler beim Eintragen von Report 2<br/>";
    $fehler = true;
}

// Transaktion abschließen oder zurückrollen
if (!$fehler) {
    $db->commit();
    echo "Transaktion erfolgreich abgeschlossen.<br/>";
} else {
    $db->rollBack();
    echo "Transaktion wurde wegen Fehler zurückgerollt.<br/>";
}
?>
