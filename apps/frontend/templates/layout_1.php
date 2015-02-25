<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <?php # atom-link :-) <?php echo url_for('news', array('sf_format' => 'atom')) */ ?>
        <meta charset="utf-8">
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_http_metas() ?>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <?php $q_value = __('Ihren Suchbegriff eingeben'); ?>
    </head>
    <body>
        <?php
        use_helper('Number');
        $show = $sf_request->getParameter('module');
        $lang = $sf_user->getCulture();
        $innerClass = $show;
        $archive = false;
        $contentFile = 'nein';
        $headerClass = '';
        if ($show == 'netzwerk') {
            $show = "network";
        }
        if ($show == 'home') {
            $show = 'infoWrapper';
        }
        $headerClass = $sf_request->getAttribute('header_class');
        $searchWrapperClass = $sf_request->getAttribute('search_wrapper_class');
        $innerClass = $sf_request->getAttribute('inner_class');
        $contentWrapperClass = $sf_request->getAttribute('content_wrapper_class');
        $archive = (is_null($sf_request->getAttribute('is_archive'))) ? false : true;
        $archiv = '';
        $is_info_page = $sf_request->getAttribute('is_info_page');
        $content_in_footer = $sf_request->getAttribute('content_in_footer');
        if (strpos($show, 'sfGuard') !== false) {
            $contentWrapperClass = 'noBg';
        }
        $signin = new sfGuardFormSignin();
        if (!$content_in_footer) {
            $sf_user->setAttribute('footer_minimize_destination', $sf_context->getRouting()->getCurrentInternalUri());
        }
        ?>
        <div id="globalWrapper">
            <div id="header" class="<?php echo $headerClass; ?>">
                <div id="logo">
                    <a href="<?php echo url_for('homepage') ?>">
                        <img src="/images/dhak_ilogo.png"/>
                    </a>
                </div>
                <ul id="navMain" class="sf-menu">
                    <?php if ($sf_user->isAuthenticated()): ?>
                        <li class="login account">
                            <a href="" id="user_menu">Persönlicher Bereich</a>
                            <ul>
                                <li><a href="<?php echo url_for('dokument') ?>">Meine Archivalien</a></li>
                                <li><a href="<?php echo url_for('dokument/new') ?>">Archivalie aufnehmen</a></li>
                                <li><a href="<?php echo url_for('projekte') ?>">Meine Projekte</a></li>
                                <li><a href="<?php echo url_for('projekte/new') ?>">Projekt eintragen</a></li>
                                <li><a href="<?php echo url_for('sf_guard_signout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="login">
                            <a id="loginlink" href="<?php echo url_for('sf_guard_signin') ?>">
                                <span><?php echo __('Login'); ?></span>
                            </a>
                            <div class="loginWrapper">
                                <div class="topArrow"></div>
                                <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $signin)) ?>
                            </div>
                        </li>
                    <?php endif ?>
                    <li>
                        <a href="<?php echo url_for('patenschaft_intro') ?>" class="<?php echo (strpos($sf_request->getPathInfo(), 'patenschaft')) ? 'active' : ''; ?>">
                            <?php echo __('Patenschaften') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url_for('netzwerk') ?>" class="<?php echo ($show == 'network') ? 'active' : ''; ?>">
                            <?php echo __('Netzwerk') ?>
                        </a>
                    </li>
                    <li class="archive">
                        <a href="<?php echo url_for('@lesesaal?type=archiv') ?>" class="<?php echo ($show == 'archive') ? 'active' : ''; ?>">
                            <?php echo __('Digitaler Lesesaal') ?>
                        </a>
                    </li>
                    <li class="info">
                        <a href="<?php echo url_for('homepage') ?>" class="<?php echo ($show == 'infoWrapper') ? 'active' : ''; ?>">
                            <?php echo __('Informationen') ?>
                        </a>
                    </li>
                </ul>
                <?php
                $url_for_search = 'lesesaal_search';
                $search_is_extended = false;
                if (strpos($searchWrapperClass, 'extended') !== false) {
                    $url_for_search .= '_extended';
                    $search_is_extended = true;
                }
                ?>
                <div id="searchWrapper" class="<?php echo $searchWrapperClass; ?>">
                    <div class="body">
                        <p class="amount">
                            <a style="color: <?php echo ($searchWrapperClass == 'archive') ? '#fff' : '#007e8c'; ?>;" href="<?php echo url_for('@lesesaal?type=archiv') ?>">
                                <?php echo format_number(Archiv::getTotalDocuments()); ?> <?php echo __('Einträge im Archiv'); ?>
                            </a>
                        </p>
                        <form id="search" name="search" action="<?php echo url_for($url_for_search, array('query' => "")); ?>" method="get">
                            <?php
                            $q_v = $sf_request->getParameter('form', $q_value);
                            if (is_array($q_v) && array_key_exists('query', $q_v))
                                $q_v = $q_v['query'];
                            ?>
                            <input type="text" id="search_query" name="form[query]" class="text" value="<?php echo $q_v; ?>" onfocus="clearValue(this, '<?php echo $q_value ?>')"/>
                            <input type="submit" class="submit" value="Suchen" />
                        </form>
                    </div>
                </div>

                <?php if ($show == 'infoWrapper'): ?>
                    <?php echo $sf_content ?>
                <?php endif; ?>

            </div>
        </div>

        <div id="contentWrapper" class="<?php echo $contentWrapperClass ?>">
            <?php if (!$content_in_footer AND $show != 'infoWrapper'): ?>
                <div class="contentInner <?php echo ($is_info_page) ? 'info' : substr($show, 0, 4) ?> <?php echo $innerClass ?>">
                    <?php echo $sf_content ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$content_in_footer) include_component('home', 'footer') ?>
        <?php if ($content_in_footer) echo $sf_content; ?>

        <script src="/js/plugins.js"></script>
        <script src="/js/script.js"></script>

        <!--[if lt IE 7 ]>
          <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
          <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
        <![endif]-->

    </body>
</html>
