<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte/{id}", name="compte")
     */
    public function index(User $user, Request $request,$id, PanierRepository $panierRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findOneById($id);

        return $this->render('compte/index.html.twig', [
            'user' => $users,
            'paniers' => $panierRepository->findOneBy(['user'=> $this->getUser()]),
        ]);

    }
}
