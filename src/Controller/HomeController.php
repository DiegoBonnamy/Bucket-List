<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Repository\WishRepository;
use Symfony\Entity\Wish;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(WishRepository $repo): Response
    {
        $wishes = $repo->findBy(
            ['isPublished' => true],
            ['dateCreated' => 'ASC']
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'wishes' => $wishes
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Wish $wish): Response
    {
        return $this->render('home/details.html.twig', [
            'wish' => $wish
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }
}
