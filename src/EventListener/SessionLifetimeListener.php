<?php 
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class SessionLifetimeListener
{
    private UrlGeneratorInterface $urlGenerator;
    private int $maxSeconds;

    public function __construct(UrlGeneratorInterface $urlGenerator, int $maxSeconds = 172800)
    {
        $this->urlGenerator = $urlGenerator;
        $this->maxSeconds = $maxSeconds; // 48h = 172800s
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        if (!$session instanceof SessionInterface) {
            return;
        }

        // si l'utilisateur n'est pas connecté, on ne fait rien ici
        if (!$request->getUser()) {
            return;
        }

        $loginAt = $session->get('login_at');
        if ($loginAt === null) {
            // initialiser la date de connexion
            $session->set('login_at', time());
            return;
        }

        if (time() - $loginAt > $this->maxSeconds) {
            // expire session + redirect vers login
            $session->invalidate();
            $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
            $event->setResponse($response);
        }
    }
}
