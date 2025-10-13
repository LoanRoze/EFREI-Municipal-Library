<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UiController extends AbstractController
{
    #[Route('/toggle-theme/{mode}', name: 'toggle_theme')]
    public function toggleTheme(string $mode, Request $request): Response
    {
        $value = ($mode === 'dark') ? 'true' : 'false';
        $response = $this->redirect($request->headers->get('referer', '/'));
        $cookie = Cookie::create('myapp_dark_mode', $value, (new \DateTime())->modify('+1 year'));
        $response->headers->setCookie($cookie);
        return $response;
    }
}
