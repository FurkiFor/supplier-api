<?php

namespace App\Controller;


use App\Entity\Company;
use App\Entity\Products;
use App\Entity\User;
use App\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractApiController
{
    /**
     * @param Request $request
     */
    public function indexAction(Request $request): Response
    {
        $product = $this->getDoctrine()->getRepository(Products::class)->findAll();
        return $this->respond($product);
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(ProductType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return  $this->respond([], Response::HTTP_BAD_REQUEST, $form);
        }
        $product = $form->getData();
        $product->setCompany($this->getCurrentCompany());
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($product);
    }


    public function singleAction($id) : Response
    {
        $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
        if(!$product){
            return $this->respond($product, Response::HTTP_BAD_REQUEST, 'Product Not Found');
        }
        return $this->respond($product);
   }

}