<?php

namespace App\Controller;

use App\Entity\Formation;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $em;
    private CartService $cartService;
    private UrlGeneratorInterface $generator;

    public function __construct(EntityManagerInterface $em, CartService $cartService, UrlGeneratorInterface $generator)
    {
        $this->em = $em;
        $this->cartService = $cartService;
        $this->generator = $generator;
    }

    #[Route('/create-session-stripe', name: 'payment')]
    public function stripeCheckout(UserInterface $user): RedirectResponse
    {
        $cart = $this->cartService->getCartData();

        if (!$cart) {
            return $this->redirectToRoute('home');
        }

        Stripe::setApiKey(apiKey: 'sk_test_51OHjAKD7dz5NmKx1uiDHTzrP34iGOXuAlpQX1Jy4EvED9f00h5MMVh5ZbjThtYNNUKI9842dEYk97ZHfY63XGbhE00DGILg3Pm');

        $items = [];

        foreach ($cart['products'] as $item) {
            $items[] = [
                'quantity' => $item['amount'],
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['product']->getPrice(),
                    'product_data' => [
                        'name' => $item['product']->getName()
                    ]
                ]
            ];
        }

        $checkout_session = Session::create([
            'ui_mode' => 'embedded',
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $items,
            'mode' => 'payment',
            'return_url' => $this->generator->generate('checkout_return', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);

        return new RedirectResponse($checkout_session->return_url);
    }

    #[Route('/return', name: 'checkout_return')]
    public function stripeSuccess(CartService $cart): Response
    {
        return $this->render('cart/return.html.twig');
    }
}
