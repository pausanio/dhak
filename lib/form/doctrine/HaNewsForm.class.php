<?php

/**
 * HaNews form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Norman Fiedler
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class HaNewsForm extends BaseHaNewsForm
{

    public function configure()
    {

        unset($this['created_at'], $this['updated_at'], $this['created_by'], $this['updated_by'], $this['user_id']);

        $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
            'choices' => array(1 => 'yes', 0 => 'no'),
            'expanded' => true)
        );

        $this->widgetSchema['publish_date'] = new sfWidgetFormDate(array(
            'default' => time(),
            'format' => '%day%.%month%.%year%'
        ));

        /* form validation error !?
          $this->widgetSchema['publish_date'] = new sfWidgetFormJQueryDate(array(
          'date_widget' => new sfWidgetFormI18nDate(array('culture'=>'de')),
          'config' => '{}',
          //  'culture' => sfContext::getInstance()->getUser()->getCulture()
          //  'culture' => 'de'
          ));
         */

        // Tags
        $options['default'] = implode(', ', $this->getObject()->getTags());
        if (sfConfig::get('app_a_all_tags', true)) {
            $options['all-tags'] = PluginTagTable::getAllTagNameWithCount();
        } else {
            sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
            $options['typeahead-url'] = url_for('taggableComplete/complete');
        }

        $options['popular-tags'] = PluginTagTable::getPopulars(null, array(), false);

        $this->setWidget('tags', new pkWidgetFormJQueryTaggable($options, array('class' => 'tags-input')));

        $this->setValidator('tags', new sfValidatorString(array('required' =>
            false)));

        /*
          $this->widgetSchema['news_text'] = new sfWidgetFormTextareaTinyMCE(array(
          'width' => sfConfig::get('app_tinymce_options_width'),
          'height' => sfConfig::get('app_tinymce_options_height'),
          'config' => sfConfig::get('app_tinymce_options')));
         */

        $this->widgetSchema['news_text'] = new sfWidgetFormTextareaTinyMCE(array(
            'width' => sfConfig::get('app_tinymce_options_width'),
            'height' => sfConfig::get('app_tinymce_options_height'),
            'config' => sfConfig::get('app_tinymce_options'),
                #'config' => 'relative_urls : "false", convert_urls : "false", document_base_url : "http://www.historischesarchivkoeln.de/"'
                #'config' => 'valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,#p,-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],q[cite],samp,select[disabled|multiple|name|size],small,textarea[cols|rows|disabled|name|readonly],tt,var,big,video,audio,source[src]"'
        ));

        $this->widgetSchema['image'] = new sfWidgetFormInputFileEditable(array(
            'label' => 'Bild',
            'delete_label' => 'Bild löschen',
            'file_src' => $this->getObject()->getImageSrc(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'template' => '<div>%file%<br />%input%<br />%delete% %delete_label%</div>',
        ));

        $this->validatorSchema['image'] = new sfValidatorFile(array(
            'path' => sfConfig::get('app_cms_news_imagefile_org'),
            'mime_types' => 'web_images',
            'required' => false,
            'validated_file_class' => 'ValidatedOriginalnameFile',
            'mime_types' => array('image/jpeg', 'image/png', 'image/x-png', 'image/gif')
                ), array(
            'mime_types' => 'Der Dateityp wird nicht unterstützt.'
                )
        );
        $this->validatorSchema['image_delete'] = new sfValidatorPass();

        $this->widgetSchema->setHelp('image', 'Erlaubte Formate: gif, png, jpg');
    }

}

