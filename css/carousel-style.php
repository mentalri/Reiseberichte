<?php
if($report->getPictures() == null){
    return;
}
$numSlides = count($report->getPictures());
if($numSlides != 0):?>

<style type="text/css">
    .carousel {
    overflow: hidden;
  }

  .report-<?=$report->getId()?>-slides {
    display: flex;
    height: 100%;
    width: <?=count($report->getPictures());?>00%;
    animation: report-<?=$report->getId()?>-slide <?=7*$numSlides?>s infinite;
  }

  .report-<?=$report->getId()?>-slide {
    width: <?=100/$numSlides. "%";?>;
    height: 100%;
    flex-shrink: 0;
  }

  @keyframes report-<?=$report->getId()?>-slide {
    <?php 
        $duration = 7*$numSlides; // total duration in seconds 100
        $pct = 100 / $numSlides; // percentage for each slide  25
        $transitionPct = 1.35 / $duration * 100; // 1.35 seconds in pct of total duration 
        for ($i = 0; $i < $numSlides; $i++) {
            echo ($i * $pct) . "% { transform: translateX(-" . ($i * $pct) . "%); }\n";
            echo (($i + 1) * $pct - $transitionPct) . "% { transform: translateX(-" . ($i * $pct) . "%); }\n";
        }
        echo   "100% { transform: translateX(0%); }\n";
    ?>
  }

</style>
<?php endif; ?>