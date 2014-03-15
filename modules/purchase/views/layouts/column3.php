<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div id="content">

  <div class="row">
    <div class="col-md-1">   
      <div class="pg-sidebar">    
        <?= $this->blocks['sidebar']; ?>
      </div>
    </div>
    <div class="col-md-8">
      <div class="formular">
        <?= $content; ?>
      </div>
    </div>
    <div class="col-md-3">   
      <div class="pg-sidebar">    
        <?= $this->blocks['toolbar']; ?>
      </div>
    </div>
  </div>
  
</div><!-- container -->
<?php $this->endContent(); ?>
