<h1>Lesesaal</h1>

<div class="row">
  <div class="span5">
    <div class="well sidebar-nav">
      <?php if ($tree): ?>
        <?php include_partial('archiv/tree', array('tree' => $tree, 'path' => $path, 'current' => $current, 'parent' => null)) ?>
      <?php endif; ?>
    </div>
    <div class="well sidebar-nav">
      <ul class="nav nav-list">
        <li><a target="_blank" href="<?php echo url_for('archiv/simpletree') ?>">Einfache Gesamtübersicht der Archivsystematik</a></li>
      </ul>
    </div>
  </div>
  <div class="span7">

    <ul class="breadcrumb">
      <?php if ($ancestors): ?>
        <?php foreach ($ancestors as $ancestor): ?>
          <li>
            <a href="<?php echo url_for('archiv/tree?id=' . $ancestor['id']) ?>">
              <?php echo $ancestor['name'] ?>
            </a>
            <span class="divider">/</span>
          </li>
        <?php endforeach ?>
      <?php endif ?>
      <li><?php echo $currentArchiv->getName() ?></li>
    </ul>

    <h2>
      <?php echo $currentArchiv->getName() ?>
      <?php if ($currentArchiv->getType() > 0): ?>
        <small><?php echo $currentArchiv->getModel($currentArchiv->getType()) ?></small>
      <?php endif ?>
    </h2>

    <hr>

    <?php if ($currentArchiv->getBeschreibung()): ?>
      <p><?php echo $currentArchiv->getBeschreibung() ?></p>
    <?php endif ?>

    <?php if ($currentArchiv->getUserDescription()): ?>
      <h3>Ergänzungen</h3>
      <?php echo $currentArchiv->getUserDescription() ?>
    <?php endif ?>

    <?php if ($currentArchiv->getContactperson()): ?>
      <h3>Ansprechpartner</h3>
      <p><?php echo $currentArchiv->getContactperson() ?></p>
    <?php endif ?>

    <div class="btn-group">
      <a class="btn" href="<?php echo url_for('archiv/edit?id=' . $currentArchiv->getId()) ?>"><i class="icon icon-edit"></i> Bearbeiten</a>
    </div>

    <?php include_partial('archiv/bestand', array('bestand' => $bestand)) ?>
    <?php include_partial('archiv/verzeichnungseinheiten', array('verzeichnungseinheiten' => $verzeichnungseinheiten)) ?>
    <?php include_partial('archiv/dokumente', array('dokuments' => $dokuments)) ?>

  </div>
</div>

