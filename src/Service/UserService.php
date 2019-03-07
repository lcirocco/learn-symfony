<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserService
{
    private $em;
    private $passwordEncoder;
    private $userTokenService;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserTokenService $userTokenService,
        UserRepository $userRepository
    )
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userTokenService = $userTokenService;
        $this->userRepository = $userRepository;
    }

    public function createUserByUsernameAndPassword(string $username, string $password): User
    {
        $user = (new User())
            ->setUsername($username)
            ->setRoles(['ROLE_USER'])
        ;
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (Exception $ex) {
            // todo: catch unique exception for username
        }

        return $user;
    }

    public function createToken($username, $password)
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($user === null) {
            throw new EntityNotFoundException('user not found');
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new AuthenticationException('Invalid password');
        }

        $token = $this->userTokenService->getValidToken($user);

        return $token === null
            ? $this->userTokenService->createToken($user)
            : $token;
    }
}