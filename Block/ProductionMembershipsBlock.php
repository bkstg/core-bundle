<?php

namespace Bkstg\CoreBundle\Block;

use Bkstg\CoreBundle\Entity\ProductionMembership;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ProductionMembershipsBlock extends AbstractAdminBlockService
{
    protected $em;
    protected $token_storage;

    public function __construct(
        $name,
        EngineInterface $templating,
        EntityManagerInterface $em,
        TokenStorageInterface $token_storage
    ) {
        $this->token_storage = $token_storage;
        $this->em = $em;
        parent::__construct($name, $templating);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $membership_repo = $this->em->getRepository(ProductionMembership::class);
        $memberships = $membership_repo->findActiveMemberships($this->token_storage->getToken()->getUser());

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
            'memberships' => $memberships,
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'content' => 'Insert your custom content here',
            'template' => '@BkstgCore/Block/production_memberships.html.twig',
        ));
    }
}
