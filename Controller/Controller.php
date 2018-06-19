<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

abstract class Controller
{
    protected $templating;
    protected $session;
    protected $form;
    protected $em;
    protected $translator;
    protected $url_generator;

    public function __construct(
        Environment $templating,
        SessionInterface $session,
        FormFactoryInterface $form,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        UrlGeneratorInterface $url_generator
    ) {
        $this->templating = $templating;
        $this->session = $session;
        $this->form = $form;
        $this->em = $em;
        $this->translator = $translator;
        $this->url_generator = $url_generator;
    }

    /**
     * Helper function that looks up entities for controllers.
     *
     * Checks that entities are proper members of productions and throws not
     * found exceptions when they are not.
     *
     * @param  string                $entity_class    The class to lookup the entity for.
     * @param  int                   $entity_id       The entity id.
     * @param  string                $production_slug The production slug.
     * @throws NotFoundHttpException                  If the production or entity is not found or the entity is not in the production.
     * @return array                                  The production and the entity.
     */
    protected function lookupEntity(
        string $entity_class,
        int $entity_id,
        string $production_slug
    ): array {
        // Lookup the production by production_slug.
        $production_repo = $this->em->getRepository(Production::class);
        if (null === $production = $production_repo->findOneBy(['slug' => $production_slug])) {
            throw new NotFoundHttpException();
        }

        // Lookup the entity by id.
        $entity_repo = $this->em->getRepository($entity_class);
        if (null === $entity = $entity_repo->findOneBy(['id' => $entity_id])) {
            throw new NotFoundHttpException();
        }

        // Ensure entity is within production.
        if (!$entity->hasGroup($production)) {
            throw new NotFoundHttpException();
        }

        // Return the entity and the production.
        return [$entity, $production];
    }
}
