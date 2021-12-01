<?php
use Mhmmdq\Database\Connection;
use Mhmmdq\Database\QueryBuilder;

if( !class_exists('PARVAZ_DCL_REPORTS') ) {

    class PARVAZ_DCL_REPORTS {

        public  $links;

        public function __construct()
        {
            
        }

        public function paginate_render( $item_count ) {
            $items = $this->get_data_pagi( $item_count );
            $items = !empty($items) ? $items : [];
            $datas = [];
            foreach ($items as $item) {
                $extra_data = $this->get_order_details( $item['order_id'] );
                $datas[] = array_merge($item , $extra_data);
            }
            
            return $datas;
        }
        
        public  function get_data_pagi ( $item_count )
        {
            global $wpdb;
            new Connection([
                'driver'=>'mysql',
                'host'=>DB_HOST,
                'username'=>DB_USER,
                'password'=>DB_PASSWORD,
                'charset'=>'utf8mb4',
                'collation'=>'utf8mb4_general_ci',
                'database'=>DB_NAME
            ]);
            $db = new QueryBuilder;
            return $db->table($wpdb->prefix . 'parvaz_dokan_custom_log')->orderBy('id' , 'DESC')->pagination(10)->toArray();
            $this->links = $db->links();
        }

        public function  get_order_details( $order_id ) {
            $status = new WC_Order( $order_id );
            return [
                'name' => get_post_meta( $order_id , '_billing_first_name' )[0] . ' ' . get_post_meta( $order_id , '_billing_last_name' )[0] ,
                'tax' => get_post_meta( $order_id , '_order_tax')[0],
                'shipping_tax' => get_post_meta( $order_id , '_order_shipping' )[0],
                'status' => $status->get_status(),
                'time' => $status->get_date_modified(),
            ];
        }

        public function render_links() {
            return $this->links;
        }
    }

}