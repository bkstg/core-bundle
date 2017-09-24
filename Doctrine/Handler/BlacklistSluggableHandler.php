<?php

namespace Bkstg\CoreBundle\Doctrine\Handler;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Gedmo\Sluggable\Handler\SlugHandlerWithUniqueCallbackInterface;
use Gedmo\Sluggable\Mapping\Event\SluggableAdapter;
use Gedmo\Sluggable\SluggableListener;

/**
 * Slug handler that "blacklists" slugs.
 *
 * This can be useful to prevent system paths from being overwritten with a
 * slug. This is useful when a slug is the first part of a path.
 */
class BlacklistSluggableHandler implements SlugHandlerWithUniqueCallbackInterface
{
    private $sluggable;
    private $usedOptions;

    /**
     * $options = array(
     *     'blacklist' => array(),
     * )
     * {@inheritdoc}
     */
    public function __construct(SluggableListener $sluggable)
    {
        $this->sluggable = $sluggable;
    }

    /**
     * {@inheritdoc}
     */
    public function onChangeDecision(SluggableAdapter $ea, array &$config, $object, &$slug, &$needToChangeSlug)
    {
        $this->usedOptions = $config['handlers'][get_called_class()];
    }

    /**
     * {@inheritdoc}
     */
    public function postSlugBuild(SluggableAdapter $ea, array &$config, $object, &$slug)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onSlugCompletion(SluggableAdapter $ea, array &$config, $object, &$slug)
    {
        if (in_array($slug, $this->usedOptions['blacklist'])) {
            $slug .= '-0';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handlesUrlization()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function validate(array $options, ClassMetadata $meta)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeMakingUnique(SluggableAdapter $ea, array &$config, $object, &$slug)
    {
        if (in_array($slug, $this->usedOptions['blacklist'])) {
            $slug .= '-0';
        }
    }
}
