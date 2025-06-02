<?php
$user = null;
$pw = null;
$dsn = 'sqlite:database.db';

$db = new PDO($dsn, $user, $pw);

$sql = 'CREATE TABLE follows (' .
    ' following_user_id INTEGER,' .
    ' followed_user_id INTEGER,' .
    ' created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,' .
    ' PRIMARY KEY (following_user_id, followed_user_id),' .
    ' FOREIGN KEY (following_user_id) REFERENCES users(id),' .
    ' FOREIGN KEY (followed_user_id) REFERENCES users(id) );';
$db->exec($sql);
echo 'Follows Tabelle angelegt.<br/>';

$sql = "INSERT INTO follows (following_user_id, followed_user_id) VALUES (1, 2);";
if ($db->exec($sql)) {
    echo "Follow-Beziehung (User 1 folgt User 2) eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen der ersten Follow-Beziehung.<br/>';
}

$sql = "INSERT INTO follows (following_user_id, followed_user_id) VALUES (2, 1);";
if ($db->exec($sql)) {
    echo "Follow-Beziehung (User 2 folgt User 1) eingetragen.<br />";
} else {
    echo 'Fehler beim Eintragen der zweiten Follow-Beziehung.<br/>';
}

echo 'Follows Tabelle fertig';
?>
