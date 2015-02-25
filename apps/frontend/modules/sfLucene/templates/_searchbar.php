<div class="row" id="search_bar">
    <div class="span12 navbar <?php if ($sf_request->getParameter('module') == 'archiv'): ?>navbar-top<?php endif ?>">
        <div class="navbar-inner">
            <div class="row-fluid">
                <div class="span4 brand">
                    <?php echo $count; ?> Suchergebnisse zu "<?php echo $query; ?>"
                </div>
                <div class="span8">
                    <form id="search" name="search" class="navbar-search pull-right" action="<?php echo url_for('lesesaal_search', array('query' => "")); ?>" method="get">
                        <div class="input-append">
                            <input type="text" class="input-xxlarge" placeholder="Suchergebnisse zu "<?php echo $query; ?>" <?php if (!false == $query): ?> value="<?php echo $query; ?>" <?php endif ?> name="form[query]" />
                            <button type="submit" class="btn"><i class="icon-search"></i> Suchen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>