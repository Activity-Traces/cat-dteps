<?php

namespace App\Controller;

use App\Form\Search;
use App\Entity\Evaluation;
use App\Form\EvaluationUpdate;
use App\Records;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class EvaluationController extends AbstractController
{


    #**************************************************************************************************************************************#
    /**
     * @Route("/evaluations", name="EGetAll")
     */

    public function EGetAll(
        Request $request,
        EvaluationRepository $rep,
        UserInterface $user

    ) {

        $service = new Records;
        $form =  $this->createForm(Search::class);
        $result = $service->GetRecords($form, $request, $rep, $user);

        return $this->render('evaluation/getAll.html.twig', ['result' => $result, 'form' => $form->createView()]);
    }


    #**************************************************************************************************************************************#

    /** View one Evaluation using it's identifier
     * 
     * @Route("/evaluation/voir/{id}", name="EGet") 
     */

    public function EGet(Evaluation $evaluation)

    {
        return $this->render(
            'evaluation/get.html.twig',
            [
                'evaluation' => $evaluation,
            ]
        );
    }


    #**************************************************************************************************************************************#

    /** Add a new Evaluation
     * 
     * @Route("/evaluation/ajouter", name="EAdd")
     */

    public function EAdd(
        EntityManagerInterface $manager,
        Request $request,
        UserInterface $user
    ) {


        $Evaluation_Record = new Evaluation();
        $form = $this
            ->createForm(EvaluationUpdate::class,  $Evaluation_Record);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            //echo "ici";

            $Evaluation_Record->setHasUser($user);

            $manager->persist($Evaluation_Record);
            $manager->flush();
            return $this->redirectToRoute('EGetAll');
        }

        return $this->render(
            'evaluation/update.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Add'
            ]
        );
    }


    #**************************************************************************************************************************************#

    /** To edit Evaluation or a competence
     * 
     * @Route("/evaluation/editer/{id}", name="EEdit")
     */

    public function EEdit(
        EntityManagerInterface $manager,
        Evaluation $record,
        Request $request,
        $id
    ) {

        $form = $this
            ->createForm(EvaluationUpdate::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($record);
            $manager->flush();
            return $this->redirectToRoute('EGetAll');
        }

        return $this->render(
            'evaluation/update.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Edit'
            ]
        );
    }

    #**************************************************************************************************************************************#

    /** Delete one Evaluation
     * 
     * @Route("/evaluation/supprimer/{id}", name="EDelete")
     */

    public function EDelete(
        EntityManagerInterface $manager,
        Evaluation $record,
        $id
    ) {

        $manager->remove($record);
        $manager->flush();
        return $this->redirectToRoute('EGetAll');
    }

    #**************************************************************************************************************************************#

    /** Delete all Evaluation
     * 
     * @Route("/evaluation/toutsupprimer", name="EDeleteAll")
     */

    public function EDeleteAll(
        EvaluationRepository $rep,
        UserInterface $user

    ) {

        $rep->deleteAll($user);
        return $this->redirectToRoute("EGetAll");
    }

    #**************************************************************************************************************************************#
    /**
     * @Route("/evaluation/partager/{id}", name="EAllow")
     */

    public function EAllow(
        EntityManagerInterface $manager,
        Evaluation $record,
        $id
    ) {

        //  $record->setVerou(!($record->getVerou()));
        //  $manager->persist($record);
        // $manager->flush();

        return $this->redirectToRoute('EGetAll');
    }


    #**************************************************************************************************************************************#
    #**************************************************************************************************************************************#
    /* CLASS END */
}
