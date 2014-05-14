<?php
/**
 * @var yii\web\View $this
 */
$this->title = 'NeighborDrop - Help your neighbor with droping picking or droping stuff';
?>

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
      <img src="img/intro_one.png" alt="Intro">
    </div>
    <div class="col-md-6">
      <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
      </div>
    </div>
  </div>
</div>
