<?php

namespace EVT\IntranetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeadController extends Controller
{
    /**
     * @Route("/leads")
     */
    public function listAction(Request $request)
    {
        $filter = '';
        foreach ($request->query as $key => $param) {
            if ($param != '') {
                $filter .= '&' . $key . '=' . $param;
            }
        }

        $leadsResponse = $this->container->get('evt.core.client')
            ->get('/api/leads?' . $filter);

        if (404 == $leadsResponse->getStatusCode() && $request->query->get('page', 1)>1) {
            throw new NotFoundHttpException();
        }

        $leads = [];
        if (isset($leadsResponse->getBody()['items'])) {
            $leads = $leadsResponse->getBody()['items'];
        }

        $pagination = [];
        if (isset($leadsResponse->getBody()['pagination'])) {
            $pagination = $leadsResponse->getBody()['pagination'];
        }

        $verticalsResponse = $this->container->get('evt.core.client')
            ->get('/api/verticals');

        $content = $this->renderView(
            'EVTIntranetBundle:Lists:leads.html.twig',
            [
                "leads" => $leads,
                "pagination" => $pagination,
                "routeController" => "evt_intranet_lead_list",
                "verticals" => $verticalsResponse->getBody()
            ]
        );

        return new Response($content);
    }

    /**
     * @Route("/leads/{id}", requirements={"id" = "\d+"})
     */
    public function showLeadAction($id)
    {
        $leadResponse = $this->container->get('evt.core.client')->get('/api/leads/'.$id);
        $lead = $leadResponse->getBody();

        $content = $this->renderView('EVTIntranetBundle:Lists:lead.html.twig', ["lead" => $lead]);
        $role = $this->get('security.context')->getToken()->getRoles()[0]->getRole();
        if ($role == 'ROLE_MANAGER' && !isset($lead['read_at'])) {
            $this->container->get('evt.core.client')->patch('/api/leads/'.$id.'/read');
        }

        return new Response($content);
    }
}
