<h1>Test items List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Country</th>
      <th>Region</th>
      <th>City</th>
      <th>Country2</th>
      <th>City2</th>
      <th>Id category</th>
      <th>Id subcategory</th>
      <th>Id subsubcategory</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($test_items as $test_item): ?>
    <tr>
      <td><a href="<?php echo url_for('sfChoiceChainExample/edit?id='.$test_item->getId()) ?>"><?php echo $test_item->getId() ?></a></td>
      <td><?php echo $test_item->getName() ?></td>
      <td><?php echo $test_item->getIdCountry() ?></td>
      <td><?php echo $test_item->getIdRegion() ?></td>
      <td><?php echo $test_item->getIdCity() ?></td>
      <td><?php echo $test_item->getIdCountry2() ?></td>
      <td><?php echo $test_item->getIdCity2() ?></td>
      <td><?php echo $test_item->getCategoryId() ?></td>
      <td><?php echo $test_item->getSubCategoryId() ?></td>
      <td><?php echo $test_item->getSubSubCategoryId() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('sfChoiceChainExample/new') ?>">New</a>
