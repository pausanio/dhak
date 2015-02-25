<?php sfContext::getInstance()->getResponse()->removeStylesheet('/sfLucenePlugin/css/search.css'); ?>
<div id="archiveWrapper" class="searchResults">
    <div class="header">
        <?php $q_v = $sf_request->getParameter('form', '');
        if (isset($q_v['query']))
            $q_v = $q_v['query'];
        ?>
<?php include_partial('sfLucene/searchbar', array('count' => 0, 'query' => $q_v)) ?>
    </div>
    <div class="archiv-content">
        <div class="row-fluid">
            <div class="span12" style="padding-left: 40px;">
                <h3>Leider wurde nichts gefunden.</h3>
            </div>
        </div>
    </div>
</div>