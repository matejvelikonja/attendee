<?php

namespace Attendee\Bundle\ApiBundle\Security\Voter;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Attendee\Bundle\ApiBundle\Service\TeamService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class TeamVoter
 *
 * @package Attendee\Bundle\ApiBundle\Security\Voter
 *
 * @DI\Service("attendee.security.voters.team", public=false)
 * @DI\Tag("security.voter")
 */
class TeamVoter implements VoterInterface
{
    /**
     * @var \Attendee\Bundle\ApiBundle\Service\TeamService
     */
    private $teamService;

    /**
     * @param TeamService $teamService
     *
     * @DI\InjectParams({
     *     "teamService" = @DI\Inject("attendee.team_service")
     * })
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return Boolean true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        return 'MANAGER' === $attribute;
    }

    /**
     * Checks if the voter supports the given class.
     *
     * @param string $class A class name
     *
     * @return Boolean true if this Voter can process the class
     */
    public function supportsClass($class)
    {
        return get_class(new Team()) === $class;
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param Team           $team       The object to secure
     * @param array          $attributes An array of attributes associated with the method being invoked
     *
     * @return integer either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $team, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        if (!$this->supportsClass(get_class($team))) {
            return $result;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;
            if ($this->teamService->isManager($token->getUser(), $team)) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return $result;
    }
}