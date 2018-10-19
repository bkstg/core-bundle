<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGallery as BaseGallery;

class Gallery extends BaseGallery
{
    protected $id;

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
