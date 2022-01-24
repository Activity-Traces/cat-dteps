<?php

namespace App\Form;

use App\Entity\Corpus;

use App\Repository\CorpusRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CorpusList extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $params = $options['data'];
        $param_user = $params['user'];



        $builder
            ->add(
                'corpus',
                EntityType::class,
                [
                    'class' => Corpus::class,

                    'query_builder' => function (CorpusRepository $rep) use ($param_user) {
                        return $rep->createQueryBuilder('p')
                            ->where('p.hasUser = :user')
                            ->setParameter('user', $param_user);
                    },

                    'choice_label' => 'nom', 'label' => false,
                    'multiple' => false, 'required' => false
                ]

            )
            ->add('done', SubmitType::class);;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
