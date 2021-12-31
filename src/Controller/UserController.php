<?php

namespace App\Controller;


use App\Entity\Products;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractApiController
{
    /**
     * @param Request $request
     */
    public function indexAction(Request $request): Response
    {
        if ($this->checkRole())
            return $this->checkRole();
        $User = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->respond($User,200);
    }
    public function meAction(): Response
    {
        return $this->respond($this->getCurrentUser(),200);
    }
}