<?php

if( !class_exists('PARVAZ_DCL_DOKAN_REPORTS') ) {

    class PARVAZ_DCL_DOKAN_REPORTS {

        public function getReports() {

            global $wpdb;

            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 10;
            $offset = ($page - 1) * $per_page;

            $query = "SELECT * FROM {$wpdb->prefix}parvaz_dokan_custom_log ORDER BY id DESC LIMIT {$offset} , {$per_page}";
            $reports = $wpdb->get_results($query);

            return $reports;

        }

        public function getTotalReports() {

            global $wpdb;

            $query = "SELECT COUNT(*) FROM {$wpdb->prefix}parvaz_dokan_custom_log";
            $total = $wpdb->get_var($query);

            return $total;

        }

        public function getReport( $id ) {

            global $wpdb;

            $query = "SELECT * FROM {$wpdb->prefix}parvaz_dokan_custom_log WHERE id = {$id}";
            $report = $wpdb->get_row($query);

            return $report;

        }

        public function getLinks() {

            $total = $this->getTotalReports();
            $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 10;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $links = [];

            $total_pages = ceil($total / $per_page);

            if($total_pages > 1) {

                if($page > 1) {
                    $links['prev'] = admin_url('admin.php?page=parvaz_dokan_reports&page=' . ($page - 1));
                }

                if($page < $total_pages) {
                    $links['next'] = admin_url('admin.php?page=parvaz_dokan_reports&page=' . ($page + 1));
                }

                if($page < $total_pages) {
                    $links['next'] = admin_url('admin.php?page=parvaz_dokan_reports&page=' . ($page + 1));
                }

            }

            return $links;


        }

    }

}