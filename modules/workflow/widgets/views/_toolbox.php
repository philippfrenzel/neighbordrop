<?php

use yii\helpers\Html;

?>

<?php foreach($menuItems AS $menuEntry): ?>

<?php if($menuEntry['link']!=''): ?>

  <a href="<?= $menuEntry['link']; ?>" class="btn btn-primary btn-md tipster toolbarbtn" title="<?= $menuEntry['label']; ?>">
    <i class="<?= $menuEntry['icon']; ?>"></i>
  </a>
  <br>

<?php endif; ?>

<?php endforeach; ?>
