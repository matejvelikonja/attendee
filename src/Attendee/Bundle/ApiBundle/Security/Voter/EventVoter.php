<?php

namespace Attendee\Bundle\ApiBundle\Security\Voter;

use Attendee\Bundle\ApiBundle\Entity\Event;
use Attendee\Bundle\ApiBundle\Service\EventService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class EventVoter
 *
 * @package Attendee\Bundle\ApiBundle\Security\Voter
 *
 * @DI\Service("attendee.security.voters.event", public=false)
 * @DI\Tag("security.voter")
 */
class EventVoter implements VoterInterface
{
    /**
     * @var \Attendee\Bundle\ApiBundle\Service\EventService
     */
    private $eventService;

    /**
     * @param EventService $eventService
     *
     * @DI\InjectParams({
     *     "eventService" = @DI\Inject("attendee.event_service")
     * })
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
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
        return get_class(new Event()) === $class;
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param Event          $event      The object to secure
     * @param array          $attributes An array of attributes associated with the method being invoked
     *
     * @return integer either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $event, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        if (!$this->supportsClass(get_class($event))) {
            return $result;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;
            if ($this->eventService->isManager($token->getUser(), $event)) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return $result;
    }
}