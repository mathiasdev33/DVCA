<?php

namespace App\Controller\admin;

use App\Controller\MaestroController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @IsGranted ("ROLE_ADMIN")
 */

class ChangePasswordController extends MaestroController
{

    /**
     * @Route("/password", name="admin_change_password")
     * 
     * Change password to current user
     */

    public function change(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if($request->isMethod("POST")){
            $post = $this->getFormHTML($_POST);

            $user->setPassword($this->hash($post['password']));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("success","Mot de passe modifié");
            return $this->redirectToRoute('admin_home');

        }

        return $this->render("admin/password/change.html.twig");

    }

}
