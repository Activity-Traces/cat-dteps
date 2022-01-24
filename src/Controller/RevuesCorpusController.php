<?php

namespace App\Controller;


use App\Entity\Articles;
use App\Form\ArticlesFormType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RevuesCorpusController extends AbstractController
{


    //************************************************************************************************************************************* */

    #**************************************************************************************************************************************#

    /** Delete one corpus
     * 
     * @Route("/article/editer/{id}", name="ArticleEDIT")
     */

    public function ArticlesEDIT(
        EntityManagerInterface $manager,
        Articles $record,
        Request $request,
        $id
    ) {

        $form = $this
            ->createForm(ArticlesFormType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($record);
            $manager->flush();
            return $this->redirectToRoute('getArticles');
        }

        return $this->render('articles/update.html.twig', ['form' => $form->createView(), 'action' => 'Edit']);
    }




    //************************************************************************************************************************************* */

    #**************************************************************************************************************************************#

    /** Delete one corpus
     * 
     * @Route("/article/supprimer/{id}", name="ArticleDELETE")
     */

    public function ADelete(
        EntityManagerInterface $manager,
        Articles $record
    ) {

        $manager->remove($record);
        $manager->flush();
        return $this->redirectToRoute('getArticles');
    }

    #**************************************************************************************************************************************#

    //************************************************************************************************************************************* */

    /**
     * @Route("/xml", name="getAllXML")
     */
    public function getXML(
        ArticlesRepository $rep
    ) {

        $find = $rep->findAll();
        foreach ($find as $item) {


            //  $contenu = str_replace(array('"',  '<', '>', '“', '”', '&'), '\'', $item->getContenu());

            //  $biblio = str_replace(array('"', '<', '>', '“', '”', '&'), '\'', $item->getBiblio());


            $contenu = htmlspecialchars($item->getContenu(), ENT_XML1, 'UTF-8');
            $biblio =  htmlspecialchars($item->getBiblio(), ENT_XML1, 'UTF-8');


            $source = '<?xml version="1.0" encoding="UTF-8"?> 
            <text auteur="' . $item->getAuteur()  . '" titre="' . $item->getTitre() .
                '" periode="' . $item->getPeriode() . '" numero_parent="' .  $item->getNumeroParent() . '" 
        href_parent="' . $item->getHrefparent() . '" id="' .   $item->getHrefparent()  . '">
   
   
        <div id="abstract" class="section">
           <h2 class="section">
   
               <span class="text">Résumés</span>
   
           </h2>
   
   
           <div class="tabMenu">
   
               <a class="active" href="#abstract-' . $item->getHrefparent() . '-fr">Français</a>
   
               <a href="#abstract-' . $item->getHrefparent() . '-en" hreflang="en">English</a>
   
           </div>
   
   
           <p class="resume">' .  $item->getResumeFr() . '
           </p>
           <p lang="en" class="resume">' .  $item->getResumeEn() . '
   
           </p>
       </div>
   
   
   
       <div id="text" class="section">
   
   
           <h2 class="section">
           
               <span class="text">Texte intégral</span>
           
           </h2>';


            $par = '';
            $i = 1;

            $paragraphs = explode(chr(10), $contenu);
            foreach ($paragraphs as $paragraph) {



                if (is_numeric($paragraph[0]))
                    $par = $par . '  <h1 class="texte"> <a id="tocto" href="#tocfrom">' . $paragraph . '</a></h1>';
                else {
                    $par = $par .  '<p class="texte"><span class="paranumber">' . $i . '</span>' . $paragraph . '</p>';
                    $i++;
                }
            }


            $source = $source . $par . '</div>';



            $bib = '<div id="bibliography" class="section">
                <h2 class="section">   
                <span class="text">Bibliographie</span>
                </h2>';

            $bibliographies = explode("\r\n", $biblio);


            foreach ($bibliographies as $bibliographie) {

                $bib = $bib . '<p class="bibliographie">
            <span lang="en" xml:lang="en" rend="uppercase">' . $bibliographie . '</span>
            </p>';
            }


            $source = $source . $bib . '</div></text>';
            file_put_contents('xml/XML_htmlEnteties_' . $item->getHrefparent() . '.xml', $source);
        }

        return $this->render('articles/voirXml.html.twig', ['result' => $source]);
    }


    #**************************************************************************************************************************************#

    //************************************************************************************************************************************* */

    /**
     * @Route("/xml/voir/{id}", name="getXML")
     */



    public function getOneXML(
        Articles $item
    ) {


        $contenu = htmlspecialchars($item->getContenu(), ENT_XML1, 'UTF-8');
        $biblio =  htmlspecialchars($item->getBiblio(), ENT_XML1, 'UTF-8');

        //$contenu = str_replace(array('"',  '<', '>', '“', '”', '&'), '\'', $item->getContenu());
        //$biblio = str_replace(array('"', '<', '>', '“', '”', '&'), '\'', $item->getBiblio());
        //$contenu = htmlentities($item->getContenu(), ENT_QUOTES, 'UTF-8');

        //$biblio = htmlentities($item->getBiblio(), ENT_QUOTES,  'UTF-8');



        $source = '<?xml version="1.0" encoding="UTF-8"?> 
            <text auteur="' . $item->getAuteur()  . '" titre="' . $item->getTitre() .
            '" periode="' . $item->getPeriode() . '" numero_parent="' .  $item->getNumeroParent() . '" 
        href_parent="' . $item->getHrefparent() . '" id="' .   $item->getHrefparent()  . '">
   
   
        <div id="abstract" class="section">
           <h2 class="section">
   
               <span class="text">Résumés</span>
   
           </h2>
   
   
           <div class="tabMenu">
   
               <a class="active" href="#abstract-' . $item->getHrefparent() . '-fr">Français</a>
   
               <a href="#abstract-' . $item->getHrefparent() . '-en" hreflang="en">English</a>
   
           </div>
   
   
           <p class="resume">' .  $item->getResumeFr() . '
           </p>
           <p lang="en" class="resume">' .  $item->getResumeEn() . '
   
           </p>
       </div>
   
   
   
       <div id="text" class="section">
   
   
           <h2 class="section">
           
               <span class="text">Texte intégral</span>
           
           </h2>';


        $par = '';
        $i = 1;

        $paragraphs = explode(chr(10), $contenu);
        foreach ($paragraphs as $paragraph) {


            if (is_numeric($paragraph[0]))
                $par = $par . '  <h1 class="texte"> <a id="tocto" href="#tocfrom">' . $paragraph . '</a></h1>';
            else {
                $par = $par .  '<p class="texte"><span class="paranumber">' . $i . '</span>' . $paragraph . '</p>';
                $i++;
            }
        }


        $source = $source . $par . '</div>';



        $bib = '<div id="bibliography" class="section">
                <h2 class="section">   
                <span class="text">Bibliographie</span>
                </h2>';

        $bibliographies = explode("\r\n", $biblio);


        foreach ($bibliographies as $bibliographie) {

            $bib = $bib . '<p class="bibliographie">
            <span lang="en" xml:lang="en" rend="uppercase">' . $bibliographie . '</span>
            </p>';
        }


        $source = $source . $bib . '</div></text>';

        file_put_contents('xml/TEST_XML_htmlEnteties_' . $item->getHrefparent() . '.xml', $source);



        return $this->render('articles/voirXml.html.twig', ['result' => $source]);
    }

    //************************************************************************************************************************************* */
    //************************************************************************************************************************************* */

    /**
     * @Route("/articles", name="getArticles")
     */
    public function getArticles(

        EntityManagerInterface $manager,
        Request $request,
        ArticlesRepository $rep
    ) {

        $form = $this
            ->createForm(ArticlesFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $form->getData();
            $Record = clone $item;
            $manager->persist($Record);
            $manager->flush();
        }

        $find = $rep->findAll();



        return $this->render('articles/articles.html.twig', ['form' => $form->createView(), 'result' => $find]);
    }
}
