<?php

namespace App\EventListener;

use Knp\Menu\ItemInterface;
use Survos\BarcodeBundle\Service\BarcodeService;
use Survos\BootstrapBundle\Event\KnpMenuEvent;
use Survos\BootstrapBundle\Menu\MenuBuilder;
use Survos\BootstrapBundle\Traits\KnpMenuHelperInterface;
use Survos\BootstrapBundle\Traits\KnpMenuHelperTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsEventListener(event: KnpMenuEvent::SIDEBAR_MENU_EVENT, method: 'sidebarMenu')]
#[AsEventListener(event: KnpMenuEvent::PAGE_MENU_EVENT, method: 'pageMenu')]
#[AsEventListener(event: KnpMenuEvent::NAVBAR_MENU_EVENT, method: 'navbarMenu')]

final class AppMenuEventListener implements KnpMenuHelperInterface
{
    use KnpMenuHelperTrait;

    // this should be optional, not sure if we really need it here.
    public function __construct(
//        private BarcodeService $barcodeService,
        private ?AuthorizationCheckerInterface $security=null)
    {
    }

    public function sidebarMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();

        $this->addHeading($menu, 'Generators');
        foreach ($this->barcodeService->getGeneratorClasses() as $code => $generatorClass) {
            $this->add($menu, 'app_demo', ['generatorCode' => $code], label: $code);
        }
        $options = $event->getOptions();

//        $this->add($menu, 'app_homepage');
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem

        $nestedMenu = $this->addSubmenu($menu, 'Credits');
        foreach (['bundles', 'javascript'] as $type) {
            // $this->addMenuItem($nestedMenu, ['route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
            $this->addMenuItem($nestedMenu, ['uri' => "#$type" , 'label' => ucfirst($type)]);
        }
    }

public function pageMenu(KnpMenuEvent $event): void
{
}

public function navbarMenu(KnpMenuEvent $event): void
{

}

}
