<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

try {
    $db = new PDO($dsn, $user, $pw);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT id, rateable_id, title, location, description FROM report ORDER BY id ASC';
    $result = $db->query($sql);

    echo '<h2>Report-Liste</h2>';
    echo '<ul>';
    foreach ($result as $row) {
        echo '<li><strong>ID:</strong> ' . htmlspecialchars($row['id']) .
            ' | <strong>Rateable ID:</strong> ' . htmlspecialchars($row['rateable_id']) .
            ' | <strong>Titel:</strong> ' . htmlspecialchars($row['title']) .
            ' | <strong>Ort:</strong> ' . htmlspecialchars($row['location']) .
            ' | <strong>Beschreibung:</strong> ' . htmlspecialchars($row['description']) .
            '</li>';
    }
    echo '</ul>';

} catch (PDOException $e) {
    echo 'Fehler beim Abrufen der Reports: ' . htmlspecialchars($e->getMessage());
}
?>
