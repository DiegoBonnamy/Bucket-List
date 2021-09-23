<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Http\HttpFoundation;
use App\Repository\WishRepository;
use App\Entity\Wish;
use App\Form\WishType;

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
        return $this->render('details/index.html.twig', [
            'wish' => $wish
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Wish $wish): Response
    {
        $emi = $this->getDoctrine()->getManager();
        $emi->remove($wish);
        $emi->flush();

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Wish $wish): Response
    {
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $emi = $this->getDoctrine()->getManager();
            $emi->persist($wish);
            $emi->flush();
            return $this->redirectToRoute("home");
        }
        else{
            return $this->render('add/index.html.twig',
                ['form' => $form->createView()]
            );
        }
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $emi = $this->getDoctrine()->getManager();
            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime());
            $emi->persist($wish);
            $emi->flush();
            return $this->redirectToRoute("home");
        }
        else{
            return $this->render('add/index.html.twig',
                ['form' => $form->createView()]
            );
        }
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('about/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig');
    }
}
