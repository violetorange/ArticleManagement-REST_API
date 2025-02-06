<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(NormalizerInterface $normalizer, #[CurrentUser] ?User $user = null): Response
    {
        return $this->render('security/login.html.twig', [
            'userData' => $normalizer->normalize($user, 'jsonld', [
                'groups' => ['user:read'],
            ]),
        ]);
    }

    #[Route('/authorization', name: 'app_authorization', methods: ['POST'])]
    public function authorization(#[CurrentUser] ?User $user = null): Response
    {
        return $this->json([
            'user' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('logout() should never be reached');
    }
}
