<?php
/**
 * Dynamic Carousel Style Generator (carousel-style.php)
 * Creates custom CSS animations for report image carousels
 * Generates unique styles based on each report's ID and number of images
 */

// Skip generation if report has no pictures
if($report->getPictures() == null){
    return;
}

// Get number of slides and only generate styles if there are pictures
$numSlides = count($report->getPictures()); 
if($numSlides != 0):?>

<style type="text/css">
    /* Container for the carousel - hides overflow */
    .carousel {
        overflow: hidden;
    }
    
    /* Slides container - sets width based on number of slides and enables animation */
    .report-<?=$report->getId()?>-slides {
        display: flex;
        height: 100%;
        width: <?=count($report->getPictures());?>00%;  /* Width grows with number of slides */
        animation: report-<?=$report->getId()?>-slide <?=7*$numSlides?>s infinite;  /* Duration scales with slides */
    }
    
    /* Individual slide styling - width adjusts based on number of slides */
    .report-<?=$report->getId()?>-slide {
        width: <?=100/$numSlides. "%";?>;
        height: 100%;
        flex-shrink: 0;
    }
    
    /* Dynamic keyframe animation - creates smooth transitions between slides */
    @keyframes report-<?=$report->getId()?>-slide {
        <?php 
        // Calculate timing values for the animation
        $duration = 7*$numSlides;
        $pct = 100 / $numSlides;
        $transitionPct = 1.35 / $duration * 100;
        
        // Generate keyframes for each slide position
        for ($i = 0; $i < $numSlides; $i++) {
            // Hold position at start of each slide segment
            echo ($i * $pct) . "% { transform: translateX(-" . ($i * $pct) . "%); }\n";
            
            // Hold position until just before transition to next slide
            echo (($i + 1) * $pct - $transitionPct) . "% { transform: translateX(-" . ($i * $pct) . "%); }\n";
        }
        
        // Return to first slide at the end of animation
        echo "100% { transform: translateX(0%); }\n";
        ?>
    }
</style>
<?php endif; ?>