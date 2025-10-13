<?php
namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginSuccessListener
{
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $event->getRequest();
        $request->getSession()->set('login_at', time());
    }
}