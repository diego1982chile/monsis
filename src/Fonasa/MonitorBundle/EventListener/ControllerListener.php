<?php

namespace Fonasa\MonitorBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Controller\TraceableControllerResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class ControllerListener
{    
        
    protected $router;
    protected $security;        
    //protected $resolver;
    protected $tokenStorage;
    protected $session;


    public function __construct(Router $router, AuthorizationChecker $security, /*TraceableControllerResolver $resolver,*/
                                TokenStorage $tokenStorage = null, Session $session) {
        $this->router = $router;		
        $this->security = $security;                
        //$this->resolver = $resolver;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }
        
    public function onKernelController(FilterControllerEvent $event)
    {        	
        $request = $event->getRequest();                                        
        $routeName = $request->getPathInfo('_route');                                   
        
        /*
        if ($this->tokenStorage->getToken() != null && explode('/',$routeName)[1] != "login" &&
            $this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                        
            $event->setController($this->resolver->getController($request));						
            $request->attributes->set('_controller', 'FrameworkBundle:Redirect:Redirect');
            $request->attributes->set('_route', $this->router->generate('fos_user_security_login'));
            $event->setController($this->resolver->getController($request));						                        
        }
        */        
        
        //$_SESSION['routes'][]=explode('/',$routeName)[1];          
        
        switch (explode('/',$routeName)[1]){
            case 'servicio':
                $this->session->set('active', 'incidencia_index');
                break;            
            case 'new':                
                $this->session->set('active', 'servicio_new');
                break;            
        }
        
        if ($this->tokenStorage->getToken() != null && explode('/',$routeName)[1] == "login" &&
            $this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            /*          
            $event->setController($this->resolver->getController($request));						
            $request->attributes->set('_controller', 'MonitorBundle:Dashboard:index');
            $request->attributes->set('_route', $this->router->generate('dashboard_index'));
            $event->setController($this->resolver->getController($request));	
            */
            $this->tokenStorage->setToken(null);
            $request->getSession()->invalidate();
        }
          
        /*
        if ($this->tokenStorage->getToken() != null && in_array(explode('/',$routeName)[1],["servicio","new","assign","edit"]) &&
            !$this->security->isGranted('ROLE_ADMIN')) {
            $this->tokenStorage->setToken(null);
            $request->getSession()->invalidate();
        }
        
        if ($this->tokenStorage->getToken() != null && in_array(explode('/',$routeName)[1],["dashboard"]) &&
            $this->security->isGranted('ROLE_ADMIN')) {
            $this->tokenStorage->setToken(null);
            $request->getSession()->invalidate();
        }
        */
    }
}