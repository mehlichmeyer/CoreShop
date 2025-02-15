<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\CoreBundle\Validator\Constraints;

use CoreShop\Bundle\CoreBundle\Validator\QuantityValidatorService;
use CoreShop\Bundle\OrderBundle\DTO\AddToCartInterface;
use CoreShop\Component\Core\Model\CartInterface;
use CoreShop\Component\Core\Model\CartItemInterface;
use CoreShop\Component\Core\Model\ProductInterface;
use CoreShop\Component\Inventory\Model\StockableInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class AddToCartQuantityValidator extends ConstraintValidator
{
    /**
     * @var QuantityValidatorService
     */
    private $quantityValidatorService;

    /**
     * @param QuantityValidatorService $quantityValidatorService
     */
    public function __construct(QuantityValidatorService $quantityValidatorService)
    {
        $this->quantityValidatorService = $quantityValidatorService;
    }

    /**
     * @param mixed      $addToCartDto
     * @param Constraint $constraint
     */
    public function validate($addToCartDto, Constraint $constraint): void
    {
        Assert::isInstanceOf($addToCartDto, AddToCartInterface::class);
        Assert::isInstanceOf($constraint, AddToCartQuantity::class);

        /**
         * @var PurchasableInterface $purchasable
         */
        $purchasable = $addToCartDto->getCartItem()->getProduct();

        if (!$purchasable instanceof StockableInterface) {
            return;
        }

        if (!$purchasable instanceof ProductInterface) {
            return;
        }

        /**
         * @var CartItemInterface $cartItem
         * @var CartInterface     $cart
         */
        $cartItem = $addToCartDto->getCartItem();
        $cart = $addToCartDto->getCart();

        $quantity = $cartItem->getDefaultUnitQuantity() + $this->getExistingCartItemQuantityFromCart($cart, $cartItem);
        $minLimit = $purchasable->getMinimumQuantityToOrder();

        if ($this->quantityValidatorService->isLowerThenMinLimit($minLimit, $quantity)) {
            $this->context->addViolation(
                $constraint->messageBelowMinimum,
                [
                    '%stockable%' => $purchasable->getInventoryName(),
                    '%limit%' => $minLimit,
                ]
            );
        }
    }

    /**
     * @param CartInterface     $cart
     * @param CartItemInterface $cartItem
     *
     * @return int
     */
    private function getExistingCartItemQuantityFromCart(CartInterface $cart, CartItemInterface $cartItem)
    {
        $product = $cartItem->getProduct();
        $quantity = 0;

        /**
         * @var CartItemInterface $item
         */
        foreach ($cart->getItems() as $item) {
            if (!$product && $item->equals($cartItem)) {
                return $item->getDefaultUnitQuantity();
            }

            if ($item->getProduct() instanceof $product && $item->getProduct()->getId() === $product->getId()) {
                $quantity += $item->getDefaultUnitQuantity();
            }
        }

        return $quantity;
    }
}
