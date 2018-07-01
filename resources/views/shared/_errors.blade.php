
<?php  if(count($errors)){?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach($errors->all() as $v){?>
        <li><?=$v?></li>
        <?php }?>
      </ul>
<?php }?>