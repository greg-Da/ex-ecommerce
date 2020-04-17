<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Form\ContenuPanierType;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/produit/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            try{
              $entityManager->persist($produit);  // prepare
              $entityManager->flush();            // execute
              $this->addFlash("success", "Produit created");
              return $this->redirectToRoute('produit_index');
          }catch(\Exception $e){
            error_log($e->getMessage());

            return $this->redirectToRoute('produit_new');
        }
    }
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produit/{id}", name="produit_show", methods={"GET"})
     */
    public function show($id,ProduitRepository $repo,ContenuPanier $contenuPanier = null,Request $request, Produit $produit): Response
    {
        $product = $repo->find($id);

        if ($contenuPanier == null ) {
            $contenuPanier = new ContenuPanier;
        }
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contenuPanier->setAddedAt(new \DateTime);
            $product->setContenuPanier($contenuPanier);
            $manager->persist($contenuPanier);
            $manager->flush();
            $this->addFlash("success", "Product added");        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/produit/edit/{id}", name="produit_edit", methods={"GET","POST"})
     */
    public function modifproduit(Produit $produit=null, Request $request){
        if ($produit !=null){
            $form= $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($produit);
                $pdo->flush();
                $this->addFlash("success", "Produit Modifié");

            }

            return $this->render('produit/edit.html.twig', [
                'produit' => $produit,
                'form'=>$form -> createView()

            ]);
        }

        else{
            return $this->redirectToRoute('produit_index');
        }
    }

    /**
     * @Route("/produit/{id}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->remove($produit);
              $entityManager->flush();
              $this->addFlash("success", "Produit deleted");
            }catch(\Exception $e){
                error_log($e->getMessage());
            }
        }

        return $this->redirectToRoute('produit_index');
    }
}
