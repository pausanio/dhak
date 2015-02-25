<?php

/**
 * meinArchiv actions.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage meinArchiv
 * @author     Maik Mettenheimer <mettenheimer@pausanio.de>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class meinArchivActions extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->verzeichnungseinheiten = null;
        $this->dokumente = null;
        $this->archiv = null;
        if ($this->getUser()->getGuardUser()) {
            $this->verzeichnungseinheiten = $this->getUser()->getGuardUser()->getMyVerzeichnungseinheit();
            $this->dokumente = $this->getUser()->getGuardUser()->getMyDokumente();
            $this->archiv = $this->getUser()->getGuardUser()->getMyArchiv();
        }
    }

    public function executeSave(sfWebRequest $request)
    {
        try {
            $rawJson = $request->getContent();
            $json = json_decode($rawJson);
            $userId = $this->getUser()->getGuardUser()->getId();
            if ($json->type == Bookmarks::TYPE_VE) {
                $bookmark = MyVerzeichnungseinheitTable::findByUserAndId($userId, $json->id);
                if (!$bookmark) {
                    $bookmark = new MyVerzeichnungseinheit();
                    $bookmark->setUser($this->getUser()->getGuardUser());
                    $bookmark->verzeichnungseinheit_id = (int)$json->id;
                    $bookmark->save();
                }
            } else {
                $bookmark = MyArchivTable::findByUserAndId($userId, $json->id);
                if (!$bookmark) {
                    $bookmark = new MyArchiv();
                    $bookmark->setUser($this->getUser()->getGuardUser());
                    $bookmark->archiv_id = (int)$json->id;
                    $bookmark->save();
                }
            }
            $this->getResponse()->setStatusCode(201);
            $this->getResponse()->setContentType('application/json');
            return $this->renderText(json_encode(array('id' => $bookmark->id)));
        } catch (\Exception $e) {
            $this->getResponse()->setStatusCode(500);
        }
        return sfView::HEADER_ONLY;
    }

    public function executeDelete(sfWebRequest $request)
    {
        try {
            $rawJson = $request->getContent();
            $json = json_decode($rawJson);
            //secure user
            $userId = $this->getUser()->getGuardUser()->getId();
            if ($json->type == Bookmarks::TYPE_VE) {
                $q = Doctrine_Query::create()
                    ->delete('MyVerzeichnungseinheit')
                    ->addWhere('created_by = ?', $userId)
                    ->andWhere('id = ?', (int)$json->id)
                    ->execute();
            } elseif ($json->type == Bookmarks::TYPE_ARCHIV) {
                $q = Doctrine_Query::create()
                    ->delete('MyArchiv')
                    ->addWhere('created_by = ?', $userId)
                    ->andWhere('id = ?', (int)$json->id)
                    ->execute();
            } else {
                $q = Doctrine_Query::create()
                    ->delete('MyDokumente')
                    ->addWhere('created_by = ?', $userId)
                    ->andWhere('id = ?', (int)$json->id)
                    ->execute();
            }
            if ($q == 0) {
                throw new \Exception('Couldnt delete');
            }
            $this->getResponse()->setStatusCode(204);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->getResponse()->setStatusCode(500);
        }
        return sfView::HEADER_ONLY;
    }
}
