<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $em;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $em, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->em = $em;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fa fa-clock')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fa fa-truck')->linkToCrudAction('updateDelivery');
        $updateCancel = Action::new('updateCancel', 'Commande annulée', 'fa fa-ban')->linkToCrudAction('updateCancel');

        return $actions
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('detail', $updateCancel)
            ->add('index', 'detail');
    }

    public function configureCrud(Crud $crud): Crud
    {
        // Pour classer les commandes par ordre descendant
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(2);
        $this->em->flush();

        $this->addFlash('notice', '<span style="text-align: center; color:#FDCA40;"><strong>La commande n°' . $order->getReference() . ' a changée de statut. Elle est en cours de préparation</strong></span>');

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $title = "Hey, nous avons une bonne nouvelle pour vous !";
        $content = "Bonjour ". $order->getUser()->getLastName() ." " . $order->getUser()->getFirstName() ."<br/><br/>Encore merci pour votre commande n°<strong>" . $order->getReference() . "</strong> effectuée sur notre site.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'DiY District - Votre commande est en cours de préparation', $content, $title );
    
        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(3);
        $this->em->flush();

        $this->addFlash('notice', '<span style="text-align: center; color:#33A1FD;"><strong>La commande n°' . $order->getReference() . ' a changée de statut. Elle est en cours de livraison</strong></span>');

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $title = "Le livreur s'occupe de tout !";
        $content = "Bonjour ". $order->getUser()->getLastName() ." " . $order->getUser()->getFirstName() ."<br/><br/>Votre commande n°<strong>" . $order->getReference() . "</strong> vient de s'envoler vers sa destination. Elle ne devrait pas tarder à arriver chez vous";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'DiY District - Votre commande est en cours de livraison', $content, $title );
    
        return $this->redirect($url);
    }

    public function updateCancel(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(4);
        $this->em->flush();

        $this->addFlash('notice', '<span style="text-align: center; color:#AD2E24;"><strong>La commande n°' . $order->getReference() . ' a changée de statut. Elle est annulée</strong></span>');

        $url = $this->adminUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $title = "C'est dommage ...";
        $content = "Bonjour ". $order->getUser()->getLastName() ." " . $order->getUser()->getFirstName() ."<br/><br/>Votre commande n°<strong>" . $order->getReference() . "</strong> vient d'être annulée pour anomalie. N'hésitez pas à nous contacter si vous désirez en connaître la raison exacte";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'DiY District - Votre commande vient d\'être annulée', $content, $title );
    
        return $this->redirect($url);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            //Gestion des fiels coté EasyAdmin pour la partie Orders
            // IdField::new('ID', 'N°'),
            TextField::new('reference', 'Commande n°'),
            DateTimeField::new('createdAt', 'Date'),
            TextField::new('user.getFullName', 'Client'),
            EmailField::new('user.getEmail', 'Email')->onlyOnDetail(),
            TextEditorField::new('deliveryAddress', 'Adresse de livraison')->formatValue(function ($value) { return $value; })->onlyOnDetail(),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('deliveryName', 'Transporteur'),
            MoneyField::new('deliveryPrice', 'Fraid de Port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Statut')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Commande annulée' => 4,
            ]),
            ArrayField::new('orderDetails','Produits')->hideOnIndex(),
        ];
    }
    
}
