<?php

namespace Bkstg\CoreBundle\Controller;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\Form\Type\ProductionSettingsType;
use Bkstg\CoreBundle\Form\Type\ProductionType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductionSettingsController extends Controller
{
    public function generalAction(
        $production_slug,
        Request $request,
        AuthorizationCheckerInterface $auth
    ) {
        if (null === $production = $this->em->getRepository(Production::class)->findOneBy(['slug' => $production_slug])) {
            throw new NotFoundHttpException();
        }
        if (!$auth->isGranted('GROUP_ROLE_ADMIN', $production)) {
            throw new AccessDeniedException();
        }

        $form = $this->form->create(ProductionSettingsType::class, $production);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->em->persist($production);
            $this->em->flush();

            // Set success message.
            $this->session->getFlashBag()->add(
                'success',
                $this->translator->trans('Production "%production%" saved.', [
                    '%production%' => $production->getName(),
                ])
            );

            return new RedirectResponse($this->url_generator->generate('bkstg_production_settings_general', [
                'production_slug' => $production->getSlug(),
            ]));
        }

        return new Response($this->templating->render('@BkstgCore/Production/settings-general.html.twig', [
            'form' => $form->createView(),
            'production' => $production,
        ]));
    }
}
