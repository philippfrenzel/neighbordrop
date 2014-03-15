<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div id="content">

  <div class="row">
    <div class="col-md-9">   
      <div class="cms">
        <?= $content; ?>
      </div>
    </div>
    <div class="col-md-3">
      <div class="pg-sidebar">    
        <?= $this->blocks['sidebar']; ?>
      </div>      
    </div>
  </div>
  
</div><!-- container -->
<?php $this->endContent(); ?>
