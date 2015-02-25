<?php

class viewerActions extends myActions
{

    public function executeImage(sfWebRequest $request)
    {
        $image = $request->getParameter('image');
        $signatur = $request->getParameter('signatur');
        $usergenerated = $request->getParameter('usergenerated');
        //secure
        $signatur = str_replace('../', '', $signatur);//TODO check if signatur is plausible
        $image = str_replace('../', '', $image) . '.jpg';
        $manipulated = false;
        try {
            if ($usergenerated == 1) {
                $appPath = 'app_dokument_user_filesystem';
                $appWeb = 'app_dokument_user_web';
                $imageName = Dokument::ORGDIR . '/' . $image;
            } else {
                $appPath = 'app_dokument_filesystem';
                $appWeb = 'app_dokument_web';
                $imageName = $signatur . '/' . Dokument::ORGDIR . '/' . $image;
            }

            $pathImage = sfConfig::get($appPath) . $imageName;
            $ret = array();
            if (file_exists($pathImage)) {
                $newPath = sfConfig::get($appPath) . $signatur . '/transformed/';
                if (!is_dir($newPath)) {
                    //create transformed folder
                    mkdir($newPath, 0777);
                }
                //TODO image filename with params
                //TODO cache image
                umask(0000);
                $newImageName = session_id() . '_' . $image;
                $newImage = $newPath . $newImageName;
                $queryString = $request->getParameterHolder()->getAll();
                $convertString = $this->createConvertCommand($queryString);
                if (!empty($convertString)) {
                    //echo '[42] convert ' . $pathImage . $convertString . ' ' . $newImage;  die();
                    $exec = exec('convert ' . $pathImage . $convertString . ' ' . $newImage);
                    if (file_exists($newImage)) {
                        $manipulated = TRUE;
                        if (isset($queryString['rotate']) && $queryString['rotate'] != 0) {
                            $dimensions = getimagesize($newImage);
                            $ret['width'] = $dimensions[0];
                            $ret['height'] = $dimensions[1];
                        }
                    } else {
                        throw new \Exception('image creation failed 51: '. $exec);
                    }
                }
                //if grid is set apply after other manipulations
                if (isset($queryString['grid']) && $queryString['grid'] == 'true') {
                    $infile = $pathImage;
                    if ($manipulated) {
                        $infile = $newImage;
                    }
                    $gridCommand = 'cd ../scripts && sh imagemagick.grid.sh -o 0.4 ' . $infile . ' ' . $newImage;
                    $exec = exec($gridCommand, $r);
                    //echo '[62] ' . $gridCommand . ' - r: ' . $r; die();
                    $manipulated = TRUE;
                    if (file_exists($newImage)) {
                        $manipulated = TRUE;
                    } else {
                        throw new \Exception('image grid creation failed 66: ' . $exec);
                    }
                }
                if ($manipulated) {
                    $webImage = sfConfig::get($appWeb) . $signatur . '/transformed/' . $newImageName;
                    $ret['path'] = $webImage;
                } else {
                    //not modified
                    $this->setLayout(false);
                    $this->getResponse()->setStatusCode('304');
                    return $this->renderText('');
                }
            } else {
                throw new \Exception('image not found');
            }
        } catch (\Exception $exc) {
//            var_dump($exc);
            $this->setLayout(false);
            $this->getResponse()->setStatusCode('500');
            return $this->renderText($exc->getMessage());
        }
        $this->setLayout('json');
        $this->getResponse()->setContentType('application/json');
        return $this->renderText(json_encode($ret));
    }

    public function executeSave(sfWebRequest $request)
    {
        try {
            $rawJson = $request->getContent();
            $json = json_decode($rawJson);
            if (!empty($json)) {
                $userId = $json->user;
                if ($userId != $this->getUser()->getGuardUser()->getId()) {
                    throw new Exception('User ID is probably not correct');
                }
                $veId = $json->veid;
                $dokId = $json->dokumentid;
                if (!empty($veId) && $json->saveMode == 've') {
                    $bookmark = MyVerzeichnungseinheitTable::findByUserAndId($userId, $veId);
                    if(!$bookmark){
                        $bookmark = new MyVerzeichnungseinheit();
                        $bookmark->setUser($this->getUser()->getGuardUser());
                        $bookmark->verzeichnungseinheit_id = (int)$veId;
                    }
                }
                else{
                    $bookmark = MyDokumenteTable::findByUserAndId($userId, $dokId);
                    if(!$bookmark){
                        $bookmark = new MyDokumente();
                        $bookmark->setUser($this->getUser()->getGuardUser());
                        $bookmark->dokument_id = (int)$dokId;

                    }
                }
                $bookmark->viewer_settings = $rawJson;
                $bookmark->save();
                $this->getResponse()->setStatusCode(201);
                $this->getResponse()->setContentType('application/json');
                return $this->renderText(json_encode(array('id' => $bookmark->id)));
            }
        } catch (\Exception $e) {
            $this->getResponse()->setStatusCode(500);
        }
        $this->getResponse()->setStatusCode(204);
        return sfView::HEADER_ONLY;
    }

    public function executeSaveComments(sfWebRequest $request)
    {
        try {
            $rawJson = $request->getContent();
            $json = json_decode($rawJson);
            $statuscode = 204;
            if (!empty($json)) {
                $userId = $this->getUser()->getGuardUser()->getId();
                if (!empty($json->veid)) {
                    $bookmark = MyVerzeichnungseinheitTable::findByUserAndId($userId, $json->veid);
                    if(!$bookmark){
                        $bookmark = new MyVerzeichnungseinheit();
                        $bookmark->setUser($this->getUser()->getGuardUser());
                        $bookmark->verzeichnungseinheit_id = (int)$json->veid;
                        $statuscode = 201;
                    }
                }
                elseif(!empty($json->dokumentid)){
                    $bookmark = MyDokumenteTable::findByUserAndId($userId, $json->dokumentid);
                    if(!$bookmark){
                        $bookmark = new MyDokumente();
                        $bookmark->setUser($this->getUser()->getGuardUser());
                        $bookmark->dokument_id = (int)$json->dokumentid;
                        $statuscode = 201;
                    }
                }
                else{
                    throw new \Exception('No related Dokument given');
                }
                $bookmark->personal_comments = $json->comments;
                $bookmark->save();
                $this->getResponse()->setStatusCode($statuscode);
                $this->getResponse()->setContentType('application/json');
                return $this->renderText(json_encode(array('id' => $bookmark->id)));
            }
        } catch (\Exception $e) {
            $this->getResponse()->setStatusCode(500);
        }
        $this->getResponse()->setStatusCode($statuscode);
        return sfView::HEADER_ONLY;
    }

    protected function createConvertCommand($queryString)
    {
        $command = '';
        $brightness = 0;
        $contrast = 0;
        foreach ($queryString as $key => $value) {
            switch ($key) {
                case 'invert':
                    if ($value == 'true') {
                        $command .= ' -negate';
                    }
                    break;
                case 'rotate':
                    if ($value != 'undefined') {
//                        $command .= ' -rotate ' . (int) $value;
                        $command .= ' -virtual-pixel background +distort ScaleRotateTranslate ' . (int)$value . ' +repage ';
                    }
                    break;
                case 'brightness':
                    if ($value != 'undefined' && $value != 0) {
                        $brightness = (int)$value;
                    }
                    break;
                case 'contrast':
                    if ($value != 'undefined' && $value != 0) {
                        $contrast = (int)$value;
//                        $prefix = '0x';
//                        $suffix = (int) $value;
//                        $suffix .= '%';
//                        if ($value < 0) {
//                            $suffix = 'x0';
//                            $value = substr($value, 1);
//                            $prefix = (int) $value;
//                            $prefix .= '%';
//                        }
//                        $command .= ' -contrast-stretch ' . $prefix . $suffix;
                    }
                    break;
                case 'saturation':
                    if ($value != 'undefined' && $value != 'false') {
                        $command .= ' -modulate 100,' . (int)$value . ',100 ';
                    }
                    break;
            }
        }
        if ($brightness != 0 || $contrast != 0) {
            $command .= ' -brightness-contrast ' . $brightness . 'x' . $contrast;
        }
        return $command;
    }

}
