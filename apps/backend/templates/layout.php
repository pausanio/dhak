<!DOCTYPE html>
<html xml:lang="de" lang="de">
    <head>
        <?php include_title() ?>
        <meta charset="utf-8">

        <!--
          pausanio.com                       _
           _ __   __ _ _   _ ___  __ _ _ __ (_) ___
          | '_ \ / _` | | | / __|/ _` | '_ \| |/ _ \
          | |_) | (_| | |_| \__ \ (_| | | | | | (_) |
          | .__/ \__,_|\__,_|___/\__,_|_| |_|_|\___/
          |_|
                  developed 2011 - 2015 by Ivo Bathke and Maik Mettenheimer
        -->

        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <script>
            pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
        </script>
    </head>
    <body <?php if ($sf_request->getParameter('module') == 'doc'): ?> data-spy="scroll" data-target=".aside" class="doc" <?php endif ?> >

        <?php include_partial('general/navigation') ?>

        <div class="container" id="sf_content">
            <div class="row">
                <div class="span12">
                    <?php echo $sf_content ?>
                </div>
            </div>
        </div>

        <?php include_partial('general/footer') ?>

    </body>
</html>
