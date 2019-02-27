<?php

namespace App\Controller\Api;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/authentication", name="user.log_in", methods="POST")
     */
    public function authentication(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['username' => $data['username'], 'password' => $data['password']]);

        if($user === null)
        {
            return $this->json(['message' => 'Incorrect username or password'], 404);
        }


        return $this->json($user,200);
    }
}