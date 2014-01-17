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
    /**
     * @var Attendance[]
     *
     * @ORM\OneToMany(targetEntity="Attendance", mappedBy="user")
     */
    private $attendances;

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Attendance[] $attendances
     */
    public function setAttendances($attendances)
    {
        $this->attendances = $attendances;
    }

    /**
     * @return \Attendee\Bundle\ApiBundle\Entity\Attendance[]
     */
    public function getAttendances()
    {
        return $this->attendances;
    }


}
