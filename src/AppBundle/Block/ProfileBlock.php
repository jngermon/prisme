<?php

namespace AppBundle\Block;

use AppBundle\Security\ProfileProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ProfileBlock extends AbstractAdminBlockService
{
    protected $profileProvider;

    public function __construct(
        $name,
        EngineInterface $engine,
        ProfileProvider $profileProvider
    ) {
        parent::__construct($name, $engine);

        $this->profileProvider = $profileProvider;
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {

        return $this->renderResponse('AppBundle:Default:profile-block.html.twig', [
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
            'activeProfile' => $this->profileProvider->getActiveProfile(),
            'profiles' => $this->profileProvider->getProfiles(),
        ], $response);
    }
}
