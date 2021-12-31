<?php

namespace App\Controller;


use App\Entity\Company;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\User;
use App\Form\Type\OrderType;
use App\Form\Type\OrderUpdateType;
use App\Form\Type\ProductType;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 * @package App\AbstractApiController
 */
class OrderController extends AbstractApiController
{
    /**
     * @param Request $request
     */
    public function indexAction(Request $request): Response
    {
        $order = $this->getDoctrine()->getRepository(Orders::class)->findAll();
        return $this->respond($order);
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(OrderType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond([], Response::HTTP_BAD_REQUEST, $form);
        }

        $findProduct = $this->getDoctrine()->getRepository(Products::class)->find($request->get('product'));
        if (!$findProduct) {
            return $this->respond('Error');
        }

        $order = $form->getData();
        $order->setOrderCode(mb_strtolower(substr(uniqid('', ''), 0, 8)));
        $order->setUser($this->getCurrentUser());
        $order->setProduct($findProduct);

        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($order);
    }


    public function singleAction($id): Response
    {

        $order = $this->getDoctrine()->getRepository(Orders::class)->find($id);

        if (!$order) {
            return $this->respond($order, Response::HTTP_BAD_REQUEST, 'Product Not Found');
        }
        if($order->getUser() !== $this->getCurrentUser())
            return $this->checkRole("ROLE_COMPANY_ADMIN");

        return $this->respond($order,200);
    }

    public function updateAction(Request $request, $id): Response
    {
        // PERMISSION CONTROL | DEFAULT SUPER_ADMIN
        if ($this->checkRole("ROLE_COMPANY_ADMIN"))
            return $this->checkRole("ROLE_COMPANY_ADMIN");

        $entityManager = $this->getDoctrine()->getManager();
        try {
            $formatShippingDate = new DateTime($request->get('shippingDate'));
        } catch (Exception $e) {
            return $this->respond('Error Date Format', 400);
        }
        $findOrder = $this->getDoctrine()->getRepository(Orders::class)->find($id);

        if (!$findOrder)
            return $this->respond('Order Not found ', 400);
        if ($findOrder->getShippingDate() !== null)
            return $this->respond('Error shippingDate already exists', 400);
        $findOrder->setShippingDate(new DateTime($request->get('shippingDate')));

        $entityManager->flush();
        return $this->respond($findOrder, 200, false);
    }


}