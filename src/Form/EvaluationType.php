<?php

namespace App\Form;

use DateTime;
use App\Entity\Corpus;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\Evaluation;
use App\Repository\CorpusRepository;
use App\Repository\EvaluationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['data'];
        $param = $user['user'];

        $builder
            ->add('nom', TextType::class, ['label'=>'Indicateur'])
            ->add(
                'evaluation',
                EntityType::class,
                [
                    'class' => Evaluation::class,

                    'query_builder' => function (EvaluationRepository $rep) use ($param) {
                        return $rep->createQueryBuilder('p')
                            ->where('p.hasUser = :user')
                            ->setParameter('user', $param);
                    },

                    'choice_label' => 'nom', 'label' => "Evaluation en question",
                    'multiple' => false, 'required' => true
                ]
                )


            ->add(
                'timeBegin',
                DateTimeType::class,
                [
                    'widget' => 'single_text', 'label' => "Date début",
                    'data' => new \DateTime("now")
                ]
            )
            ->add(
                'timeEnd',
                DateTimeType::class,
                [
                    'widget' => 'single_text', 'label' => "Date fin",
                    'data' => new \DateTime("now")
                ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
