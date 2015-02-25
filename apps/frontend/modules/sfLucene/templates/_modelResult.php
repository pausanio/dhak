<?php
/**
 * @package sfLucenePlugin
 * @subpackage Module
 * @author Carl Vondrick <carl@carlsoft.net>
 * @version SVN: $Id: _modelResult.php 7108 2008-01-20 07:44:42Z Carl.Vondrick $
 */
?>
<?php echo link_to($result->getInternalTitle(), $result->getInternalUri()) ?>
<p><?php echo highlight_result_text($result->getInternalDescription(), $query, sfConfig::get('app_lucene_result_size', 200), sfConfig::get('app_lucene_result_highlighter', '<strong class="highlight">%s</strong>')) ?></p>