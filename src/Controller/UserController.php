<?php

namespace App\Controller;

use App\Security\AuthApiV1Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function login()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    public function registrExternalService(Request $request,AuthApiV1Service $authApiService)
    {
        $data=$request->request->all();
        $result=$authApiService->createExternalService($data);
        return $this->json($result->toArray());
    }
}
