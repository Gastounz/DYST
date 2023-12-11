<?php

namespace App\Service;

use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private RequestStack $requestStack;
    private EntityManagerInterface $em;
    private $tax = 0.2;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function getCart()
    {
        return $this->getSession()->get('cart', []);
    }

    public function updateCart($cart): void
    {
        $this->getSession()->set('cart', $cart);
        $this->getSession()->set('cartData', $this->getCartData());
    }

    public function getCartData()
    {
        $cart = $this->getCart();
        $cartData = [];
        $cartAmount = 0;
        $subtotal = 0;

        foreach ($cart as $id => $amount) {
            $product = $this->em->getRepository(Formation::class)->findOneBy(['id' => $id]);

            if (!$product) {
                $this->removeFromCart($id);
            }

            $cartData['products'][] = [
                'product' => $product,
                'amount' => $amount
            ];

            $cartAmount += $amount;

            // WARNING : Régler l'écart de virgule selon les données de la base
            $subtotal += $amount * $product->getPrice();
        }

        $cartData['data'] = [
            'amount' => $cartAmount,
            'subtotalHT' => $subtotal,
            'tax' => round($subtotal * $this->tax, 2),
            'subtotalTTC' => round($subtotal + ($subtotal * $this->tax), 2)
        ];

        return $cartData;
    }

    public function addToCart(int $id): void
    {
        $cart = $this->getCart();

        // Si l'article est dans le panier, l'incrémente, sinon l'ajoute
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->updateCart($cart);
    }

    public function removeFromCart(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        return $this->getSession()->set('cart', $cart);
    }

    public function removeAll()
    {
        return $this->getSession()->remove('cart');
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
