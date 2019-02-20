<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(UserRepository $userRepository)
    {
        $number = random_int(0, 100);

        return $this->render('number.html.twig', [
            'number' => $number,
        ]);
    }

    /**
     * @Route("/list")
     */
    public function list(LoggerInterface $logger)
    {

    }

    /**
     * @Route("/blog/{slug}", name="slug")
     */
    public function show($slug)
    {
        return $this->render('number.html.twig', [

            'number' => $slug,
        ]);
    }

}
