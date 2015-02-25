<?php
/**
 *
 * @author Norman Fiedler / Maik Mettenheimer
 */
class uploadActions extends myActions
{

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new HaDocumentsForm();
    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new HaDocumentsForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
    $this->getRequest()->setAttribute('content_wrapper_class', 'noBg');
  }
  public function executeShow(sfWebRequest $request)
  {
    //echo
  }
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid())
    {
      $document = $form->save();

      $this->redirect($this->generateUrl('lesesaal', array('module' => 'archive','action' => 'index', 'tekt_nr'=>$document->getTektNr(), 'bestand_sig'=>$document->getBestandSig(),'ve_sig'=>urlencode($document->getVeSignatur()))));
    } else {
    }
  }
}