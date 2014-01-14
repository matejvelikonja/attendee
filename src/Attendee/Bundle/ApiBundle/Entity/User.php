<?php

namespace Attendee\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Attendee\Bundle\UserBundle\Entity\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class User extends BaseUser
{

}
