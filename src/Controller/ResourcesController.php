<?php

namespace App\Controller;

use App\Records;
use App\Form\Search;
use App\Entity\Corpus;
use App\Entity\Resource;
use App\Form\CorpusList;
use App\Form\ResourceUpdate;
use App\Service\FileUploader;
use App\Repository\CorpusRepository;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ResourcesController extends AbstractController
{

    #**************************************************************************************************************************************#
    // view all resources
    /**
     * @Route("/resources", name="RGetAll")
     */

    public function RGetAll(
        Request $request,
        ResourceRepository $rep,
        UserInterface $user

    ) {

        $service = new Records;
        $form =  $this->createForm(Search::class);
        $result = $service->GetRecords($form, $request, $rep, $user);

        return $this->render('resources/getAll.html.twig', ['result' => $result, 'form' => $form->createView()]);
    }



    #**************************************************************************************************************************************#
    /** Delete only resources related ro Resource: it does not delete an existing resource
     * 
     * @Route("/resources/supprimer/{Corpus}/{id}/{resourceid}/{mode}", name="RCDelete")
     */
    public function RCDelete(
        EntityManagerInterface $manager,
        CorpusRepository $CorpusRep,
        Resource $Resource_Record,
        $Corpus,
        $resourceid,
        $mode

    ) {

        $CorpusRecord = new Corpus();

        $CorpusRecord = $CorpusRep->findOneBy(['id' => $Corpus]);
        $CorpusRecord->removeHasResource($Resource_Record);
        $manager->flush();

        if ($mode == 1)
            return $this->redirectToRoute('CGet', ['id' => $Corpus]);

        if ($mode == 0)
            return $this->redirectToRoute('RGet', ['id' => $resourceid]);
    }

    #**************************************************************************************************************************************#

    /** Add a new Resource
     * 
     * @Route("/resources/ajouter", name="RAdd")
     */

    public function RAdd(
        EntityManagerInterface $manager,
        Request $request,
        UserInterface $user,
        SluggerInterface $slugger
    ) {



        $directory = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/resources';
        $fileUploader = new  FileUploader($directory, $slugger);

        $form = $this
            ->createForm(ResourceUpdate::class);

        $form->handleRequest($request);
        dump($form->getExtraData());

        if ($form->isSubmitted() && $form->isValid()) {

            $i = 1;


            $files = $form->get('resourceFile')->getData();

            dump($form->get('resourceFile')->getData());

            foreach ($files as $file) {

                if ($file) {

                    $newFilename = $fileUploader->upload($file,  $user->getUsername());

                    $Record = new Resource();
                    $Record->setHasUser($user);
                    $Record->setDescription($form->get('description')->getData());
                    $Record->setNom($form->get('nom')->getData() . $i);
                    $Record->setresourceFile($newFilename);
                    $Record->setVerou(0);

                    $manager->persist($Record);
                    $manager->flush();
                    unset($Record);
                    $i++;
                }
            }


            return $this->redirectToRoute('RGetAll');
        }

        return $this->render(
            'resources/update.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Add'
            ]
        );
    }


    #**************************************************************************************************************************************#

    /** View one Resource using it's identifier
     * 
     * @Route("/Resource/voir/{id}", name="RGet") 
     */

    public function RGet(Resource $Resource)

    {
        return $this->render(
            'resources/get.html.twig',
            [
                'resource' => $Resource,
            ]
        );
    }


    #**************************************************************************************************************************************#

    /** To edit Resource
     * 
     * @Route("/resources/editer/{id}", name="REdit")
     */

    public function REdit(
        EntityManagerInterface $manager,
        Resource $record,
        Request $request,
        $id,
        FileSystem $filesystem,
        UserInterface $user,
        SluggerInterface $slugger



    ) {


        $form = $this
            ->createForm(ResourceUpdate::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $files = $form->get('resourceFile')->getData();

            if ($files != null) {
                $file = $files[0];

                if ($file != null) {

                    $directory = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/resources';

                    try {

                        dump($record->getResourceFile());


                        unlink($directory . '/' . $record->getResourceFile());

                        $fileUploader = new  FileUploader($directory, $slugger);

                        // créer le nouveau fichier
                        $newFilename = $fileUploader->upload($file,  $user->getUsername());

                        $record->setresourceFile($newFilename);
                    } catch (FileException $e) {
                    }
                }
            }

            $manager->persist($record);
            $manager->flush();
            return $this->redirectToRoute('RGetAll');
        }

        return $this->render(
            'resources/update.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Edit'
            ]
        );
    }

    #**************************************************************************************************************************************#

    /** Delete one Resource
     * 
     * @Route("/resources/supprimer/{id}", name="RDelete")
     */


    public function RDelete(
        EntityManagerInterface $manager,
        Resource $record,
        FileSystem $filesystem,
        UserInterface $user,

        $id
    ) {

        $directory = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/resources';


        $filesystem->remove(['symlink', $directory, $record->getResourceFile()]);

        $manager->remove($record);
        $manager->flush();
        return $this->redirectToRoute('RGetAll');
    }

    #**************************************************************************************************************************************#

    /** Delete all Resource
     * 
     * @Route("/resources/toutsupprimer", name="RDeleteAll")
     */

    public function RDeleteAll(
        ResourceRepository $rep,
        UserInterface $user,
        FileSystem $filesystem
    ) {

        $directory = $this->getParameter('upload_directory') . '/' . $user->getUsername() . '/resources';

        $filesystem->remove(['symlink',  $directory]);

        $rep->deleteAll($user);
        return $this->redirectToRoute("RGetAll");
    }

    #**************************************************************************************************************************************#

    /**
     * 
     * @Route("/resources/links", name="RCLink")
     */

    public function linkResourcesTwo(
        Request $request,
        UserInterface $user,
        ResourceRepository $rep,
        EntityManagerInterface $manager

    ) {

        $result = null;

        $form = $this->createForm(CorpusList::class, ['user' => $user]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            if ($form->getClickedButton() === $form->get('done')) {

                $corpus = $form->get('corpus')->getData();
                if (isset($corpus)) {

                    $List = $corpus->getHasResource();

                    foreach ($List as $element) {

                        $corpus->removeHasResource($element);

                        $manager->persist($corpus);
                        $manager->flush();
                    }

                    if (isset($_POST['resourcein'])) {

                        foreach ($_POST['resourcein'] as $ResId) {

                            $resource = $rep->find($ResId);
                            $corpus->addHasResource($resource);

                            $manager->persist($corpus);
                            $manager->flush();
                        }
                    }

                    return $this->render('resources/confirmation.html.twig', ['message' => 'mise à jour effectuée']);
                }
            }
        }


        $result = $rep->findBy(['hasUser' => $user]);
        return $this->render(
            'resources/links.html.twig',
            ['form' => $form->createView(), 'result' => $result]
        );
    }



    #**************************************************************************************************************************************#

    /**
     * @Route("/resource/partager/{id}", name="RAllow")
     */

    public function CAllow(
        EntityManagerInterface $manager,
        Resource $record,
        $id
    ) {

        $record->setVerou(!($record->getVerou()));
        $manager->persist($record);
        $manager->flush();

        return $this->redirectToRoute('RGetAll');
    }

    #**************************************************************************************************************************************#

    #**************************************************************************************************************************************#

}
