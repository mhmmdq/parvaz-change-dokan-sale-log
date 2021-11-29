<?php

if( !class_exists( 'PARVAZ_DCL_DB_TABLE' ) )
{

    class PARVAZ_DCL_DB_TABLE { 

        public $table;

        public function __construct()
        {
            $this->table_name();

            
            if($this->table_exists()) {
                $this->create_table();
            }
        }

        public function create_table () {
            global $wpdb;

            $sql = "CREATE TABLE `" . $this->table . "` ( `id` INT NOT NULL AUTO_INCREMENT ,
             `order_id` INT NOT NULL , `vendor_id` INT NOT NULL ,
              `total` TEXT NOT NULL , `seller` TEXT NOT NULL ,
               `commission` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            
            if(!function_exists('dbDelta')) {
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            }

            dbDelta($sql);
        }

        public function table_exists() {
            global $wpdb;
            return $wpdb->get_var("SHOW TABLES LIKE '". $this->table ."'"  ) != $this->table;
        }

        public function drop_table() {

        }

        protected function table_name() {
            global $wpdb;
            $this->table = $wpdb->prefix . 'parvaz_dokan_custom_log';
        } 

    }

}