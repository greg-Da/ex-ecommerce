<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Form\ContenuPanierType;
use App\Repository\ContenuPanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/contenu/panier")
 */
class ContenuPanierController extends AbstractController
{

    /**
     * @Route("/{id}", name="contenu_panier_show", methods={"GET"})
     */
    public function show(ContenuPanier $contenuPanier): Response
    {
        return $this->render('contenu_panier/show.html.twig', [
            'contenu_panier' => $contenuPanier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contenu_panier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContenuPanier $contenuPanier): Response
    {
        //create a form to edit the content of the cart
        $form = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $form->handleRequest($request);
        
        //check if the form is valid and process with the modification
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contenu_panier_index');
        }

        return $this->render('contenu_panier/edit.html.twig', [
            'contenu_panier' => $contenuPanier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contenu_panier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContenuPanier $contenuPanier): Response
    {
        //try to delete the contenuPanier
        if ($this->isCsrfTokenValid('delete'.$contenuPanier->getId(), $request->request->get('_token'))) {
            try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->remove($contenuPanier);
              $entityManager->flush();
        //if it fails, a error message will be displayed
            }catch(\Exception $e){
                error_log($e->getMessage());
            }
        }

        return $this->redirectToRoute('panier_index');
    }
}
