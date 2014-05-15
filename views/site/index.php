<?php

use dosamigos\chartjs\Chart;
use kartik\widgets\Affix;
use kartik\icons\Icon;

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
        'width'  => 80,
     ],
    'data' => [
        'labels' => ["RS"],
        'datasets' => [
            [
                'fillColor' => "rgba(220,220,220,0.5)",
                'strokeColor' => "rgba(220,220,220,1)",
                'pointColor' => "rgba(220,220,220,1)",
                'pointStrokeColor' => "#fff",
                'data' => [65]
            ],
            [
                'fillColor' => "rgba(151,187,205,0.5)",
                'strokeColor' => "rgba(151,187,205,1)",
                'pointColor' => "rgba(151,187,205,1)",
                'pointStrokeColor' => "#fff",
                'data' => [28]
            ]
        ]
    ]
]);
?>
</div>

<div class="container-fluid bg_azure">
  <div class="row">
    <?php 
      if(class_exists('\frenzelgmbh\cmaddress\widgets\IPLocation')){
        echo \frenzelgmbh\cmaddress\widgets\IPLocation::widget([
          'title' => null,
          'options' => [
            'height' => 400,
            'zoom' => 10
          ]          
        ]); 
      }
    ?>
  </div>
</div>

<div id="slide1">
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 class="fg_white">How it works...</h1>      
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-2">
      <?= Icon::show('thumb-tack', ['class' => 'fa-4x pull-right fg_white'], Icon::FA); ?>
    </div>
    <div class="col-md-4">
      <div class="fg_dark">
        <h2 class="fg_white">The Idea</h2>
        NeighborDrop is a plattform that allows users to register several "drops". A drop
        can be your <b>HOME</b>, <b>WORKPLACE</b> or another place where you regulary live.
        After you registered at the platform you add your drops to your profile and then
        the social part begins - you need milk, but you can't get to the groceries in time...
        Post your <b>SUPPORTREQUEST</b> to the community and see if an NEIGHBOR can help you!
      </div>
    </div>
    <div class="col-md-2">
      <?= Icon::show('h-square', ['class' => 'fa-4x pull-right fg_white'], Icon::FA); ?>
    </div>
    <div class="col-md-4">
      <div class="fg_dark">
        <h2 class="fg_white">Samples</h2>
        <b>SUPPORTREQUEST</b>s are categorized by the type of support you request, e.g. need something
        from the groceries. Or maybe you need someone to help you with the laundry or you are sick and
        can't walk to the pharmacy.
      </div>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-2">
      <?= Icon::show('money', ['class' => 'fa-4x pull-right fg_white'], Icon::FA); ?>
    </div>
    <div class="col-md-4">
      <div class="fg_dark">
        <h2 class="fg_white">Security</h2>
        As money matters in real life and even as we are a social plattform, we need a backup
        for the services <b>NEIGHBOR</b>s deliver, which means, as you are gonna buy a liter of
        milk for another user, you'll have to spend e.g. 50 Cents. So as you spend the money,
        we wanna be sure, you'll get it back. So each user can ask for things to buy, only if he
        has a certain amount of <b>CALORIES</b> on his account. <b>CALORIES</b> can be bought
        for the price of one banana (89 CALORIES).
      </div>
    </div>
    <div class="col-md-2">
      <?= Icon::show('user', ['class' => 'fa-4x pull-right fg_white'], Icon::FA); ?>
    </div>
    <div class="col-md-4">
      <div class="fg_dark">
        <h2 class="fg_white">Supporters</h2>
        As not only money matters to us, we keep a statistic of all the important and good
        thinks that are done by our community. While you decide to join, you can always see
        how much good you have done in a month. Each month we give an award to the top 3 
        neighbors to honor their social behaviour.
        
        <h2 class="fg_white">Login</h2>
        <?= \dektrium\user\widgets\Connect::widget(); ?>
        <?= \dektrium\user\widgets\Login::widget(); ?>
        
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      
    </div>
    <div class="col-md-6">
     
    </div>
  </div>
</div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">
        
    </div>
  </div>
</div>
