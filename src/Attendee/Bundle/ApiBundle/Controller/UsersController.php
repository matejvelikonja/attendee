<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class UsersController
 *
 * @Route("/users")
 *
 * @package Attendee\Bundle\ApiBundle\Controller
 */
class UsersController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     *
     * @Rest\View()
     * @Rest\Get("", name="api_users_index")
     *
     * @ApiDoc(
     *  description="Lists all users.",
     *  section="Users",
     *  parameters={
     *      {"name"="ids[]", "dataType"="integer", "required"=false}
     *  }
     * )
     *
     */
    public function indexAction(Request $request)
    {
        $ids = $request->get('ids');

        $users = $this->getUserService()->find($ids);

        return array(
            'users' => $users
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Rest\View
     * @Rest\Post("", name="api_users_create")
     *
     * @ApiDoc(
     *  section="User",
     *  description="Create user."
     * )
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $data = $request->request->get('user');
        $form = $this->createForm('user', new User());
        $form->submit($data);

        $user = $form->getData();
        $this->getUserService()->save($user);

        return $this->showAction($user);
    }

    /**
     * @param User $user
     *
     * @Rest\View()
     * @Rest\Get("/{id}", name="api_users_show")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="User detail.",
     *  section="User"
     * )
     *
     * @return array
     */
    public function showAction(User $user)
    {
        return array(
            'user' => $user
        );
    }
}
