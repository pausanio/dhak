<?php if(isset($add_empty)): ?>
<option value=""><?php echo $add_empty; ?></option>
<?php endif; ?>
<?php foreach($options as $option): ?>
    <option value="<?php echo $option->getPrimaryKey(); ?>"><?php echo $option; ?></option>
<?php endforeach; ?>