<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

try {
    $db = new PDO($dsn, $user, $pw);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT id, username, email, description FROM users ORDER BY id ASC';
    $result = $db->query($sql);

    echo '<h2>Benutzerliste</h2>';
    echo '<ul>';
    foreach ($result as $row) {
        echo '<li><strong>ID:</strong> ' . htmlspecialchars($row['id']) .
            ' | <strong>Username:</strong> ' . htmlspecialchars($row['username']) .
            ' | <strong>Email:</strong> ' . htmlspecialchars($row['email']) .
            ' | <strong>Beschreibung:</strong> ' . htmlspecialchars($row['description']) .
            '</li>';
    }
    echo '</ul>';

} catch (PDOException $e) {
    echo 'Fehler beim Abrufen der Benutzer: ' . htmlspecialchars($e->getMessage());
}
?>

