<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class IndicatorsList extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equation', TextareaType::class)

            ->add(
                'monchoix',
                ChoiceType::class,
                [
                    'choices'  => [
                        'PieChart' => 'PieChart',
                        'Histogram' => 'Histogram',
                        'AreaChart' => 'AreaChart',
                        'BarChart' => 'BarChart',
                        'BubbleChart' => 'BubbleChart',
                        'CandlestickChart' => 'CandlestickChart',
                        'ColumnChart' => 'ColumnChart',
                        'ComboChart' => 'ComboChart',
                        'GeoChart' => 'GeoChart',
                        'LineChart' => 'LineChart',
                        'SankeyDiagram' => 'SankeyDiagram',
                        'ScatterChart' => 'ScatterChart',
                        'SteppedAreaChart' => 'SteppedAreaChart',
                        'TableChart' => 'TableChart'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
