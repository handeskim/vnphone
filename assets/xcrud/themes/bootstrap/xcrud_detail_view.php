
<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-top-actions">
    <?php echo $this->render_button('Lưu và Tạo Mới','save','create','btn btn-primary','','create,edit') ?>
    
    <?php echo $this->render_button('Lưu và Thoát','save','list','btn btn-success','','create,edit') ?>
    <?php echo $this->render_button('Thoát','list','','btn btn-warning') ?>
</div>
<div class="xcrud-view">
<?php echo $this->render_fields_list($mode); ?>
</div>
<div class="xcrud-nav">
    <?php echo $this->render_benchmark(); ?>
</div>
