<?php

namespace App\Controller\Api;


use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\UserTokenService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @Route("/api/users")
 */
class UserController extends AbstractController
{
    private $userRepository;
    private $userService;
    private $userTokenService;

    public function __construct(
        UserRepository $userRepository,
        UserService $userService,
        UserTokenService $userTokenService
    )
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
        $this->userTokenService = $userTokenService;
    }

    /**
     * @Route("/authentication", name="user.log_in", methods="POST")
     */
    public function authentication(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);
        $username = $data['username'];
        $password = $data['password'];
        try {
            $token = $this->userService->createToken($username, $password);
            return $this->json($token);
        } catch(EntityNotFoundException $e) {
            return $this->json(['message' => $e->getMessage()], 404);
        }
        catch(AuthenticationException $e) {
            return $this->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * @Route("/", name="create_user", methods="POST")
     */
    public function createUser(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);

        $this->userService->createUserByUsernameAndPassword($data['username'], $data['password']);

        return $this->json(null, 201);
    }

    /**
     * @Route("/refresh_token", name="refresh_token", methods="POST")
     */
    public function refreshToken(Request $request)
    {
        $data = \json_decode($request->getContent(), true);

        $token = $this->userTokenService->userRefreshToken($data);

        return $this->json($token, 400);
    }
}