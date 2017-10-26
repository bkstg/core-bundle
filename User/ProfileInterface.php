<?php

namespace Bkstg\CoreBundle\User;

interface ProfileInterface
{
    public function getName();
    public function getEmail();
    public function getPhone();
    public function getHeight();
    public function getWeight();
    public function getImage();
    public function getFacebook();
    public function getTwitter();
}
