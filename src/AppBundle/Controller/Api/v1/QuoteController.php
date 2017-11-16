<?php

namespace AppBundle\Controller\Api\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Quote;

class QuoteController extends FOSRestController {

    /**
     * @Rest\Get("/api/v1/quote")
     */
    public function getAction() {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Quote')->findAll();

        if (!$restresult) {
            return $this->json(array('message' => 'there are no quotes yet'));
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/api/v1/quote/{id}")
     */
    public function idAction($id) {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Quote')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/api/v1/quote")
     */
    public function postAction(Request $request) {
        $data = new Quote;
        $quoteQuery = $request->get('quote');
        $sourceQuery = $request->get('source');
        if (empty($quoteQuery) || empty($sourceQuery)) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setQuote($quoteQuery);
        $data->setSource($sourceQuery);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("Quote Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/api/v1/quote/{id}")
     */
    public function updateAction($id, Request $request) {
        $data = new Quote;
        $quoteQuery = $request->get('quote');
        $sourceQuery = $request->get('source');
        $sn = $this->getDoctrine()->getManager();
        $quote = $this->getDoctrine()->getRepository('AppBundle:Quote')->find($id);
        if (empty($quote)) {
            return new View("Quote not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($quoteQuery) && !empty($sourceQuery)) {
            $user->setName($quoteQuery);
            $user->setRole($sourceQuery);
            $sn->flush();
            return new View("Quote Updated Successfully", Response::HTTP_OK);
        } elseif (empty($quoteQuery) && !empty($sourceQuery)) {
            $user->setRole($sourceQuery);
            $sn->flush();
            return new View("Quote Source Updated Successfully", Response::HTTP_OK);
        } elseif (!empty($quoteQuery) && empty($sourceQuery)) {
            $user->setName($quoteQuery);
            $sn->flush();
            return new View("Quote Text Updated Successfully", Response::HTTP_OK);
        } else {
            return new View("Quote Text and Source cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    
     /**
     * @Rest\Delete("/api/v1/quote/{id}")
     */
    public function deleteAction($id) {
        $data = new Quote;
        $sn = $this->getDoctrine()->getManager();
        $quote = $this->getDoctrine()->getRepository('AppBundle:Quote')->find($id);
        if (empty($quote)) {
            return new View("Quote not found", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($quote);
            $sn->flush();
        }
        return new View("Quote deleted successfully", Response::HTTP_OK);
    }

}    