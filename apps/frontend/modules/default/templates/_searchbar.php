<div class="row" id="search_bar">
    <div class="span12 navbar <?php if ($sf_request->getParameter('module') == 'archiv'): ?>navbar-top<?php endif ?>">
        <div class="navbar-inner">
            <div class="row-fluid">
                <div class="span4">
                    <a class="brand" href="<?php echo url_for('@lesesaal_root') ?>">
                        <?php echo format_number(Archiv::getTotalDocuments()); ?> <?php echo __('Einträge im Lesesaal'); ?>
                    </a>
                </div>
                <div class="span8">
                    <form id="search" name="search" class="navbar-search pull-right" action="<?php echo url_for('lesesaal_search', array('query' => "")); ?>" method="get">
                        <div class="input-append">
                            <input type="text" class="input-xxlarge" placeholder="Lesesaal durchsuchen"<?php if (!false == $q_v): ?> value="<?php echo $q_v; ?>" <?php endif ?> name="form[query]" />
                            <button type="submit" class="btn"><i class="icon-search"></i> Suchen</button>
                        </div>
                    </form>
                </div>
                <?php /*
                  <div class="span3">
                  <ul class="sociallinks pull-right">
                  <li><a href="#" class="twitter" target="_blank">Twitter</a></li>
                  <li><a href="#" class="facebook" target="_blank">Facebook</a></li>
                  <li><a href="#" class="googleplus" target="_blank">Google+</a></li>
                  <li><a href="#" class="contact"><?php echo __('Kontakt'); ?></a></li>
                  <li><a href="#" class="rss" target="_blank">RSS Feed</a></li>
                  </ul>
                  </div>
                 */ ?>
            </div>
        </div>
    </div>
</div>