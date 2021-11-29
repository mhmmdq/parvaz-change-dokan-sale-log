<?php

if( !class_exists( 'PARVAZ_DCL' ) )
{

    class PARVAZ_DCL {

        public function __construct()
        {
            add_action( 'woocommerce_thankyou' , [ $this , 'change_dokan_reports' ] );
            add_action("admin_menu", array($this , "add_sidebar_menu"));
        }

        public function get_order_data( $cart_object ) {
            global $woocommerce;

                if ( is_admin() && ! defined( 'DOING_AJAX' ) ){
                    return;
                }

                if ($coupons = WC()->cart->get_applied_coupons()  == False ) {
                    $coupon = False;
                } else {
                    foreach ( WC()->cart->get_applied_coupons() as $code ) {
                        $coupon = new WC_Coupon( $code );
                        $discount_type = $coupon->get_discount_type(); // Get coupon discount type
                        $coupon_amount = $coupon->get_amount(); // Get coupon amount
                    }
                }
                

                if($coupon !== false) {
                    foreach ( $cart_object->get_cart() as $cart_item )
                    {
                        $commission = dokan()->commission->get_earning_by_product( $cart_item['product_id'], 'admin' );
                        $quantity = $cart_item['quantity'];
                        $price = $cart_item['data']->regular_price;
                        add_post_meta( $cart_item['product_id'] , '_parvaz' , [
                            'commission' => $commission , 
                            'quantity' => $quantity ,
                            'price' => $price,
                            'coupon_amount' => $coupon_amount,
                            'rand' => rand(0 , 100)
                        ]);
                    }
                }
        }

        public function change_dokan_reports( $order_id ) {
            global $wpdb;
            $order = new WC_Order( $order_id );
            $reports = [];
            foreach( $order->get_items() as $item ) {
                $product_id = $item['product_id'];
                // var_dump($item);
                $commission = dokan()->commission->get_earning_by_product( $product_id , 'admin' ) * $item['quantity'] ;
                $subtotal = $item['subtotal'];
                $total = $item['total'];
                $discountـrate = $subtotal - $total;
                $seller = $subtotal - $commission;
                $vendor_id = get_post( $product_id )->post_author;

                if(!isset($reports[$vendor_id])) {
                    $reports[$vendor_id] = [
                        'product_id' => $product_id,
                        'commission' => $commission ,
                        'discount_rate '=> $discountـrate,
                        'seller' => $seller,
                        'vendor_id' => $vendor_id,
                        'total' => $total
                    ];
                }else {
                    $reports[$vendor_id] = [
                        'product_id' => $product_id,
                        'commission' => $commission + $reports[$vendor_id]['commission'] ,
                        'discount_rate '=> $discountـrate,
                        'seller' => $seller + $reports[$vendor_id]['seller'],
                        'vendor_id' => $vendor_id,
                        'total' => $total + $reports[$vendor_id]['total']
                    ];
                }
            }

            $seller_income = [];
            $commission_amount = [];

            foreach( $reports as  $report ) {
                $commission_amount[$report['vendor_id']] = $commission_amount[$report['vendor_id']] + ($report['commission'] - $report['discount_rate']);
                $seller_income[$report['vendor_id']] = $seller_income[$report['vendor_id']] + $report['seller'];
            }
            
            if( $this->check_record_exists( $order_id ) == null) {
                // $wpdb->update( $wpdb->prefix . 'dokan_orders' , [
                //     'net_amount' => $seller_income
                // ] , [
                //     'order_id' => $order_id
                // ]);
                foreach($reports as $report) {
                    $wpdb->insert($wpdb->prefix . 'parvaz_dokan_custom_log' , [
                        'order_id' => $order_id , 
                        'vendor_id' => $report['vendor_id'],
                        'total' => $report['total'] , 
                        'seller' => $seller_income[$report['vendor_id']] , 
                        'commission' => $commission_amount[$report['vendor_id']]
                    ]);
                }
            }
        }

        public function add_sidebar_menu() {
            add_menu_page("Parvaz_log", "پرواز - گزارشات", "edit_posts",
            "Parvaz_log", [ $this , 'parvaz_reports_log' ], "https://img.icons8.com/external-kiranshastry-lineal-kiranshastry/18/ffffff/external-link-business-and-management-kiranshastry-lineal-kiranshastry.png" , 1);
        }

        public function parvaz_reports_log() {
            view('reports');
        }

        public function export_reports() {

            if( !class_exists('PARVAZ_DCL_REPORTS') ) {
                include_once 'class-dokan-reports.php';
                return PARVAZ_DCL_REPORTS::paginate_render(20);
            }
        }

        public function check_record_exists ( $order_id ) {
            global $wpdb;
            return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}parvaz_dokan_custom_log WHERE order_id = {$order_id}", OBJECT );
        }

    }

}


