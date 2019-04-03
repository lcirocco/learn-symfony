<?php
/**
 * Created by PhpStorm.
 * User: teo
 * Date: 25/02/19
 * Time: 13:18
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\UserToken;
use App\Repository\UserRepository;
use App\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserTokenService
{
    private $entityManager;
    private $userTokenRepository;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserTokenRepository $userTokenRepository,
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userTokenRepository = $userTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function createToken(User $user)
    {
        $token = new UserToken();
        $token->setUser($user)
            ->setToken($this->generateStringToken())
            ->setRefreshToken($this->generateStringToken())
            ->setCreatedAt(new \DateTime())
            ->setExpiresAt(strtotime("+60 minutes"));

        $this->entityManager->persist($token);

        $this->entityManager->flush();

        return $token;
    }

    public function getValidToken(User $user): ?UserToken
    {
        $token = $this->userTokenRepository->findOneBy(['user' => $user->getId()], ['createdAt' => 'DESC']);
        if ($token !== null && $token->getRefreshedAt() === null && $token->getExpiresAt() > time()) {
            return $token;
        }

        return null;
    }

    public function userRefreshToken(array $data): UserToken
    {
        $user = $this->userRepository->findOneBy(['username' => $data['user']]);
        $token = $this->userTokenRepository->findOneBy(['token' => $data['token']]);

        if($token !== null && $token->getRefreshedAt() === null)
        {
            $token->setRefreshedAt(new \DateTime());
            $token = $this->createToken($user);
        }

        return $token;
    }

    public function checkExpiration(string $oldToken)
    {
        /*
        $user = $this->userRepository->findOneBy(['apiToken' => $oldToken]);
        if($user->getExpireAt()<time()){
            return true;
        }
        return false;
        */
    }

    private function generateStringToken()
    {
        return md5(uniqid(time()));
    }
}