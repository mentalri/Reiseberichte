<?php
$cssFiles = array("css/pages/bericht.css");
?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>
    <section class="bericht-container">
   
    <img src="img/reisebild.jpg" alt="Reisebild" class="bericht-bild">

    
    <div class="bericht-header">
        <h2 class="bericht-titel">Ein Tag in London</h2>
        <p><strong>Autor:</strong> Lisa Müller</p>
        <p><span class="icon">📍</span> London, England</p>
        <p><span class="icon">📅</span> 22.04.2025</p>
        <p><span class="icon">⭐</span> <strong>4.2</strong></p>
    </div>

  
    <div class="beschreibung">
        <p>Ich hatte eine wundervolle Zeit in London. Das Wetter war großartig und das British Museum war faszinierend.</p>
    </div>

  
    <div class="bewertung-bereich">
        <h3>Bewertung abgeben:</h3>
        <div class="sterne-bewertung">
           
            <span class="stern">★</span>
            <span class="stern">★</span>
            <span class="stern">★</span>
            <span class="stern">★</span>
            <span class="stern">☆</span>
        </div>
    </div>

  
    <div class="kommentarbereich">
        <h3>Kommentare</h3>

       
        <form class="kommentar-formular">
            <textarea placeholder="Dein Kommentar schreiben..." required></textarea>
            <button type="submit">Kommentar absenden</button>
        </form>

     
        <div class="kommentar">
            <strong>Nutzer1</strong>
            <p>Tolle Reisebeschreibung, danke fürs Teilen!</p>
        </div>
    </div>
</section>


    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>
