<?php

#**************************************************************************************************************************************#

namespace App\Controller;

use App\Entity\User;
use App\Form\Search;
use App\Form\Registration;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#**************************************************************************************************************************************#

class ConnexionController extends AbstractController
{
    #**************************************************************************************************************************************#
    /**
     * @Route("/inscription", name="Registration")
     */

    public function Registration(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordEncoderInterface $encoder,
        UserRepository $rep

    ) {

        $user = new User();
        $form = $this->createForm(Registration::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email  = $rep->findOneBy(['email' => $form['email']->getData()]);

            if (empty($email)) {

                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $user->setCanAccess(0);
                $manager->persist($user);
                $manager->flush();

                $message = 'Votre compte a été crée avec sucess. Votre administrateur vas vous contacter pour activer votre compte ';
            } else

                $message = 'Ce compte existe. Merci de changer votre identifiant ';

            return $this->render('security/Messages.html.twig', ['message' => $message]);
        }

        return $this->render('security/Registration.html.twig', ['form' => $form->createView()]);
    }

    #**************************************************************************************************************************************#
    // 
    /**
     * @Route("/connexion", name="Login")
     */

    public function Login()
    {

        return $this->render('security/Login.html.twig');
    }

    #**************************************************************************************************************************************#
    /**
     * @Route("/deconnexion", name="Logout")
     */
    public function Logout()
    {

        return $this->render('security/Login.html.twig');
    }

    #**************************************************************************************************************************************#

    /**
     * @Route("/utilisateurs/autoriser/{id}", name="UAllow")
     */

    public function Allow(
        EntityManagerInterface $manager,
        UserRepository $rep,
        $id
    ) {

        $user = new User;
        $user = $rep->findOneBy(['id' => $id]);
        $user->setCanAccess(!($user->getCanAccess()));

        $manager->persist($user);
        $manager->flush();

        $directory = $this->getParameter('resources_directory') . '/' . $user->getEmail();

        if (!(file_exists($directory)))
            mkdir($directory);


        unset($user);



        return $this->redirectToRoute('Users');
    }

    #**************************************************************************************************************************************#
    /**
     * @Route("/utilisateurs", name="Users")
     */
    public function Users(
        EntityManagerInterface $manager,
        Request $request,
        UserRepository $rep
    ) {

        $form =  $this->createForm(Search::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $search = $form['search']->getData();
            if ($search == null)
                $result = $rep->findAll();
            else
                $result = $rep->findByEmail($search);
        } else

            $result = $rep->findAll();
        return $this->render('security/getAll.html.twig', ['result' => $result, 'form' => $form->createView()]);
    }
    #**************************************************************************************************************************************#
    /**
     * @Route("/utilisateurs/supprimer/{id}", name="UDelete")
     */
    public function Delete(
        EntityManagerInterface $manager,
        User $User
    ) {

        $manager->remove($User);
        $manager->flush();
        return $this->redirectToRoute('Users');
    }


    #**************************************************************************************************************************************#
    /**
     * @Route("/utilisateurs/toutsupprimer/", name="UDeletes")
     */

    public function DeleteAll(EntityManagerInterface $manager)
    {

        return $this->redirectToRoute('Users');
    }

    #**************************************************************************************************************************************#
}
