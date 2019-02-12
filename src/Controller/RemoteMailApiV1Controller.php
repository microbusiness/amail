<?php

namespace App\Controller;

use App\Document\InternalResult;
use App\Service\MailJobService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RemoteMailApiV1Controller extends AbstractController
{
    public function sendMail(Request $request, MailJobService $mailJobService)
    {
        $data=$request->request->all();
        if (array_key_exists('data',$data)) {
            $result=$mailJobService->createMailJob($data['data'],$this->getUser());
            return $this->json($result->toArray());
        } else {
            $result=new InternalResult();
            $result->setBadMessage('Not data in request');
            return $this->json($result->toArray());
        }


    }

    public function checkMailPixel()
    {
        header('Content-Type: image/gif');
        echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');

    }

}
