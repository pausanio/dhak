<h2>Patenschafts-Objekt bearbeiten</h2>
<?php include_partial('form', array('form' => $form)) ?>

<p>&nbsp;</p>
<h3>Abbildungen zu diesem Objekt </h3>
<?php foreach ($images as $image) : ?>
  <img src="/images/patenpic/thumbs/th_pat_<?php echo $image->getIntname() ?>" />
<?php endforeach; ?>
<p></p>
<?php include_partial('pic_form', array('form' => $pic_form)) ?>