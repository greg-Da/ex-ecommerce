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
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findOneBy(['user'=> $this->getUser(), 'state'=> false]),
        ]);
    }

    /**
     * @Route("/new", name="panier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_show", methods={"GET"})
     */
    public function show(Panier $panier, Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(ContenuPanier::class)->findBy($id);
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/{id}/edit", name="panier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Panier $panier): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContenuPanier $contenuPanier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contenuPanier->getId(), $request->request->get('_token'))) {
            try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->remove($contenuPanier);
              $entityManager->flush();
            }catch(\Exception $e){
                error_log($e->getMessage());
            }
        }

        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/panier/buy",name="buy")
     */
    public function buy(PanierRepository $panierRepository)
    {
        $panier = $panierRepository ->findOneBy(['user' => $this->getUser(), 'state' => false]);
        $entityManager = $this->getDoctrine()->getManager();

        $panier
            ->setState(true)
            ->setBoughtAt(new \DateTime());
        $entityManager->persist($panier);
        $entityManager->flush();
        
        return $this->redirectToRoute('produit_index');
    }
}
