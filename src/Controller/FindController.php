<?php

namespace App\Controller;

use App\Form\CorpusList;
use App\Form\findCorpus;
use App\Service\Textometry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FindController extends AbstractController
{

    //******************************************************************************************************* */


    /**
     * @Route("/find/init", name="initfind")
     */
    public function initfind(
        Request $request,
        UserInterface $user
    ) {



        $form = $this->createForm(CorpusList::class, ['user' => $user]);
        $form->handleRequest($request);

        $total = 0;

        if (($form->isSubmitted()) && ($form->get('corpus')->getData() != null)) {


            if (!($this->get('session')->get("final"))) {


                $final = [];

                $this->get('session')->set('final', $final);


                $Analyse = new Textometry;

                $directory = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/resources';

                $resources = $form->get('corpus')->getData()->getHasResource();
                $corpus = $form->get('corpus')->getData()->getNom();
                $this->get('session')->set('corpusName', $corpus);


                foreach ($resources as $resource) {

                    $total = 0;
                    $data = [];
                    $results = [];
                    $xml = [];

                    $path =  $directory . '/' . $resource->getResourceFile();

                    dump($path);
                    $xml = simplexml_load_file($path);
                    $lines = $xml->body;

                    $list = null;
                    $Analyse->setWords([]);

                    foreach ($lines->p as $line) {
                        $Analyse->setTxt($line);

                        $Analyse->getWordsCount();
                        $list = $Analyse->getWords();
                    }


                    foreach ($list as $key => $value) {
                        $data[] = array('name' => $key, 'value' => $value);
                    }

                    unset($list);


                    $results['Name'] = $resource->getNom() . '_' . $resource->getResourceFile();
                    $results['Info'] = json_decode(json_encode($xml->attributes()), TRUE);
                    $results['Value'] = $data;
                    $results['total'] = $total;
                    //$results['max'] = 1;

                    array_push($final, $results);

                    unset($results);
                    unset($data);
                    unset($xml);
                    unset($temp);
                    unset($lines);
                    unset($line);
                }


                unset($Analyse);


                $this->get('session')->set('final', $final);

                unset($final);
            }


            return $this->redirectToRoute('find');
        }

        return $this->render(
            'find/init.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    //******************************************************************************************************* */
    /**
     * @Route("/find", name="find")
     */
    public function find(

        Request $request,
        UserInterface $user
    ): Response {

        $tab = 2;

        $form = $this->createForm(findCorpus::class, ['user' => $user]);
        $form->handleRequest($request);
        $choice = '';
        $final = [];
        $min = 0;
        $max = 0;
        $result = [];
        $words = [];


        $corpusName = $this->get('session')->get('corpusName');


        if ($form->isSubmitted()) {

            //$clean = $form->get('clean')->getData();
            $sort = $form->get('sorted')->getData();

            $choice = $form->get('choix')->getData();


            $tab =  $form->get('tab')->getData();
            $min = $form->get('min')->getData();
            $max = $form->get('max')->getData();

            $final = $this->get('session')->get('final');

            $Mots = $form->get('word')->getData();
            //       $Mots = $form->get('Mots')->getData();
            if ($choice == 'Mots') {
                if ($Mots != null) {
                    $found_key = null;



                    $list = explode(',', $Mots);
                    foreach ($list as $token) {

                        foreach ($final as $line) {



                            $found_key = array_search($token, array_column($line['Value'], 'name'));
                            if ($found_key)

                                $result[] =    [$line['Name'], $line['Value'][$found_key]];
                        }
                    }
                }
            }


            if ($choice == 'Frequence') {

                foreach ($final as $resource) {

                    $words = [];

                    $resourceName = $resource['Name'];


                    foreach ($resource['Value'] as $element) {
                        if (($element['value']  >= $min) && ($element['value'] <= $max))
                            $words[] =    [$element['name'], $element['value']];
                    }

                    $result[] = [$resourceName, $words];
                }

                $this->get('session')->set('result', $result);
            }
        }




        return $this->render(
            'find/find.html.twig',
            [
                'form' => $form->createView(),
                'words' => $result,  'results' => $result, 'tab' => $tab, 'mode' => $choice, 'corpusName' => $corpusName, 'min' => $min, 'max' => $max
            ]
        );
    }
}
