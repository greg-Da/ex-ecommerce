<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/{_locale}/superadmin")
 */
class SuperAdminController extends AbstractController
{
    /**
     * @Route("/", name="super_admin")
     */
    public function index()
    { $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll(array(), array('id'=>'DESC'));

        return $this->render('super_admin/index.html.twig', [
            'users' => $users,
        ]);
    }
}
