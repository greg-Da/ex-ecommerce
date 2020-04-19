<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/{_locale}/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/editrole/{id}", name="editrole")
     */
    public function editrole(User $user = null){
        if ($user == null){
            $this->addFlash('error', 'Utilisateur introuvable');
            return $this->redirectToRoute('produit_index');
        }

        if ($user->hasRole('ROLE_ADMIN')){
            $user->setRoles(['ROLE_USER','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        }
        elseif ($user->hasRole(['ROLE_USER','ROLE_ADMIN'])){
            $user->setRoles(['ROLE_USER']); ;
        }
        elseif ($user->hasRole(['ROLE_USER','ROLE_ADMIN','ROLE_SUPER_ADMIN'])){
            $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        }
        else{
            $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Role modifié');
        return $this->redirectToRoute('admin');
    }
}
