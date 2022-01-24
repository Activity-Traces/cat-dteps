<?php

namespace App\Controller;

use App\Repository\CorpusRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartagesController extends AbstractController
{
    /**
     * @Route("/partages", name="PGetAll")
     */
    public function share(CorpusRepository $rep)
    {



        $result = $rep->findBy(['verou' => 1]);


        return $this->render('partages/getAll.html.twig', ['result' => $result]);
    }


    #**************************************************************************************************************************************#/
    /**
     * @Route("/partages/telecharger/{id}", name="Download")
     */
    public function download($id)
    {

        return $this->render('partages/getAll.html.twig', ['result' => '']);
    }
}
