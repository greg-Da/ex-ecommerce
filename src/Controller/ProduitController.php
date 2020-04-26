<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Form\ContenuPanierType;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        //search and display all product
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/produit/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //create a new product 
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        // if the form is valid it will persist
        if ($form->isSubmitted() && $form->isValid()) {

            // if there is a picture process the information and create it s name
            $fichier = $form->get('photoupload')->getData();
            if ($fichier){
                $nomFichier= uniqid().'.'.$fichier->guessExtension();
                // move the picture to the right repositery
                try {
                    $fichier->move(
                        $this->getParameter('upload_dir'),
                        $nomFichier
                    );
                }
                //in case of an error it will display a message
                catch (FileException $e){
                    $this->addFlash('danger', "Impossiple d'uploader le fichier");
                    return $this->redirectToRoute('produit_index');
                }
                $produit->setPhoto($nomFichier);
            }
            try{
              $entityManager->persist($produit);  // prepare
              $entityManager->flush();            // execute
              $this->addFlash("success", "Produit created");
              return $this->redirectToRoute('produit_index');
                //in case of an error it will display a message
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
     * @Route("/produit/{id}", name="produit_show", methods={"GET","POST"})
     */
    public function show($id,ProduitRepository $repo,ContenuPanier $contenuPanier = null,Request $request, Produit $produit, PanierRepository $panierRepository,TranslatorInterface $translator): Response
    {
        $manager = $this->getDoctrine()->getManager();
        //search for the article
        $product = $repo->find($id);
        //search for the cart of the user
        $panier = $panierRepository->findOneBy(['user'=> $this->getUser(), 'state'=>false]);
        
        //if the user doesn t have a cart we create it for him
        if ($panier == null){
            $panier = new Panier();
            $panier->setUser($this->getUser());
            $manager->persist($panier);
        }

        //if the user doesn t have a cart-slot for the article we create it for him
        if ($contenuPanier == null ) {
            $contenuPanier = new ContenuPanier;
        }
       
        //create the form
        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form -> handleRequest($request);

        //if the form is valid we set the date and link the cart slot to the cart
        if ($form->isSubmitted() && $form->isValid()) {

            $contenuPanier->setAddedAt(new \DateTime)
                          ->setPanier($panier);
            $product->addContenuPanier($contenuPanier);
            $manager->persist($contenuPanier);
            $manager->flush();
            $this->addFlash("success", $translator->trans('produit.add'));
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'formCart' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/edit/{id}", name="produit_edit", methods={"GET","POST"})
     */
    public function modifproduit(Produit $produit=null, Request $request,TranslatorInterface $translator){
        if ($produit !=null){
            $form= $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);
            // if the form is valid it will persist
        if ($form->isSubmitted() && $form->isValid()) {

            // if there is a picture process the information and create it s name
                $fichier = $form->get('photoupload')->getData();
                if ($fichier){
                    $nomFichier= uniqid().'.'.$fichier->guessExtension();
                // move the picture to the right repositery
                    try {
                        $fichier->move(
                            $this->getParameter('upload_dir'),
                            $nomFichier
                        );
                    }
                //in case of an error it will display a message
                    catch (FileException $e){
                        $this->addFlash('danger', $translator->trans('produit.impossible'));
                        return $this->redirectToRoute('produit_index');
                    }
                    $produit->setPhoto($nomFichier);
                }
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($produit);
                $pdo->flush();
                $this->addFlash("success", $translator->trans('produit.modify'));

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
    public function delete(Request $request, Produit $produit,TranslatorInterface $translator): Response
    {
        //try to delete the contenuPanier
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->remove($produit);
              $entityManager->flush();
              $this->addFlash("success", $translator->trans('produit.delete'));
            }catch(\Exception $e){
                error_log($e->getMessage());
            }
        }

        return $this->redirectToRoute('produit_index');
    }
}
