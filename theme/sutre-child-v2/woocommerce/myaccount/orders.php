<?php
/**
 * Orders — Sutre P41 özelleştirmesi: arama + dönem + sıralama (GET: sv_q, sv_period, sv_sort).
 * Kaynak: WooCommerce 11.1.0 templates/myaccount/orders.php (@version 9.5.0)
 * Değişiklik: tablo üstü filtre formu; server-side filtre/sıralama/sayfalama
 * functions.php sv41_orders_prepare() ile; Woo tablo + aksiyon markup'ı korunur.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

$sv_ready = function_exists( 'sv41_orders_prepare' )
	? sv41_orders_prepare( is_object( $customer_orders ) ? $customer_orders->orders : array(), $current_page )
	: array(
		'items'     => array(),
		'total'     => 0,
		'max_pages' => 1,
		'page'      => 1,
		'filters'   => array( 'q' => '', 'period' => 'all', 'sort' => 'date-desc' ),
	);
$sv_orders     = $sv_ready['items'];
$sv_filters    = $sv_ready['filters'];
$sv_has_filter = ( '' !== $sv_filters['q'] ) || ( 'all' !== $sv_filters['period'] );

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

	<form class="sv-orders-filter" method="get" role="search" action="<?php echo esc_url( sv41_myaccount_url( 'orders' ) ); ?>">
		<label class="screen-reader-text" for="sv_q">Siparişlerde ara</label>
		<input type="search" id="sv_q" name="sv_q" value="<?php echo esc_attr( $sv_filters['q'] ); ?>" placeholder="Sipariş no veya ürün adı ara">
		<label class="screen-reader-text" for="sv_period">Dönem</label>
		<select id="sv_period" name="sv_period">
			<option value="all" <?php selected( 'all', $sv_filters['period'] ); ?>>Tümü</option>
			<option value="3m" <?php selected( '3m', $sv_filters['period'] ); ?>>Son 3 ay</option>
			<option value="6m" <?php selected( '6m', $sv_filters['period'] ); ?>>Son 6 ay</option>
			<option value="1y" <?php selected( '1y', $sv_filters['period'] ); ?>>Son 1 yıl</option>
		</select>
		<label class="screen-reader-text" for="sv_sort">Sıralama</label>
		<select id="sv_sort" name="sv_sort">
			<option value="date-desc" <?php selected( 'date-desc', $sv_filters['sort'] ); ?>>Tarihe göre yeni</option>
			<option value="date-asc" <?php selected( 'date-asc', $sv_filters['sort'] ); ?>>Tarihe göre eski</option>
			<option value="price-asc" <?php selected( 'price-asc', $sv_filters['sort'] ); ?>>Fiyata göre artan</option>
			<option value="price-desc" <?php selected( 'price-desc', $sv_filters['sort'] ); ?>>Fiyata göre azalan</option>
		</select>
		<button type="submit" class="button">Filtrele</button>
		<?php if ( $sv_has_filter ) : ?>
			<a class="sv-orders-filter__clear" href="<?php echo esc_url( sv41_myaccount_url( 'orders' ) ); ?>">Temizle</a>
		<?php endif; ?>
	</form>

	<?php if ( empty( $sv_orders ) ) : ?>

		<div class="woocommerce-info sv-orders-no-result">
			Aramanızla eşleşen sipariş bulunamadı.
			<a href="<?php echo esc_url( sv41_myaccount_url( 'orders' ) ); ?>">Filtreleri temizle</a>
		</div>

	<?php else : ?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $sv_orders as $customer_order ) {
				$order      = ( $customer_order instanceof WC_Order ) ? $customer_order : wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
						$is_order_number = 'order-number' === $column_id;
						?>
						<?php if ( $is_order_number ) : ?>
							<th class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" scope="row">
						<?php else : ?>
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php endif; ?>

							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( $is_order_number ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Sipariş %s görüntüle', $order->get_order_number() ) ); ?>">
									<?php echo esc_html( '#' . $order->get_order_number() ); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								echo wp_kses_post( sprintf( '%1$s — %2$s ürün', $order->get_formatted_order_total(), $item_count ) );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
										if ( empty( $action['aria-label'] ) ) {
											$action_aria_label = sprintf( '%1$s — sipariş %2$s', $action['name'], $order->get_order_number() );
										} else {
											$action_aria_label = $action['aria-label'];
										}
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . '" aria-label="' . esc_attr( $action_aria_label ) . '">' . esc_html( $action['name'] ) . '</a>';
										unset( $action_aria_label );
									}
								}
								?>
							<?php endif; ?>

						<?php if ( $is_order_number ) : ?>
							</th>
						<?php else : ?>
							</td>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $sv_ready['max_pages'] ) : ?>
		<?php
		$sv_qargs = array();
		if ( '' !== $sv_filters['q'] )                { $sv_qargs['sv_q']      = $sv_filters['q']; }
		if ( 'all' !== $sv_filters['period'] )        { $sv_qargs['sv_period'] = $sv_filters['period']; }
		if ( 'date-desc' !== $sv_filters['sort'] )    { $sv_qargs['sv_sort']   = $sv_filters['sort']; }
		?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $sv_ready['page'] ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( add_query_arg( $sv_qargs, wc_get_endpoint_url( 'orders', $sv_ready['page'] - 1 ) ) ); ?>">Önceki</a>
			<?php endif; ?>

			<?php if ( intval( $sv_ready['max_pages'] ) !== $sv_ready['page'] ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( add_query_arg( $sv_qargs, wc_get_endpoint_url( 'orders', $sv_ready['page'] + 1 ) ) ); ?>">Sonraki</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php endif; ?>

<?php else : ?>

	<?php wc_print_notice( 'Henüz sipariş oluşturmadınız. <a class="woocommerce-Button wc-forward button' . esc_attr( $wp_button_class ) . '" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">Alışverişe göz atın</a>', 'notice' ); ?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
