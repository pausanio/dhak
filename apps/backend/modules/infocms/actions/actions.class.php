<?php

require_once dirname(__FILE__) . '/../lib/infocmsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/infocmsGeneratorHelper.class.php';

/**
 * info cms actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage infocms
 * @author     Ivo Bathke
 */
class infocmsActions extends autoInfocmsActions {

    protected function addSortQuery($query) {
        //don't allow sorting; always sort by tree and lft
        $query->addOrderBy('root_id, lft');
    }

    public function executeBatch(sfWebRequest $request) {
        if ("batchOrder" == $request->getParameter('batch_action')) {
            return $this->executeBatchOrder($request);
        }

        parent::executeBatch($request);
    }

    public function executeBatchOrder(sfWebRequest $request) {
        $newparent = $request->getParameter('newparent');

        //manually validate newparent parameter
        //make list of all ids
        $ids = array();
        foreach ($newparent as $key => $val) {
            $ids[$key] = true;
            if (!empty($val))
                $ids[$val] = true;
        }
        $ids = array_keys($ids);

        //validate if all id's exist
        $validator = new sfValidatorDoctrineChoice(array('model' => 'CmsInfo', 'multiple' => true));
        try {
            // validate ids
            $ids = $validator->clean($ids);

            // the id's validate, now update the tree
            $count = 0;
            $flash = "";

            foreach ($newparent as $id => $parentId) {
                if (!empty($parentId)) {
                    $node = Doctrine::getTable('CmsInfo')->find($id);
                    $parent = Doctrine::getTable('CmsInfo')->find($parentId);

                    if (!$parent->getNode()->isDescendantOfOrEqualTo($node)) {
                        $node->getNode()->moveAsFirstChildOf($parent);
                        $node->save();

                        $count++;

                        $flash .= "<br/>Moved '" . $node['title'] . "' under '" . $parent['title'] . "'.";
                    }
                }
            }

            if ($count > 0) {
                $this->getUser()->setFlash('notice', sprintf("Tree order updated, moved %s item%s:" . $flash, $count, ($count > 1 ? 's' : '')));
            } else {
                $this->getUser()->setFlash('error', "You must at least move one item to update the tree order");
            }
        } catch (sfValidatorError $e) {
            $this->getUser()->setFlash('error', 'Cannot update the tree order, maybe some item are deleted, try again');
        }

        $this->redirect('@cms_info');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

        $object = $this->getRoute()->getObject();
        if ($object->getNode()->isValidNode()) {
            $object->getNode()->delete();
        } else {
            $object->delete();
        }

        $this->getUser()->setFlash('notice', 'The item was deleted successfully.');

        $this->redirect('@cms_info');
    }

    public function executeListNew(sfWebRequest $request) {
        $this->executeNew($request);
        $this->form->setDefault('parent_id', $request->getParameter('id'));
        $this->setTemplate('edit');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $this->getUser()->setFlash('notice', $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.');

            $tree = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $tree)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $this->getUser()->getFlash('notice') . ' You can add another one below.');

                $this->redirect('@cms_info_new');
            } else {
                $this->redirect('@cms_info_edit?id=' . $tree['id']);
            }
        } else {
//            foreach($form->getWidgetSchema()->getPositions() as $widgetName)
//            {
//                var_dump($form[$widgetName]->getValue());
//                var_dump($form[$widgetName]->getError());
//            }
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.');
        }
    }

    public function executeUpdateroute(sfWebRequest $request) {
        $parentId = $request->getPostParameter('parentId');
        $title = $request->getPostParameter('title');
        $title = Doctrine_Inflector::urlize($title);
        $parentRoute = '';

        $route = array();
        if (!empty($parentId)) {
            //fetch parents
            $Tree = Doctrine_Core::getTable("CmsInfo")->find($parentId);
            if ($Tree) {
                $ancestors = $Tree->getNode()->getAncestors();
                if ($ancestors) {
                    foreach ($ancestors as $ancestor) {
                        $route[] = Doctrine_Inflector::urlize($ancestor->__toString());
                    }
                }
                $route[] = Doctrine_Inflector::urlize($Tree->getNode()->getRecord()->title);
            }
            $parentRoute = join('/', $route) . '/';
        }

        if ($request->isXmlHttpRequest()) {
            return $this->renderText($parentRoute . $title);
        }
    }

}
