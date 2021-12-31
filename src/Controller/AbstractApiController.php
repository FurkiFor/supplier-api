<?php

namespace App\Controller;


use App\Entity\Company;
use App\Entity\User;
use FOS\RestBundle\FOSRestBundle;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\AbstractSurrogate;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 * @package App\Controller
 */
abstract class AbstractApiController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $role
     */
    public function checkRole(string $role = 'ROLE_SUPER_ADMIN')
    {
        if (in_array('ROLE_SUPER_ADMIN', $this->getCurrentUser()->getRoles()))
            if (!in_array($role, $this->getCurrentUser()->getRoles()))
                return $this->respond('Forbidden', Response::HTTP_FORBIDDEN);
        return false;

    }

    public function getCurrentUser(): ?UserInterface
    {
        return $this->security->getUser();
    }

    public function respond($data, $statusCode = 200, $error = ''): Response
    {
        if (is_array($error))
            return $this->json($error, $statusCode);

        return $this->json([
            "data" => $data,
            "statusCode" => $statusCode,
            "success" => $statusCode == 200,
            "error" => $statusCode != 200,
        ], $statusCode);
    }

    /**
     * @return object|null
     */
    protected function getCurrentCompany()
    {
        return $this->getDoctrine()->getRepository(Company::class)->find(1);
    }

    protected function buildForm(string $type, $data = null, array $options = [])
    {
        $options = array_merge($options, [
            'csrf_protection' => false,
        ]);
        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }
}