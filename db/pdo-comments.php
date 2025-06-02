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

// Transaktion starten
$db->beginTransaction();

// Prepared Statement vorbereiten
$stmt = $db->prepare("INSERT INTO comments (rateable_id, owner_id, text)
                      VALUES (:rateable_id, :owner_id, :text)");

$fehler = false;

// Erster Kommentar
if ($stmt->execute([
    ':rateable_id' => 1,
    ':owner_id'    => 1,
    ':text'        => 'Das ist ein super Kommentar!'
])) {
    echo "Kommentar 1 mit ID " . $db->lastInsertId() . " eingetragen.<br/>";
} else {
    echo "Fehler beim Eintragen von Kommentar 1<br/>";
    $fehler = true;
}

// Zweiter Kommentar
if ($stmt->execute([
    ':rateable_id' => 2,
    ':owner_id'    => 2,
    ':text'        => 'Hier ist noch ein Testkommentar.'
])) {
    echo "Kommentar 2 mit ID " . $db->lastInsertId() . " eingetragen.<br/>";
} else {
    echo "Fehler beim Eintragen von Kommentar 2<br/>";
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
