<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UploadComparedFiles extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('intialFile', FileType::class, [
                'label' => 'Fichier Enitial',

                'mapped' => false,

                'required' => false,
                'multiple' => false

            ])
            ->add('ComparedFile', FileType::class, [
                'label' => 'Fichier Equivalent',

                'mapped' => false,

                'required' => false,
                'multiple' => false

            ]);
    }
}
