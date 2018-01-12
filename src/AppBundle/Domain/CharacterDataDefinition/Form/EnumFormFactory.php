<?php

namespace AppBundle\Domain\CharacterDataDefinition\Form;

use AppBundle\Entity\CharacterDataDefinition;
use AppBundle\Entity\CharacterDataDefinitionEnum;
use AppBundle\Entity\CharacterDataDefinitionEnumCategoryItem;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormFactory;

class EnumFormFactory extends BaseFormFactory
{
    protected $repository;

    public function __construct(
        FormFactory $factory,
        EntityRepository $repository
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function supports($request)
    {
        return $request instanceof CharacterDataDefinitionEnum;
    }

    protected function getTypeClass(CharacterDataDefinition $definition)
    {
        return EntityType::class;
    }

    protected function getOptions(CharacterDataDefinition $definition)
    {
        return array_merge(parent::getOptions($definition), [
            'class' => CharacterDataDefinitionEnumCategoryItem::class,
            'query_builder' => function (EntityRepository $er) use ($definition) {
                return $er->createQueryBuilder('i')
                    ->andWhere('i.category = :category')
                    ->setParameter('category', $definition->getCategory())
                    ->orderBy('i.position', 'ASC')
                    ;
            }
        ]);
    }

    protected function doProcess($request)
    {
        $builder = parent::doProcess($request);

        $builder->addModelTransformer(new CallbackTransformer(
            function ($id) {
                return $id ? $this->repository->findOneById($id) : null;
            },
            function ($value) {
                return $value->getId();
            }
        ));

        return $builder;
    }
}
