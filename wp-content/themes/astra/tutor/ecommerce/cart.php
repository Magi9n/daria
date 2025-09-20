<?php
/**
 * Custom Cart Template Override for Tutor LMS
 * Diseño personalizado responsive basado en la imagen proporcionada
 *
 * @package Tutor\Views
 * @author Custom Design
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Tutor\Ecommerce\CartController;
use Tutor\Ecommerce\CheckoutController;
use Tutor\Ecommerce\Tax;
use Tutor\Models\CourseModel;

$cart_controller = new CartController();
$get_cart        = $cart_controller->get_cart_items();
$courses         = $get_cart['courses'];
$total_count     = $courses['total_count'];
$course_list     = $courses['results'];

$subtotal         = 0;
$tax_exempt_price = 0;

$checkout_page_url = CheckoutController::get_page_url();

?>

<style>
/* Importar Google Fonts - Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* Contenedor principal del carrito personalizado */
.custom-cart-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: 'Poppins', sans-serif;
}

/* Barra superior con color #592D36 */
.cart-header-bar {
    background-color: #592D36;
    color: white;
    padding: 16px 24px;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    font-size: 18px;
    text-align: left;
    margin-bottom: 0;
}

/* Contenedor de productos */
.cart-products-container {
    background-color: #f8f9fa;
    border-radius: 0 0 12px 12px;
    padding: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Item individual del carrito */
.custom-cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #e0e0e0;
    background-color: white;
    transition: all 0.3s ease;
}

.custom-cart-item:last-child {
    border-bottom: none;
    border-radius: 0 0 12px 12px;
}

.custom-cart-item:hover {
    background-color: #f8f9fa;
}

/* Información del curso */
.cart-course-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 16px;
}

.cart-course-thumb {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.cart-course-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-course-details h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    line-height: 1.4;
}

/* Precio centrado */
.cart-course-price {
    flex: 0 0 120px;
    text-align: center;
    font-weight: 600;
    font-size: 18px;
    color: #592D36;
}

/* Acciones del curso */
.cart-course-action {
    flex: 0 0 160px;
    text-align: right;
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
}

.btn-ir-curso {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background-color: transparent;
    border: none;
    color: #592D36;
    text-decoration: underline;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-ir-curso:hover {
    color: #4a2329;
    text-decoration: underline;
}

/* Botón de eliminar */
.btn-remove-course {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove-course:hover {
    background-color: #c82333;
    transform: translateY(-1px);
}

/* Icono Circle Chevron Down */
.chevron-icon {
    width: 20px;
    height: 20px;
    border: 2px solid currentColor;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.chevron-icon::after {
    content: '';
    width: 6px;
    height: 6px;
    border-right: 2px solid currentColor;
    border-bottom: 2px solid currentColor;
    transform: rotate(45deg);
    margin-top: -2px;
}

/* Botón "Ir a pagar" */
.cart-checkout-section {
    margin-top: 32px;
    text-align: right;
}

.btn-ir-pagar {
    background-color: #592D36;
    color: white;
    padding: 14px 32px;
    border: none;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(89, 45, 54, 0.3);
}

.btn-ir-pagar:hover {
    background-color: #4a2329;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(89, 45, 54, 0.4);
    color: white;
    text-decoration: none;
}

/* Estado vacío del carrito */
.custom-cart-empty {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.custom-cart-empty img {
    max-width: 200px;
    margin-bottom: 24px;
    opacity: 0.7;
}

.custom-cart-empty p {
    font-size: 18px;
    margin-bottom: 24px;
}

/* RESPONSIVE DESIGN */

/* Tablets (768px - 1024px) */
@media (max-width: 1024px) {
    .custom-cart-container {
        max-width: 90%;
        padding: 0 16px;
    }
    
    .cart-header-bar {
        font-size: 16px;
        padding: 14px 20px;
    }
    
    .custom-cart-item {
        padding: 16px 20px;
    }
    
    .cart-course-price {
        flex: 0 0 100px;
        font-size: 16px;
    }
    
    .cart-course-action {
        flex: 0 0 140px;
    }
}

/* Móviles (hasta 768px) */
@media (max-width: 768px) {
    .custom-cart-container {
        margin: 20px auto;
        padding: 0 12px;
    }
    
    .cart-header-bar {
        font-size: 15px;
        padding: 12px 16px;
    }
    
    .custom-cart-item {
        flex-direction: column;
        align-items: stretch;
        padding: 16px;
        gap: 16px;
    }
    
    .cart-course-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
    }
    
    .cart-course-thumb {
        width: 80px;
        height: 80px;
    }
    
    .cart-course-details h5 {
        font-size: 15px;
    }
    
    .cart-course-price {
        flex: none;
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        padding: 8px 0;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .cart-course-action {
        flex: none;
        text-align: center;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    
    .btn-ir-curso {
        justify-content: center;
        flex: 1;
        padding: 12px 16px;
        margin-right: 8px;
    }
    
    .btn-remove-course {
        flex: 0 0 auto;
        padding: 12px 16px;
        font-size: 12px;
    }
    
    .cart-checkout-section {
        text-align: center;
        margin-top: 24px;
    }
    
    .btn-ir-pagar {
        width: 100%;
        padding: 16px 32px;
        font-size: 16px;
    }
}

/* Móviles pequeños (hasta 480px) */
@media (max-width: 480px) {
    .custom-cart-container {
        margin: 16px auto;
        padding: 0 8px;
    }
    
    .cart-header-bar {
        font-size: 14px;
        padding: 10px 12px;
    }
    
    .custom-cart-item {
        padding: 12px;
    }
    
    .cart-course-thumb {
        width: 70px;
        height: 70px;
    }
    
    .cart-course-details h5 {
        font-size: 14px;
    }
    
    .cart-course-price {
        font-size: 16px;
    }
    
    .btn-ir-curso {
        font-size: 13px;
    }
    
    .btn-ir-pagar {
        font-size: 15px;
        padding: 14px 24px;
    }
}

/* Ocultar elementos originales de Tutor LMS */
.tutor-cart-page .tutor-cart-page-wrapper {
    display: none;
}
</style>

<div class="custom-cart-container">
	<?php if ( is_array( $course_list ) && count( $course_list ) ) : ?>
		
		<!-- Barra superior -->
		<div class="cart-header-bar">
			Detalles de tu Compra
		</div>
		
		<!-- Contenedor de productos -->
		<div class="cart-products-container">
			<?php
			foreach ( $course_list as $key => $course ) :
				$course_duration  = get_tutor_course_duration_context( $course->ID, true );
				$course_price     = tutor_utils()->get_raw_course_price( $course->ID );
				$regular_price    = $course_price->regular_price;
				$sale_price       = $course_price->sale_price;
				$display_price    = $sale_price ? $sale_price : $regular_price;
				$tutor_course_img = get_tutor_course_thumbnail_src( '', $course->ID );

				$subtotal += $display_price;

				$tax_collection = CourseModel::is_tax_enabled_for_single_purchase( $course->ID );
				if ( ! $tax_collection ) {
					$tax_exempt_price += $display_price;
				}
				?>
				<div class="custom-cart-item">
					<!-- Información del curso -->
					<div class="cart-course-info">
						<div class="cart-course-thumb">
							<img src="<?php echo esc_url( $tutor_course_img ); ?>" alt="<?php echo esc_attr( $course->post_title ); ?>">
						</div>
						<div class="cart-course-details">
							<h5><?php echo esc_html( $course->post_title ); ?></h5>
						</div>
					</div>
					
					<!-- Precio centrado -->
					<div class="cart-course-price">
						<?php tutor_print_formatted_price( $display_price ); ?>
					</div>
					
					<!-- Acciones del curso -->
					<div class="cart-course-action">
						<a href="<?php echo esc_url( get_the_permalink( $course ) ); ?>" class="btn-ir-curso">
							Ir al curso
							<span class="chevron-icon"></span>
						</a>
						<button class="btn-remove-course tutor-cart-remove-button" data-course-id="<?php echo esc_attr( $course->ID ); ?>">
							Eliminar
						</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		
		<!-- Sección de checkout -->
		<div class="cart-checkout-section">
			<a href="<?php echo esc_url( $checkout_page_url ? $checkout_page_url : '#' ); ?>" class="btn-ir-pagar">
				Ir a pagar
			</a>
		</div>
		
	<?php else : ?>
		<!-- Estado vacío -->
		<div class="custom-cart-empty">
			<img src="<?php echo esc_url( tutor()->url ); ?>assets/images/empty-cart.svg" alt="<?php esc_html_e( 'Empty shopping cart', 'tutor' ); ?>" />
			<p><?php esc_html_e( 'No courses in the cart', 'tutor' ); ?></p>
			<a href="<?php echo esc_url( tutor_utils()->course_archive_page_url() ); ?>" class="btn-ir-pagar"><?php esc_html_e( 'Continue Browsing', 'tutor' ); ?></a>
		</div>
	<?php endif; ?>
</div>

<!-- Mantener la funcionalidad original oculta para preservar hooks y acciones -->
<div style="display: none;">
	<?php
	// Ejecutar las acciones originales para mantener la funcionalidad
	$original_cart_controller = new CartController();
	$original_get_cart        = $original_cart_controller->get_cart_items();
	$original_courses         = $original_get_cart['courses'];
	$original_course_list     = $original_courses['results'];
	
	if ( is_array( $original_course_list ) && count( $original_course_list ) ) {
		foreach ( $original_course_list as $key => $course ) {
			// Mantener los hooks originales activos
			do_action( 'tutor_cart_item_display', $course );
		}
	}
	?>
</div>
