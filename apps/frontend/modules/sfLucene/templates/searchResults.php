<?php use_helper('sfLucene', 'I18N') ?>
<?php use_javascript('/javascripts/lib/jquery.highlight-search-terms.min.js') ?>
<?php  sfContext::getInstance()->getResponse()->removeStylesheet('/sfLucenePlugin/css/search.css'); ?>
<div id="archiveWrapper" class="searchResults">
  <?php include_partial('sfLucene/searchbar', array('count' => $pager->getNbResults(),'query' => $query)) ?>
  <div class="archiv-content searchResults">
    <?php if (!$query) : ?>
       <?php echo __('Bitte geben Sie einen Suchbegriff ein'); ?>
    <?php elseif($pager->getNbResults() > 0): ?>
      <div style="padding-top: 30px;">
        <?php foreach ($pager->getResults() as $result): ?>
        <div class="row-fluid" style="margin-left: 40px;">
            <div class="span11">
            <?php include_search_result($result, $query) ?>
            </div>
        </div>
        <?php endforeach ?>
      <div class="fixfloat"></div>
      <div class="hr"></div>
      <div class="row-fluid" style="margin-left: 40px; padding-bottom: 20px;">
      <?php include_search_pager($pager, $form, sfConfig::get('app_lucene_pager_radius', 5)) ?>
      </div>
      <script type="text/javascript">$(function(){$('.searchResults').highlight(['<?php echo implode("','", explode(' ', $query)); ?>']);});</script>
    <?php else: ?>
      <?php echo __('Es wurde leider nichts gefunden.'); ?>
    <?php endif; ?>
    </div>
  </div>

</div>


