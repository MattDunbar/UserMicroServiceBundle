<?php
namespace MattDunbar\UserMicroServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uecode\Bundle\ApiKeyBundle\Entity\ApiKeyUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends ApiKeyUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}