<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RemoteMailApiV1Controller extends AbstractController
{
    public function sendMail(Request $request)
    {
        $data=$request->request->all();
        $result=$authApiService->createExternalService($data);
        return $this->json($result->toArray());
    }
}
