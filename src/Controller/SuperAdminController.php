<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ContenuPanierRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/{_locale}/superadmin")
 */
class SuperAdminController extends AbstractController
{
    //recuperation des infos des differentes tables pour l'affichage des infos
    /**
     * @Route("/", name="super_admin")
     */
    public function index(PanierRepository $panierRepository,ContenuPanierRepository $contenuPanierRepository)
    { $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll(array(), array('id'=>'DESC'));

        return $this->render('super_admin/index.html.twig', [
            'users' => $users,
            'paniers' => $panierRepository->findAll(),
            'contenu_paniers' => $contenuPanierRepository->findAll(),

        ]);
    }
}
