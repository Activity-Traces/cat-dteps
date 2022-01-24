<?php


namespace App\Form;

use App\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ResourceUpdate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom du ressource'])
            ->add('description', TextareaType::class, ['label' => 'Description'])

            ->add('resourceFile', FileType::class, [
                'label' => 'Telecharger la ressource',

                'mapped' => false,

                'required' => false,
                'multiple' => true,
                "allow_extra_fields" => true




            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
        ]);
    }
}
