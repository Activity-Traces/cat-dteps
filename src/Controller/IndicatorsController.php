<?php

namespace App\Controller;

use App\Entity\Indicator;
use App\Form\EvaluationType;
use App\Repository\IndicatorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndicatorsController extends AbstractController
{
    #**************************************************************************************************************************************#
    #**************************************************************************************************************************************#

    /** 
     * @Route("/indicateurs", name="ICreate")
     */

    public function ICreate(
        Request $request,
        UserInterface $user,
        IndicatorRepository $rep,
        EntityManagerInterface $manager

    ) {



        $form = $this
            ->createForm(EvaluationType::class, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $Indicator_Record = new Indicator();

            $Indicator_Record->setNom($form['nom']->getData());
            $Indicator_Record->setHasEvaluation($form['evaluation']->getData());
            $Indicator_Record->setTimeBegin($form['timeBegin']->getData());
            $Indicator_Record->setTimeEnd($form['timeEnd']->getData());

            $Indicator_Record->setHasUser($user);

            $manager->persist($Indicator_Record);
            $manager->flush();
        }

        $result = $rep->findBy(['hasUser' => $user]);



        return $this->render(
            'indicators/create.html.twig',
            [
                'form' => $form->createView(), 'result' => $result
            ]
        );
    }


    #**************************************************************************************************************************************#/

    /** 
     * @Route("/indicateurs/Afficher/{type}/{page}/{id}", name="IView")
     */

    public function IView(Request $request, $type = null, $page = null, $id = null)
    {

        $vector = array();

        $x1 = array();
        $y1 = array();

        $x = array();
        $y = array();

        if ($page == null)
            $page = 1;

        $CurrentPage = 0;
        $pages = 0;
        $PerPage = 12;
        if (isset($id)) {
            $result = $this->get('session')->get('result');
            // dump($result);


            $count = count($result[$id][1]);

            $CurrentPage = $page;

            if ($CurrentPage <= 0)
                throw new Exception('page invalide');


            $pages = ceil($count / $PerPage);

            if ($CurrentPage > $pages)
                throw new Exception("page n'exites pas ");

            $min = $PerPage * ($CurrentPage - 1);
            //  $max = $min + $PerPage;


            $V = $result[$id][1];


            $vector = array_slice($V, $min, $PerPage);
            //dump($count, $min, $PerPage, $CurrentPage, $vector);


            dump($vector);
            foreach ($vector as $vect) {

                $x1[] = $vect[0];
                $y1[] = $vect[1];
            }
        }
        $x = json_encode($x1);
        $y = json_encode($y1);


        return $this->render(
            'Indicators/view.html.twig',
            [
                'x' => $x,
                'y' => $y,
                'type' => $type,
                'currentPage' => $CurrentPage,
                'pages' => $pages,
                'id' => $id

            ]
        );
    }

    #**************************************************************************************************************************************#/


    #**************************************************************************************************************************************#

    /** Delete all indicators
     * 
     * @Route("/indicatuer/supprimer/{id}", name="IDelete")
     */


    public function IDelete(
        EntityManagerInterface $manager,
        Indicator $record,
        $id
    ) {

        $manager->remove($record);
        $manager->flush();
        return $this->redirectToRoute('ICreate');
    }

    #**************************************************************************************************************************************#/

}
