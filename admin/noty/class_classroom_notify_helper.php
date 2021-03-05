<?php 

define('API_URI', 'https://apis.wemap.asia/route-api/route?type=json&locale=vi-VN&vehicle=car&weighting=fastest&elevation=false');
define('SEARCH_URI', 'https://apis.wemap.asia/geocode-1/search');

if(!class_exists('ClassroomNotyHelper')) {
    class ClassroomNotyHelper {
        private static $instance = null;
        private $classroom_id;
        private $key = '';
        
        public function __construct()
        {
            $this->key = get_option('gs_options')['wemap_key'] ? get_option('gs_options')['wemap_key'] : '';
        }

        public static function getInstance() { 
            if (self::$instance !== null) {
                return self::$instance;
            }
    
            self::$instance = new self();
            return self::$instance;
        }

        public function getTutors()
        {
            $invalid_tutors = array();

            $args = array(
                'role'    => 'tutor',
                'orderby' => 'user_nicename',
                'order'   => 'ASC'
            );
            $tutors = get_users( $args );
            $class_point = $this->getCoordinates($this->getAddress($this->classroom_id, 'classroom'));
            write_log($class_point);
            
            // if($tutors && is_array($tutors)) {
            //     foreach ( $tutors as $tutor ) {
            //         $tutor_point = $this->getCoordinates($this->getAddress($tutor->ID, 'tutor'));
            //         $api_uri     = API_URI . '&point=' . $class_point . '&point=' . $tutor_point . '&key=' . $this->key;
            //         $body        = wp_remote_retrieve_body( wp_remote_get( $api_uri ) );
            //         $distance    = $this->getMinDistance($body['paths']);
            //         if($distance > 5000 || !get_the_author_meta('user_prof_mail_sms_submit', $tutor->ID)) continue;
            //         $invalid_tutors[$tutor->ID] = $tutor->user_email;
            //     }
            // }

            return $invalid_tutors;
        } 

        public function setClassroomID($classroom_id)
        {
            return $this->classroom_id = $classroom_id;
        }

        private function getMinDistance($data, $value = 'distance'){
            $min = 0;
            foreach($data as $point){
                if($min > (float)$point->{$value}){
                    $min = $point->{$value};
                }
            }
            return $min;
        }

        private function getAddress($id, $type = 'classroom')
        {
            $location = '';
            if($type == 'classroom') {
                $location = get_post_meta( $id, 'class_address', true );
            }else{
                $location = get_the_author_meta( 'user_address', $id ) . ', ' . get_the_author_meta( 'user_train_district', $id ) . ', ' . get_the_author_meta( 'user_train_province', $id );
            }
            return $location;
        }

        private function getCoordinates($location)
        {
            $coord = '';
            $body  = '';
            if($location) {
                $api_uri = SEARCH_URI . '?text=' . $location .'&key=' . $this->key;
                $body    = wp_remote_retrieve_body( wp_remote_get( $api_uri ) );
                // $latitude   = $body['features'] ? $body['features'][0]['geometry']['coordinates'][0] : 0;
                // $longitude  = $body['features'] ? $body['features'][0]['geometry']['coordinates'][1] : 0;
                // $coord      = $latitude . '%2C' . $longitude;
            }
            
            return $body;
        }
    }
}