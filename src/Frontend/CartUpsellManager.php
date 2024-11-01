<?php namespace TierPricingTable\Frontend;

use ArrayIterator;
use TierPricingTable\Core\ServiceContainerTrait;
use TierPricingTable\PriceManager;
use TierPricingTable\PricingRule;

/**
 * Class CartUpsellManager
 *
 * @package TierPricingTable
 */
class CartUpsellManager {

	use ServiceContainerTrait;

	/**
	 * CatalogPriceManager constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_after_cart_item_name', array( $this, 'showUpsell' ), 1, 3 );
	}

	public function showUpsell( $cartItem ) {
		if ( ! $this->isCartUpsellEnabled() ) {
			return;
		}

		$upsellString = $this->formatUpsellString( $cartItem );

		if ( ! $upsellString ) {
			return;
		}

		?>
        <div>
            <small style="color: <?php echo esc_attr( $this->getUpsellColor() ); ?>"><?php echo wp_kses_post( $upsellString ); ?></small>
        </div>
		<?php
	}

	protected function formatUpsellString( $cartItem ) {

		$nextPriceData = $this->getNextPriceData( $cartItem );

		if ( empty( $nextPriceData ) ) {
			return false;
		}

		$template = $this->getTemplate();

		return strtr( $template, array(
			'{tp_required_quantity}' => $nextPriceData['next_quantity'],
			'{tp_next_price}'        => wc_price( $nextPriceData['next_price'] ),
			'{tp_next_discount}'     => number_format( $nextPriceData['next_discount'], wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() ),
			'{tp_actual_discount}'   => number_format( $nextPriceData['actual_discount'], wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() ),
		) );
	}

	protected function getNextPriceData( $cartItem ) {
		$pricingRule = $this->getPricingRule( $cartItem );

		if ( ! empty( $pricingRule->getRules() ) ) {

			$iterator        = new ArrayIterator( array_reverse( $pricingRule->getRules(), true ) );
			$currentQuantity = $this->getTotalProductCountInCart( $cartItem );

			while ( $iterator->valid() ) {
				if ( $currentQuantity < $iterator->key() ) {

					$prevQuantity = $iterator->key();
					$prevPrice    = $iterator->current();

					$iterator->next();

					if ( $iterator->valid() && $currentQuantity >= $iterator->key() ) {

						$product = wc_get_product( $this->getProductId( $cartItem ) );

						if ( $pricingRule->isFixed() ) {
							$nextPrice    = $prevPrice;
							$currentPrice = $iterator->current();
						} else {
							$nextPrice    = PriceManager::getPriceByPercentDiscount( $product->get_price(), $prevPrice );
							$currentPrice = PriceManager::getPriceByPercentDiscount( $product->get_price(), $iterator->current() );
						}

						$currentPrice = PriceManager::getPriceWithTaxes( $currentPrice, $product, 'cart' );
						$nextPrice    = PriceManager::getPriceWithTaxes( $nextPrice, $product, 'cart' );

						return array(
							'next_price'      => $nextPrice,
							'next_discount'   => PriceManager::calculateDiscount( $currentPrice, $nextPrice ),
							'actual_discount' => $pricingRule->isPercentage() ? $prevPrice : PriceManager::calculateDiscount( $product->get_price( 'edit' ), $currentPrice  ),
							'next_quantity'   => $prevQuantity - $currentQuantity,
						);
					} else if ( ! $iterator->valid() ) {
						$product = wc_get_product( $this->getProductId( $cartItem ) );

						if ( $pricingRule->isFixed() ) {
							$nextPrice    = $prevPrice;
							$currentPrice = $product->get_price();
						} else {
							$nextPrice    = PriceManager::getPriceByPercentDiscount( $product->get_price(), $prevPrice );
							$currentPrice = $product->get_price();
						}

						$nextPrice = PriceManager::getPriceWithTaxes( $nextPrice, $product, 'cart' );

						return array(
							'next_price'      => $nextPrice,
							'next_discount'   => PriceManager::calculateDiscount( $currentPrice, $nextPrice ),
							'actual_discount' => $pricingRule->isPercentage() ? $prevPrice : PriceManager::calculateDiscount( $currentPrice, $product->get_price( 'edit' ) ),
							'next_quantity'   => $prevQuantity - $currentQuantity,
						);
					}
				} else {
					$iterator->next();
				}
			}
		}

		return array();
	}

	public function getTotalProductCountInCart( $cartItem ) {

		if ( isset( $cartItem['tpt_pricing_rule_id'] ) ) {
			$samePricingRuleCartItems = [];

			foreach ( wc()->cart->cart_contents as $cartContent ) {
				if ( isset( $cartContent['tpt_pricing_rule_id'] ) && $cartContent['tpt_pricing_rule_id'] === $cartItem['tpt_pricing_rule_id'] ) {
					$samePricingRuleCartItems[] = $cartContent;
				}
			}
		} else {
			$samePricingRuleCartItems[] = $cartItem;
		}

		$quantity = 0;

		foreach ( $samePricingRuleCartItems as $cartItem ) {
			if ( $this->getContainer()->getSettings()->get( 'summarize_variations', 'no' ) !== 'yes' ) {
				$quantity += $cartItem['quantity'];
			} else {
				foreach ( wc()->cart->cart_contents as $cart_content ) {
					if ( $cart_content['product_id'] == $cartItem['product_id'] ) {
						$quantity += $cart_content['quantity'];
					}
				}
			}
		}

		return $quantity;
	}

	/**
	 * Get pricing rules from cart item
	 *
	 * @param array $cartItem
	 *
	 * @return PricingRule|false
	 */
	protected function getPricingRule( $cartItem ) {

		$productId = $this->getProductId( $cartItem );

		if ( $productId ) {
			return PriceManager::getPricingRule( $productId );
		}

		return false;
	}

	protected function getProductId( $cartItem ) {
		return ! empty( $cartItem['variation_id'] ) ? $cartItem['variation_id'] : $cartItem['product_id'];
	}

	protected function getTemplate() {
		return $this->getContainer()->getSettings()->get( 'cart_upsell_template', __( 'Buy <b>{tp_required_quantity}</b> more to get <b>{tp_next_price}</b> each', 'tier-pricing-table' ) );
	}

	protected function getUpsellColor() {
		return $this->getContainer()->getSettings()->get( 'cart_upsell_color', '#96598A' );
	}

	protected function isCartUpsellEnabled() {
		return $this->getContainer()->getSettings()->get( 'cart_upsell_enabled', 'no' ) === 'yes';
	}
}