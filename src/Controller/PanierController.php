<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\ContenuPanier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     */
    public function index(PanierRepository $panierRepository): Response
    {
        //search for a cart belonging to the user with a state == false
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findOneBy(['user'=> $this->getUser(), 'state'=> false]),
        ]);
    }


    /**
     * @Route("/panier/buy",name="buy")
     */
    public function buy(PanierRepository $panierRepository)
    {
         //search for a cart belonging to the user with a state == false
        $panier = $panierRepository ->findOneBy(['user' => $this->getUser(), 'state' => false]);
        $entityManager = $this->getDoctrine()->getManager();

        //pass the 'state' to true and add the time at 'boughtAt'
        $panier
            ->setState(true)
            ->setBoughtAt(new \DateTime());
        $entityManager->persist($panier);
        $entityManager->flush();
        $this->addFlash("success", "Done"); 
        
        return $this->redirectToRoute('produit_index');
    }
}
