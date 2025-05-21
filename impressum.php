<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path."/php/include/head.php" ?>
<title>Impressum</title>
<link rel="stylesheet" href="css/pages/footer-elements.css">
<body>

    <?php require_once $abs_path."/php/include/nav.php" ?>

    <main>

    <h1>Impressum</h1>
    <div class="datenschutz-container">
<div class="content">
<div>

  <section>
    <h2>Verantwortlich für den Inhalt</h2>
    <p><strong>Reiseberichte</strong></p>
    <p>Internet: <a href="http://www.Reiseberichte.de" target="_blank">www.Reiseberichte.de</a></p>
    <p>E-Mail: <a href="mailto:reiseberichte@info.de">reiseberichte@info.de</a></p>
    <p>Telefon: 0443567128-9</p>
  </section>

  <section>
    <h2>Entwickler</h2>
    <ul>
      <li>Fanping Zhou Schulze</li>
      <li>Thore Ritterhoff</li>
      <li>Bayan Alhawari</li>
    </ul>
  </section>
    <section>
        <h2>Haftungsausschluss</h2>
        <p>Die Inhalte dieser Webseite wurden mit größter Sorgfalt erstellt. Dennoch übernehmen wir keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität der bereitgestellten Informationen.</p>
        <p>Wir sind nicht verantwortlich für die Inhalte externer Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.</p>
    </section>
    <section>
        <h2>Credits</h2>
        <p>Die Bilder auf dieser Webseite stammen von verschiedenen Quellen. Die Urheberrechte liegen bei den jeweiligen Fotografen.</p>
        <p>
            <a href="https://www.flaticon.com/de/kostenlose-icons/profilbild" title="profilbild Icons">Profilbild Icons erstellt von kliwir art - Flaticon</a><br>
            <a href="https://www.flaticon.com/free-icons/delete" title="delete icons">Delete icons erstellt von Kiranshastry - Flaticon</a><br>
            <a href="https://www.flaticon.com/free-icons/edit" title="edit icons">Edit icons erstellt von Kiranshastry - Flaticon</a><br>
            <a href="https://www.flaticon.com/de/kostenlose-icons/bild" title="bild Icons">Bild Icons erstellt von Freepik - Flaticon</a><br>
            <a href="https://www.flaticon.com/de/kostenlose-icons/nachster" title="nächster Icons">Nächster Icons erstellt von Roundicons - Flaticon</a>
        </p>
    </section>
      
</div>
</div>
</div>
    </main>
    <?php require_once $abs_path."/php/include/footer.php" ?>
</body>

</html>