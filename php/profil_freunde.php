<div class="flex-row flex-grow">
    <div class="flex-column flex-grow profil-list">
        <div class="flex-shrink"><h2>Profile den ich folge</h2></div>
        <div class="flex-column flex-grow">
            <?php for($x=0;$x<10;$x++)
            {
                include "php/profil_preview.php";
            }?>
        </div>
    </div>
    <div class="flex-column flex-grow profil-list">
        <div class="flex-shrink"><h2>Profile die mir folgen</h2></div>
        <div class="flex-column flex-grow">
            <?php for($x=0;$x<15;$x++)
            {
                include "php/profil_preview.php";
            }?>
        </div>
    </div>
</div>