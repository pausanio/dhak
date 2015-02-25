<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ha_projekte->getId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $ha_projekte->getUserId() ?></td>
    </tr>
    <tr>
      <th>Projekt title de:</th>
      <td><?php echo $ha_projekte->getProjektTitle() ?></td>
    </tr>
    <tr>
      <th>Projekt type:</th>
      <td><?php echo $ha_projekte->getProjektType() ?></td>
    </tr>
    <tr>
      <th>Projekt einsteller:</th>
      <td><?php echo $ha_projekte->getProjektEinsteller() ?></td>
    </tr>
    <tr>
      <th>Projekt bestand:</th>
      <td><?php echo $ha_projekte->getProjektBestand() ?></td>
    </tr>
    <tr>
      <th>Projekt notiz:</th>
      <td><?php echo $ha_projekte->getProjektNotiz() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $ha_projekte->getStatus() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ha_projekte->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ha_projekte->getUpdatedAt() ?></td>
    </tr>
    <tr>
      <th>Created by:</th>
      <td><?php echo $ha_projekte->getCreatedBy() ?></td>
    </tr>
    <tr>
      <th>Updated by:</th>
      <td><?php echo $ha_projekte->getUpdatedBy() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('projekte/edit?id='.$ha_projekte->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('projekte/index') ?>">List</a>
