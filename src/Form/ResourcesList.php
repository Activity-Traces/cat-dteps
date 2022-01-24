<?php

namespace App\Form;

use App\Entity\Corpus;
use App\Entity\Resource;
use App\Repository\CorpusRepository;
use App\Repository\ResourceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResourcesList extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $params = $options['data'];
        $param_user = $params['user'];



        $builder
            ->add(
                'resourcesleft',
                EntityType::class,
                [
                    'class' => Resource::class,

                    'query_builder' => function (ResourceRepository $rep) use ($param_user) {
                        return $rep->createQueryBuilder('p')
                            ->where('p.hasUser = :user')
                            ->setParameter('user', $param_user);
                    },

                    'choice_label' => 'nom', 'label' => false,
                    'multiple' => false, 'required' => true
                ]

            )

            ->add(
                'resourcesright',
                EntityType::class,
                [
                    'class' => Resource::class,

                    'query_builder' => function (ResourceRepository $rep) use ($param_user) {
                        return $rep->createQueryBuilder('p')
                            ->where('p.hasUser = :user')
                            ->setParameter('user', $param_user);
                    },

                    'choice_label' => 'nom', 'label' => false,
                    'multiple' => false, 'required' => true
                ]

            );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
