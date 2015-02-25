<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <!--
          pausanio.com                       _
           _ __   __ _ _   _ ___  __ _ _ __ (_) ___
          | '_ \ / _` | | | / __|/ _` | '_ \| |/ _ \
          | |_) | (_| | |_| \__ \ (_| | | | | | (_) |
          | .__/ \__,_|\__,_|___/\__,_|_| |_|_|\___/
          |_|
                  developed 2011-2015 by Ivo Bathke and Maik Mettenheimer
        -->
        <meta charset="utf-8">
        <?php include_title() ?>
        <link rel="shortcut icon" href="/images/favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="alternate" type="application/atom+xml" title="News" href="<?php echo $sf_request->getUriPrefix() . $sf_request->getRelativeUrlRoot() . url_for('news', array('sf_format' => 'atom')) ?>" />
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_http_metas() ?>
        <?php include_stylesheets() ?>
        <!--[if lt IE 9]>
            <script src="/javascripts/lib/html5shiv.js"></script>
        <![endif]-->
        <?php include_javascripts() ?>
    </head>
    <body>

        <?php use_helper('Number') ?>
        <?php $layout = $sf_request->getAttribute('layout', 'cms') ?>

        <?php $q_v = $sf_request->getParameter('form', false) ?>
        <?php if (is_array($q_v) && array_key_exists('query', $q_v)) $q_v = $q_v['query']; ?>

        <div class="container-header">
            <div class="container">
                <?php include_partial('default/header') ?>
                <?php if ($layout != 'cms') include_partial('default/searchbar', array('q_v' => $q_v)) ?>
            </div>
        </div>

        <div class="container-content <?php if ($layout == 'cms'): ?>container-cms<?php endif ?>">
            <div class="container">
                <!--[if lt IE 10]>
                <?php $cms_text = cmsText::getTextByLanguage('allgemein') ?>
                <?php if ($cms_text): ?>
                    <?php echo htmlspecialchars_decode($cms_text['internet_explorer']['de']); ?>
                <?php endif ?>
                <![endif]-->
                <?php if ($layout == 'cms' || $layout == 'blank') echo $sf_content ?>
            </div>
        </div>

        <div id="footer">
            <div class="top"></div>
            <div class="container">
                <?php if ($layout == 'footer') echo $sf_content ?>
                <?php if ($layout != 'footer') include_component('home', 'footer') ?>
            </div>
        </div>

        <?php if ($sf_request->getUriPrefix() == 'http://historischesarchivkoeln.de' || $sf_request->getUriPrefix() == 'http://www.historischesarchivkoeln.de'): ?>
            <?php include_partial('default/piwik') ?>
        <?php endif ?>

    </body>
</html>
