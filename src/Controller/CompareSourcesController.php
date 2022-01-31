<?php

namespace App\Controller;

use App\Form\SearchText;
use Html2Text\Html2Text;
use App\Entity\Dictionary;
use App\Form\ResourcesList;

use App\Service\translatewords;

use App\Repository\DictionaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompareSourcesController extends AbstractController
{




    #**************************************************************************************************************************************#

    /** 
     * 
     * @Route("/charger/resources", name="loadSources")
     */

    public function load(
        UserInterface $user,
        Request $request
    ) {


        $form =  $this->createForm(ResourcesList::class, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $origin = $form->get('resourcesleft')->getData();
            $compared = $form->get('resourcesright')->getData();
            return $this->redirectToRoute('compareSources', ['source' => $origin->getResourceFile(), 'destination' => $compared->getResourceFile()]);
        }

        return $this->render('compare_sources/load.html.twig', [
            'form' => $form->createView()
        ]);
    }





    #**************************************************************************************************************************************#

    /** Add a new corpus
     * 
     * @Route("/comparer/sources/{source}/{destination}", name="compareSources")
     */

    public function compare(
        UserInterface $user,
        Request $request,
        DictionaryRepository $rep,
        EntityManagerInterface $manager,
        $source,
        $destination

    ) {



        /***************************************************************************************************************** */
        // Load the two files

        $folder = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/resources/';

        $origin = strtolower(mb_convert_encoding(file_get_contents($folder  . $source), 'HTML-ENTITIES', "UTF-8"));
        $compared = strtolower(mb_convert_encoding(file_get_contents($folder  . $destination), 'HTML-ENTITIES', "UTF-8"));

        $form =  $this->createForm(SearchText::class);
        $form->handleRequest($request);

        /***************************************************************************************************************** */
        // get form data

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $search = strtolower($data['search']);
            $options = $data['rechercher'];
            $useDictionary = $data['canDictio'];

            /***************************************************************************************************************** */

            if (($options == 1) && (isset($search))) {

                // searsh in dictionary and then in google

                $translate = new translatewords($search, $data['LenguageIn'],  $data['LenguageOut']);

                $translate->translate($rep);
                $result = $translate->getTranslation();
                $results = $translate->getTranslations();
                $equals = $translate->getSynonym();
                $message = $translate->getMessage();

                // update dictionary

                if ($useDictionary) {

                    $dictionary = new Dictionary();
                    $dictionary->setWord($search);
                    $dictionary->setLang($data['LenguageIn']);
                    $dictionary->setLangDest($data['LenguageOut']);
                    $dictionary->setTranslate($result);
                    $dictionary->setTranslated($results);
                    $dictionary->setElquals($equals);

                    $manager->persist($dictionary);
                    $manager->flush();
                }

                /***************************************************************************************************************** */

                // some stats
                $searchCount = substr_count($origin, $search);
                $comparedCount = substr_count($compared, $result);
                $ProperCount = substr_count($compared, $search);

                // visualize
                $origin = str_ireplace($search, '<mark><b>' . $search . '</b></mark>', $origin);
                $compared = str_ireplace($result, '<mark><b>' . $result . '</b></mark>', $compared);
                $compared = str_ireplace($search, '<mark><b style="color:green;">' . $search . '</b></mark>', $compared);

                $Temp = explode(',', $results);
                foreach ($Temp as $word) {
                    if ($word != null)
                        $compared = str_ireplace($word, '<mark><b style="color:Tomato;">' . $word . '</b></mark>', $compared);
                }

                $Temp = explode(',', $equals);
                foreach ($Temp as $word) {
                    if ($word != null)
                        $origin = str_ireplace($word, '<mark><b style="color:blue;">' . $word . '</b></mark>', $origin);
                }
            }

            unset($translate);

            /***************************************************************************************************************** */

            if (($options == 2) && (isset($search))) {


                $params = explode('-', $search);
                dump(count($params));

                if (count($params) == 2) {

                    $translate = new translatewords($params[0], $data['LenguageIn'],  $data['LenguageOut']);

                    $translate->translate($rep);
                    $resultWord1 = $translate->getTranslation();
                    $resultsWord1 = $translate->getTranslations();
                    $equalsWord1 = $translate->getSynonym();
                    unset($translate);



                    $translate = new translatewords($params[2], $data['LenguageIn'],  $data['LenguageOut']);

                    $translate->translate($rep);
                    $resultWord2 = $translate->getTranslation();
                    $resultsWord2 = $translate->getTranslations();
                    $equalsWord2 = $translate->getSynonym();
                    unset($translate);

                    preg_match_all('/' . $params[0] . '(.*?) ' . $params[1] . '/', $origin, $match);
                    foreach ($match[0] as $line) {
                        $origin = str_ireplace($line, '<mark>' . $line . '</mark>', $origin);
                    }
                }
            }
        }



        return $this->render('compare_sources/search.html.twig', [
            'form' => $form->createView(), 'origin' => $origin, 'compared' => $compared,
            'result' => $result, 'results' => $results, 'search' => $search, 'equals' => $equals, 'SLeng' => $data['LenguageIn'], 'DLeng' => $data['LenguageOut'],
            'searchCount' => $searchCount, 'comparedCount' => $comparedCount, 'message'  => $message,
            'ProperCount' => $ProperCount, 'autoscroll' => 'height: 950px; overflow-y: auto;'


        ]);
    }
}
