<?php

namespace App\Controller;


use Html2Text\Html2Text;
use App\Service\Textometry;
use App\Service\FileUploader;
use App\Form\UploadFoldersType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TransformCorpusController extends AbstractController
{


    //************************************************************************************************************************************* */

    /**
     * @Route("/plain", name="htmlToPlain")
     */
    public function plaintext()
    {


        //$dir = $this->getParameter('resources_directory');

        $directory = 'virginie/sources/';
        $output = 'virginie/destination/';

        $dir = opendir($directory);

        $html = new Html2Text;


        while ($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && !is_dir($directory . $file)) {
                $val = file_get_contents($directory . $file);
                $html->setHtml($val);
                $value = $html->getText();

                $filesres = pathinfo($output . $file, PATHINFO_FILENAME);


                file_put_contents($output . $filesres . '.txt', $value);
            }
        }

        closedir($dir);

        return $this->render('testsing/index.html.twig');
    }



    //************************************************************************************************************************************* */
    //************************************************************************************************************************************* */

    /**
     * @Route("/conversions/deletetags", name="deletetags")
     */
    public function deleteTags(
        UserInterface $user,
        Request $request,

        SluggerInterface $slugger
    ) {



        $folder = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/tags';
        $folderIn = $folder . '/in/';
        $folderOut = $folder . '/out/';

        $fileUploader = new  FileUploader($folderIn, $slugger);



        $form = $this
            ->createForm(UploadFoldersType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('Folder')->getData();

            foreach ($files as $file) {

                if ($file) {

                    $fileUploader->upload($file,  $user->getUsername());
                }
            }


            $Analyse = new Textometry;
            $Analyse->setFolderIn($folderIn);
            $Analyse->setFolderOut($folderOut);
            $Analyse->setPattern('#<[^/>]*/>#');
            $Analyse->deleteXMLTags();
            //$Analyse->uploadFiles();
            //exit();
        }
        return $this->render(
            'xmlTags/upload.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Add'
            ]
        );
    }





    //************************************************************************************************************************************* */

    /**
     * @Route("/ramteq", name="ramteq")
     */
    public function iramuteq()
    {


        //$dir = $this->getParameter('resources_directory');

        $directory = 'virginie/destination/';
        $output = 'virginie/iramteq/';

        $dir = opendir($directory);
        $new_content = '';

        while ($file = readdir($dir)) {

            $val = '';
            $new_content = '';
            $lines = '';

            if ($file != '.' && $file != '..' && !is_dir($directory . $file)) {
                $val = file_get_contents($directory . $file);

                $lines = explode(chr(10), $val);

                foreach ($lines as $line) {
                    if (strlen(trim($line)) > 0) {
                        $new_content .= $line . chr(10);
                    } else

                        $new_content .= $line . '****' . chr(10);
                }

                $filesres = pathinfo($output . $file, PATHINFO_FILENAME);
                file_put_contents($output . $filesres . '.txt', $new_content);
            }
        }

        closedir($dir);

        return $this->render('test/index.html.twig');
    }


    //************************************************************************************************************************************* */
    //************************************************************************************************************************************* */

}
