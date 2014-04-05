<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Attendee\Bundle\ApiBundle\Entity\Team;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class TeamsController
 *
 * @Route("/teams")
 *
 * @package Attendee\Bundle\ApiBundle\Controller
 */
class TeamsController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @Rest\View
     * @Rest\Get("", name="api_teams_index")
     *
     * @ApiDoc(
     *  section="Teams",
     *  description="Lists all teams of current user.",
     *  parameters={
     *      {"name"="limit",  "dataType"="integer", "required"=false, "description"="Limit number of results."},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="Offset results."}
     *  }
     * )
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $limit  = $request->get('limit', 15);
        $offset = $request->get('offset');
        $teams  = $this->getTeamService()->findForUser($this->getUser(), $limit, $offset);
        $users  = $this->getUserService()->findByTeams($teams);

        return array(
            'teams' => $teams,
            'users' => $users
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Rest\View
     * @Rest\Post("", name="api_teams_create")
     *
     * @ApiDoc(
     *  section="Teams",
     *  description="Create event.",
     *  parameters={
     *      {"name"="name",  "dataType"="string", "required"=true, "description"="Name of the newly created team."},
     *  }
     * )
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $data = $request->request->get('team');
        $name = $data['name'];
        $team = $this->getTeamService()->create($name, $this->getUser());

        return array(
            'team' => $team
        );
    }

    /**
     * @param Team $team
     *
     * @Rest\View
     * @Rest\Get("/{id}", name="api_teams_show")
     * @SecureParam(name="team", permissions="MANAGER")
     *
     * @ApiDoc(
     *  section="Teams",
     *  description="Team detail."
     * )
     *
     * @return array
     */
    public function showAction(Team $team)
    {
        $users = $this->getUserService()->findByTeam($team);

        return array(
            'team'  => $team,
            'users' => $users
        );
    }

    /**
     * @param \Attendee\Bundle\ApiBundle\Entity\Team    $team
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Rest\View
     * @Rest\Put("/{id}", name="api_teams_update")
     *
     * @ApiDoc(
     *  section="Teams",
     *  description="Update event."
     * )
     *
     * @return array
     */
    public function updateAction(Team $team, Request $request)
    {
        $data = $request->request->get('team');
        $form = $this->createForm('team', $team);
        $form->submit($data);

        $this->getTeamService()->save($team);

        return $this->showAction($team);
    }
}
