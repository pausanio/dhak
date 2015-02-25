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
    <body class="viewer">
        <?php include_partial('viewer/miniheader') ?>
        <?php include_partial('viewer/header') ?>
        <?php echo $sf_content ?>

        <!--[if lt IE 7 ]>
          <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
          <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
        <![endif]-->

        <?php if ($sf_request->getUriPrefix() == 'http://historischesarchivkoeln.de' || $sf_request->getUriPrefix() == 'http://www.historischesarchivkoeln.de'): ?>
            <?php include_partial('default/piwik') ?>
        <?php endif ?>

    </body>
</html>
