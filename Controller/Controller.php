<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
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

    /**
     * Construct a new controller with several common services available.
     *
     * @param Environment            $templating    The twig environment service.
     * @param SessionInterface       $session       The session service.
     * @param FormFactoryInterface   $form          The form service.
     * @param EntityManagerInterface $em            The entity manager service.
     * @param TranslatorInterface    $translator    The translator service.
     * @param UrlGeneratorInterface  $url_generator The url generator service.
     */
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
     * @param string $entity_class    The class to lookup the entity for.
     * @param int    $entity_id       The entity id.
     * @param string $production_slug The production slug.
     *
     * @throws NotFoundHttpException If anything is not found.
     *
     * @return array The production and the entity.
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
