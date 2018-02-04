<?php

namespace AppBundle\Domain\Calendar\Form\Type;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\CalendarMonth;
use AppBundle\Entity\Larp;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class CalendarDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $larp = $options['larp'];

        $builder
            ->add('nbDaysFromOrigin', HiddenType::class, [
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'query_builder' => function (EntityRepository $er) use ($larp) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.larp = :larp')
                        ->setParameter('larp', $larp)
                        ->orderBy('c.name', 'ASC');
                },
                'property_path' => 'calendar',
                'label' => 'calendar_date_type.calendar',
            ])

            ->add('year', IntegerType::class, [
                'mapped' => false,
                'property_path' => 'year',
                'label' => 'calendar_date_type.year',
            ])
            ->add('month', EntityType::class, [
                'mapped' => false,
                'property_path' => 'month',
                'label' => 'calendar_date_type.month',
                'class' => CalendarMonth::class,
                'query_builder' => function (EntityRepository $er) use ($larp) {
                    return $er->createQueryBuilder('m')
                        ->innerJoin('m.calendar', 'c')
                        ->andWhere('c.larp = :larp')
                        ->setParameter('larp', $larp)
                        ->orderBy('m.position', 'ASC');
                },
                'choice_attr' => function ($val, $key, $index) {
                    return [
                        'data-related-calendar' => $val->getCalendar()->getId(),
                        'data-month-number' => $val->getNumber(),
                        'data-nb-days' => $val->getNbDays(),
                    ];
                },
            ])
            ->add('day', IntegerType::class, [
                'mapped' => false,
                'property_path' => 'day',
                'label' => 'calendar_date_type.day',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['larp']);

        $resolver->setAllowedTypes('larp', Larp::class);

        $resolver->setDefaults([
            'translation_domain' => 'Calendar',
            'by_reference' => false,
        ]);
    }
}
