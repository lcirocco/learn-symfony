<?php
/**
 * Created by PhpStorm.
 * User: teo
 * Date: 25/02/19
 * Time: 13:18
 */

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function userRefreshToken(string $oldToken)
    {
        $user = $this->userRepository->findOneBy(['apiToken' => $oldToken]);
        $token = md5(uniqid(time()));
        $user->setExpireAt(strtotime("+3 minutes"));
        $user->setApiToken($token);
        $this->entityManager->flush();
    }

    public function checkExpiration(string $oldToken)
    {
        $user = $this->userRepository->findOneBy(['apiToken' => $oldToken]);
        if($user->getExpireAt()<time()){
            return true;
        }
        return false;
    }
}