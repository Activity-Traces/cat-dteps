<?php


namespace App\Form;

use App\Form\CorpusList;
use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EvaluationUpdate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de l"évaluation'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('regles', TextareaType::class, ['label' => 'regle', 'required' => false]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

                        'data_class' => Evaluation::class,

        ]);
    }
}
