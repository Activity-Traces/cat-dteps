<?php
namespace App;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#**************************************************************************************************************************************#/

class Records extends AbstractController {


    #**************************************************************************************************************************************#/

    public function GetRecords($form, $request, $rep, $user){

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $search = $form['search']->getData();

            if ($search == null) 

                $result = $rep->findBy(['hasUser'=>$user]);
            else
                $result = $rep->findByName($search, $user);
        } 
        else

            $result = $rep->findBy(['hasUser'=>$user]);
    
        return $result;

    }

    #**************************************************************************************************************************************#/
public function addRecord($source, $form, $record, $request, $user, $mode, $route){


            $record->setHasUser($user);

            if ($mode == 'corpus') {
                 $record->setVerou('0');
                $record->setCreatedAt(new \DateTime('now'));

            }
      
            $manager->persist($record);
            $manager->flush();
        

}

    /*
    public function Add($form, $record, $slugger, $request, $user, $mode, $pathparam,         EntityManagerInterface $manager
){

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $record->setHasUser($user);

            if ($mode == 'resource') {
                $resourceFile = $form->get('resourceFile')->getData();
                $record = $this->getFile($resourceFile, $slugger, $pathparam, $record);
            }

            $manager->persist($record);
            $manager->flush();

        }
    }


    #**************************************************************************************************************************************#/

public function getFile($resourceFile, $slugger, $pathparam, $record){

    
            if ($resourceFile) {
                $originalFilename = pathinfo($resourceFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $resourceFile->guessExtension();

                try {
                    $resourceFile->move($pathparam,
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $record->setresourceFile($newFilename);
            }
            return ($record);

}
*/
}
