<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/cart.html.twig', ['cart' => $cartService->getCartData()]);
    }

    #[Route('/cart/add/{id<\d+>}', name: 'cart_add')]
    public function add(int $id, CartService $cartService): Response
    {
        $cartService->addToCart($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id<\d+>}', name: 'cart_remove')]
    public function remove(int $id, CartService $cartService): Response
    {
        $cartService->removeFromCart($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/removeall', name: 'cart_removeall')]
    public function removeAll(CartService $cartService): Response
    {
        $cartService->removeAll();

        return $this->redirectToRoute('cart');
    }
}
