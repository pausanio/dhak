<?php

/**
 * CmsInfo form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 */
class CmsInfoForm extends BaseCmsInfoForm
{

    public function configure()
    {
        parent::configure();

        unset($this->widgetSchema['created_at'], $this->widgetSchema['updated_at']);
        unset($this->validatorSchema['created_at'], $this->validatorSchema['updated_at']);
        unset($this['root_id'], $this['lft'], $this['rgt'], $this['level']);

        $this->widgetSchema['parent_id'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'CmsInfo',
            'add_empty' => '~ (object is at root level)',
            'order_by' => array('root_id, lft', ''),
            'method' => 'getIndentedName'
        ));
        $this->validatorSchema['parent_id'] = new sfValidatorDoctrineChoice(array(
            'required' => false,
            'model' => 'CmsInfo'
        ));
        $this->setDefault('parent_id', $this->object->getParentId());

        $this->widgetSchema['text'] = new sfWidgetFormTextareaTinyMCE(array(
            'width' => sfConfig::get('app_tinymce_options_width'),
            'height' => sfConfig::get('app_tinymce_options_height'),
            'config' => sfConfig::get('app_tinymce_options'),
                #'config' => 'relative_urls : "false", convert_urls : "false", document_base_url : "http://www.historischesarchivkoeln.de/"'
                #'config' => 'valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,#p,-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],q[cite],samp,select[disabled|multiple|name|size],small,textarea[cols|rows|disabled|name|readonly],tt,var,big,video,audio,source[src]"'
        ));

        $this->widgetSchema['route_name']->setAttribute('readonly', 'readonly');
        $this->widgetSchema['route_name']->setAttribute('size', '80');

        $this->widgetSchema->setLabels(array(
            'parent_id' => 'Übergeordnete Seite',
            'title' => 'Titel/ Überschrift',
            'route_name' => 'URL-Schnipsel',
            'text_navi' => 'Navigationstitel',
            'text_top' => 'Einleitungstext',
            'text' => 'Inhalt'
        ));

        $route_info = ($this->object->getRouteName()) ? 'http://historischesarchivkoeln.de/de/info/' . $this->object->getRouteName() : '';
        $this->widgetSchema->setHelp('route_name', $route_info);
    }

    public function updateParentIdColumn($parentId)
    {
        $this->parentId = $parentId;
        // further action is handled in the save() method
    }

    protected function doSave($con = null)
    {
        parent::doSave($con);

        $node = $this->object->getNode();

        if ($this->parentId != $this->object->getParentId() || !$node->isValidNode()) {
            if (empty($this->parentId)) {
                //save as a root
                if ($node->isValidNode()) {
                    $node->makeRoot($this->object['id']);
                    $this->object->save($con);
                } else {
                    $this->object->getTable()->getTree()->createRoot($this->object); //calls $this->object->save internally
                }
            } else {
                //form validation ensures an existing ID for $this->parentId
                $parent = $this->object->getTable()->find($this->parentId);
                $method = ($node->isValidNode() ? 'move' : 'insert') . 'AsFirstChildOf';
                $node->$method($parent); //calls $this->object->save internally
            }
        }
    }

}
