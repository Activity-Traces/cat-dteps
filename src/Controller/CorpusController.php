<?php

namespace App\Controller;

use App\Records;

use App\Form\Search;
use App\Entity\Corpus;
use App\Form\CorpusUpdate;
use App\Repository\CorpusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#**************************************************************************************************************************************#
#**************************************************************************************************************************************#

class CorpusController extends AbstractController
{

    #**************************************************************************************************************************************#
    /**
     * @Route("/corpus", name="CGetAll")
     */

    public function CGetAll(
        Request $request,
        CorpusRepository $rep,
        UserInterface $user

    ) {

        $service = new Records;
        $form =  $this->createForm(Search::class);
        $result = $service->GetRecords($form, $request, $rep, $user);
        return $this->render('corpus/getAll.html.twig', ['result' => $result, 'form' => $form->createView()]);
    }


    #**************************************************************************************************************************************#

    /** View one corpus using it's identifier
     * 
     * @Route("/corpus/voir/{id}", name="CGet") 
     */

    public function CGet(Corpus $Corpus)

    {
        return $this->render('Corpus/get.html.twig', ['corpus' => $Corpus]);
    }


    #**************************************************************************************************************************************#

    /** Add a new corpus
     * 
     * @Route("/corpus/ajouter", name="CAdd")
     */

    public function CAdd(
        EntityManagerInterface $manager,
        Request $request,
        UserInterface $user
    ) {

        $Corpus_Record = new Corpus();
        $form = $this
            ->createForm(CorpusUpdate::class, $Corpus_Record);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $Corpus_Record->setHasUser($user);

            $Corpus_Record->setVerou('0');
            $Corpus_Record->setCreatedAt(new \DateTime('now'));

            $manager->persist($Corpus_Record);
            $manager->flush();
            return $this->redirectToRoute('CGetAll');
        }

        return $this->render(
            'corpus/update.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Add'
            ]
        );
    }


    #**************************************************************************************************************************************#

    /** To edit corpus or a competence
     * 
     * @Route("/corpus/editer/{id}", name="CEdit")
     */

    public function CEdit(
        EntityManagerInterface $manager,
        Corpus $record,
        Request $request,
        $id
    ) {

        $form = $this
            ->createForm(CorpusUpdate::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($record);
            $manager->flush();
            return $this->redirectToRoute('CGetAll');
        }

        return $this->render('corpus/update.html.twig', ['form' => $form->createView(), 'action' => 'Edit']);
    }

    #**************************************************************************************************************************************#

    /** Delete one corpus
     * 
     * @Route("/corpus/supprimer/{id}", name="CDelete")
     */

    public function CDelete(
        EntityManagerInterface $manager,
        Corpus $record
    ) {

        $manager->remove($record);
        $manager->flush();
        return $this->redirectToRoute('CGetAll');
    }

    #**************************************************************************************************************************************#

    /** Delete all corpus
     * 
     * @Route("/corpus/toutsupprimer", name="CDeleteAll")
     */

    public function CDeleteAll(
        CorpusRepository $rep,
        UserInterface $user

    ) {

        $rep->deleteAll($user);
        return $this->redirectToRoute("CGetAll");
    }

    #**************************************************************************************************************************************#
    /**
     * @Route("/corpus/partager/{id}", name="CAllow")
     */

    public function CAllow(
        EntityManagerInterface $manager,
        Corpus $record,
        $id
    ) {

        $record->setVerou(!($record->getVerou()));
        $manager->persist($record);
        $manager->flush();

        return $this->redirectToRoute('CGetAll');
    }


    #**************************************************************************************************************************************#
    #**************************************************************************************************************************************#
    /* CLASS END */
}
