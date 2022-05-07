<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Customer;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('diy district');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-bag', Order::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Customer::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-list', Category::class); 
        yield MenuItem::linkToCrud('Produits', 'fa fa-tags', Product::class);
        yield MenuItem::linkToCrud('Livreurs', 'fa fa-truck', Delivery::class);
        yield MenuItem::linkToCrud('Sliders', 'fa fa-image', Slider::class);
    }
}
