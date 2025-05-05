<section class="content flex-column flex-grow">
                <div class="content-header flex-shrink">                    
                    <label for="sidebarToggleCheckbox" class="sidebar-toggle" id="sidebarToggle">
                        <span class="sidebar-toggle-arrow">&#8594</span>
                    </label>
                    <h2 class="m0 h2 fit-width">Einträge</h2>
                </div>
                <div class="preview-liste flex-grow">
                    <?php for($x=0;$x<10;$x++)
                    {
                        include "php/eintrag_preview.php";
                    }
                    ?>
                </div>
                
            </section>