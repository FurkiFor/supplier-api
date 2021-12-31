<?php
/**
 * Created by PhpStorm.
 * User: hicham benkachoud
 * Date: 06/01/2020
 * Time: 20:39
 */

namespace App\Controller;


use App\Entity\Company;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractApiController
{

    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $connection = $this->getDoctrine()->getManager();
   //     $request = $this->transformJsonBody($request);
        $password = $request->get('password');
        $email = $request->get('email');
        $companyId = $request->get('company_id');
        $name = $request->get('name');
        if (empty($password) || empty($email) || empty($companyId) || empty($name)){
            return $this->json("Invalid  Password or Email or name or companyId",401);
        }
        $company =  $this->getDoctrine()->getRepository(Company::class)->find($request->get('company_id'));
        if(!$company){
            return $this->json("Company Not Found",401);
        }
        $user = new User();
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setName($name);
        $user->setCompany($company);
        $user->setRoles(["ROLE_COMPANY_USER"]);
        $connection->persist($user);
        $connection->flush();
        return $this->json(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

}