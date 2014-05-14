<?php

use dosamigos\chartjs\Chart;
use kartik\widgets\Affix;

/**
 * @var yii\web\View $this
 */
$this->title = 'NeighborDrop - Help your neighbor with droping picking or droping stuff';
?>

<div class="map-logo">
<?= Chart::widget([
    'type' => 'Bar',
    'options' => [
        'height' => 300,
        'width'  => 300,
     ],
    'data' => [
        'labels' => ["January", "February", "March", "April", "May", "June", "July"],
        'datasets' => [
            [
                'fillColor' => "rgba(220,220,220,0.5)",
                'strokeColor' => "rgba(220,220,220,1)",
                'pointColor' => "rgba(220,220,220,1)",
                'pointStrokeColor' => "#fff",
                'data' => [65, 59, 90, 81, 56, 55, 40]
            ],
            [
                'fillColor' => "rgba(151,187,205,0.5)",
                'strokeColor' => "rgba(151,187,205,1)",
                'pointColor' => "rgba(151,187,205,1)",
                'pointStrokeColor' => "#fff",
                'data' => [28, 48, 40, 19, 96, 27, 100]
            ]
        ]
    ]
]);
?>
</div>

<div class="container-fluid">
  <div class="row">
    <?php 
      if(class_exists('\frenzelgmbh\cmaddress\widgets\IPLocation')){
        echo \frenzelgmbh\cmaddress\widgets\IPLocation::widget([
          'title' => null
        ]); 
      }
    ?>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">
        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">LOIGN</a></p>
    </div>
  </div>
</div>
