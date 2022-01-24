<?php

namespace App\Controller;

use App\Entity\Dictionary;
use App\Form\DictionaryForm;
use App\Repository\DictionaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DictionaryController extends AbstractController
{



    #**************************************************************************************************************************************#

    /** 
     * 
     * @Route("/dictionnaire/editer/{id}", name="DictionaryEDIT")
     */ public function DictionaryEDIT(
        EntityManagerInterface $manager,
        Dictionary $record,
        Request $request,
        $id
    ) {

        $form = $this
            ->createForm(DictionaryForm::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($record);
            $manager->flush();
            return $this->redirectToRoute('getDictionary');
        }

        return $this->render('dictionary/update.html.twig', ['form' => $form->createView(), 'action' => 'Edit']);
    }

    #**************************************************************************************************************************************#

    /** Delete one corpus
     * 
     * @Route("/dictionnaire/supprimer/{id}", name="DictionaryDELETE")
     */

    public function DictionaryDelete(
        EntityManagerInterface $manager,
        Dictionary $record
    ) {

        $manager->remove($record);
        $manager->flush();
        return $this->redirectToRoute('getDictionary');
    }



    //************************************************************************************************************************************* */

    /**
     * @Route("/dictionnaire", name="getDictionary")
     */
    public function getDictionary(

        EntityManagerInterface $manager,
        Request $request,
        DictionaryRepository $rep
    ) {

        $form = $this
            ->createForm(DictionaryForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $item = new Dictionary();

            $item = $form->getData();

            dump($item);

            $manager->persist($item);
            $manager->flush();
        }

        $find = $rep->findAll();

        return $this->render('dictionary/dictionary.html.twig', ['form' => $form->createView(), 'result' => $find]);
    }
}
