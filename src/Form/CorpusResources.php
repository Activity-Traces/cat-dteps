<?php

namespace App\Form;

use App\Entity\Corpus;

use App\Entity\Resource;

use App\Repository\CorpusRepository;
use Symfony\Component\Form\FormEvent;
use App\Repository\ResourceRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CorpusResources extends AbstractType
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
            ->add('done', SubmitType::class);


        $builder->get('corpus')->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                dump($form);
                $val = $form['name'];

                $form->getParent()->add(

                    'resource1',
                    EntityType::class,
                    [

                        'class' => Resource::class,

                        'query_builder' => function (ResourceRepository $rep) use ($val) {
                            return $rep->createQueryBuilder('p')
                                ->where('p.hasCopus = :crp')
                                ->setParameter('crp', $val);
                        },

                        'choice_label' => 'nom', 'label' => false,
                        'multiple' => false, 'required' => false

                    ]
                );
            }
        );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
