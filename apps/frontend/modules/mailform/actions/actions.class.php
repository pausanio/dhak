<?php

/**
 *
 * @author  Norman Fiedler / Maik Mettenheimer
 */
class mailformActions extends myActions
{

  public function executeIndex(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $request_type = $request->getParameter('type');
      if ($request_type === 'project') {
        $data = HaProjekte::GetProjektDataById($request->getParameter('id'));
        $from = $this->getUser()->getGuardUser()->getEmailAddress();
        //$rcpt = $data['SfGuardUser']['email_address'];
        $rcpt = $data['projekt_einsteller'];
        $subject = $data['projekt_title_de'];
      }
      return $this->renderPartial('form', array('type' => $request_type, 'id' => $request->getParameter('id'),
            'from' => $from, 'rcpt' => $rcpt, 'subject' => $subject));
    } else {

    }
  }

  public function executeSend(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $msg = '';
      $msg .= $request->getParameter('type') . "<br>";
      $msg .= $request->getParameter('id') . "<br>";
      $msg .= $request->getParameter('from') . "<br>";
      $msg .= $request->getParameter('subject') . "<br>";
      $msg .= $request->getParameter('text') . "<br>";
      //return $this->renderText($msg);
      $request_type = $request->getParameter('type');

      if ($request_type === 'project') {
        $data = HaProjekte::GetProjektDataById($request->getParameter('id'));
        $from_email = $request->getParameter('from');
        $rcpt_name = $data['projekt_einsteller'];
        $rcpt_email = $data['SfGuardUser']['email_address'];
        $subject = $request->getParameter('subject');
        $text = $request->getParameter('text');
      }

      if ($request_type === 'faq') {
        $result = array('success' => true,
          'errors' => array('mail_email' => false, 'mail_name' => false, 'mail_comment' => false));

        if (strlen(trim($request->getParameter('from'))) == 0) {
          $result['errors']['mail_email'] = true;
          $result['success'] = false;
        } else {
          $from_email = $request->getParameter('from');
          $v = new sfValidatorEmail();
          try {
            $from_email = $v->clean($from_email);
          } catch (sfValidatorError $e) {
            $result['errors']['mail_email'] = true;
            $result['success'] = false;
          }
        }

        if (strlen(trim($request->getParameter('subject'))) == 0) {
          $result['errors']['mail_name'] = true;
          $result['success'] = false;
        } else {
          $subject = $request->getParameter('subject');
        }
        if (strlen(trim($request->getParameter('text'))) == 0) {
          $result['errors']['mail_comment'] = true;
          $result['success'] = false;
        } else {
          $text = $request->getParameter('text');
        }
        if (!$result['success']) {
          $result = json_encode($result);
          return $this->renderText($result);
        }
        /*      $rcpt_email = 'jcarl@uni-bonn.de';
          $rcpt_name = 'Janusch Carl'; */
        $rcpt_email = 'info@historischesarchivkoeln.de';
        $rcpt_name = 'www.historischesarchivkoeln.de';
        $subject = "DHAK: Frage von $subject";
      }


      //$rcpt_email = 'nf@n-data.de';
      $message = $this->getMailer()->compose(
          $from_email, array($rcpt_email => $rcpt_name), $subject, $text);
      $message->addBcc('dhak@n-data.de');
      $send_success = $this->getMailer()->send($message);

      if ($request_type === 'faq') {
        $result['success'] = $send_success;
        $result = json_encode($result);
        return $this->renderText($result);
      }

      if ($send_success) {
        $msg = "Ihre Nachricht wurde versendet";
      }
      return $this->renderText($msg);
    } else {

    }
  }

}