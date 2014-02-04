<?php

namespace Attendee\Bundle\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/", methods="GET", name="api_teams_index")
     *
     * @ApiDoc(
     *  section="Teams",
     *  description="Lists all teams of current user.",
     *  parameters={
     *      {"name"="limit",  "dataType"="integer", "required"=false, "description"="Limit number of results."},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="Offset results."}
     *  }
     * )
     */
    public function indexAction(Request $request)
    {
        $limit  = $request->get('limit', 15);
        $offset = $request->get('offset');
        $teams  = $this->getTeamService()->findForUser($this->getUser(), $limit, $offset);

        return $this->createResponse(
            array(
                'teams' => $teams
            )
        );
    }
}
