<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */

class CompteController extends AbstractController
{
    /**
     * @Route("/compte/{id}", name="compte")
     */
    public function index(User $user, Request $request,$id, PanierRepository $panierRepository)
    {
        //recuperation du l'id du user pour recuperer les bonnes infos
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findOneById($id);

        return $this->render('compte/index.html.twig', [
            'user' => $users,
            'paniers' => $panierRepository->findOneBy(['user'=> $this->getUser()]),
        ]);

    }
}
