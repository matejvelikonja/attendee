<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UsersController
 *
 * @Route("/users")
 *
 * @package   Attendee\Bundle\ApiBundle\Controller
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", methods="GET")
     *
     * @ApiDoc(
     *  description="Lists all users.",
     *  section="Users",
     *  parameters={
     *      {"name"="ids[]", "dataType"="integer", "required"=false}
     *  }
     * )
     */
    public function indexAction()
    {
        $ids = $this->getRequest()->get('ids');

        $users = $this->getUserService()->find($ids);

        return $this->createResponse(
            array(
                'users' => $users
            )
        );
    }

    /**
     * @param User $user
     *
     * @Route("/{id}", methods="GET")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="User detail.",
     *  section="User"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showAction(User $user)
    {
        return $this->createResponse(
            array(
                'user' => $user
            )
        );
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepo()
    {
        return $this->getDoctrine()->getRepository('AttendeeApiBundle:User');
    }
}
