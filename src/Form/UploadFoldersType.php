<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UploadFoldersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('Folder', FileType::class, [
                'label' => 'Dossier Source',

                'mapped' => false,

                'required' => false,
                'multiple' => true

            ]);
    }
}
