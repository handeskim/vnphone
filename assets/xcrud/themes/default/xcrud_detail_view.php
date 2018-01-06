
<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-top-actions">
    <?php echo $this->render_button('lưu và quay lại','save','list','xcrud-button xcrud-purple','','create,edit') ?>
    <?php echo $this->render_button('quay lại','list','','xcrud-button xcrud-orange') ?>
</div>
<div class="xcrud-view">
		<?php echo $this->render_fields_list($mode); ?>
</div>
<div class="xcrud-nav">
    <?php echo $this->render_benchmark(); ?>
</div>



