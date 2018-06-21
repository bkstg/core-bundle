<?php

namespace Bkstg\CoreBundle\Doctrine\Handler;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Gedmo\Sluggable\Handler\SlugHandlerWithUniqueCallbackInterface;
use Gedmo\Sluggable\Mapping\Event\SluggableAdapter;
use Gedmo\Sluggable\SluggableListener;

class BlacklistSluggableHandler implements SlugHandlerWithUniqueCallbackInterface
{
    private $sluggable;
    private $used_options;

    /**
     * $options = array(
     *     'blacklist' => array(),
     * )
     *
     * {@inheritdoc}
     *
     * @param SluggableListener $sluggable The doctrine sluggable listener.
     */
    public function __construct(SluggableListener $sluggable)
    {
        $this->sluggable = $sluggable;
    }

    /**
     * {@inheritdoc}
     *
     * @param  SluggableAdapter $ea               The sluggable adapter.
     * @param  array            $config           The plugin config.
     * @param  mixed            $object           The object being acted on.
     * @param  mixed            $slug             The slug so far.
     * @param  mixed            $needToChangeSlug Whether or not to change the slug.
     * @return void
     */
    public function onChangeDecision(SluggableAdapter $ea, array &$config, $object, &$slug, &$needToChangeSlug): void
    {
        $this->used_options = $config['handlers'][get_called_class()];
    }

    /**
     * {@inheridoc}
     *
     * @param  SluggableAdapter $ea     The sluggable adapter.
     * @param  array            $config The plugin config.
     * @param  mixed            $object The object being acted on.
     * @param  mixed            $slug   The slug so far.
     * @return void
     */
    public function postSlugBuild(SluggableAdapter $ea, array &$config, $object, &$slug): void
    {
        // Function left intentionally blank.
    }

    /**
     * {@inheridoc}
     *
     * @param  SluggableAdapter $ea     The sluggable adapter.
     * @param  array            $config The plugin config.
     * @param  mixed            $object The object being acted on.
     * @param  mixed            $slug   The slug so far.
     * @return void
     */
    public function onSlugCompletion(SluggableAdapter $ea, array &$config, $object, &$slug): void
    {
        // If this is on the blacklist append a "-0".
        if (in_array($slug, $this->used_options['blacklist'])) {
            $slug .= '-0';
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    public function handlesUrlization(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array         $options The plugin options.
     * @param  ClassMetadata $meta    The doctrine class metadata.
     * @return void
     */
    public static function validate(array $options, ClassMetadata $meta): void
    {
        // Function left intentionally blank.
    }

    /**
     * {@inheritdoc}
     *
     * @param  SluggableAdapter $ea     The sluggable adapter.
     * @param  array            $config The plugin config.
     * @param  mixed            $object The object being acted on.
     * @param  mixed            $slug   The slug so far.
     * @return void
     */
    public function beforeMakingUnique(SluggableAdapter $ea, array &$config, $object, &$slug)
    {
        // If this is on the blacklist append a "-0".
        if (in_array($slug, $this->used_options['blacklist'])) {
            $slug .= '-0';
        }
    }
}
