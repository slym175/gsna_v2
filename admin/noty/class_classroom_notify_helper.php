<?php 

define('API_URI', 'https://apis.wemap.asia/route-api/route?type=json&locale=vi-VN&vehicle=car&weighting=fastest&elevation=false');
define('SEARCH_URI', 'https://apis.wemap.asia/geocode-1/search');

if(!class_exists('ClassroomNotyHelper')) {
    class ClassroomNotyHelper {
        protected static $instance = null;
        protected $classroom_id;
        protected $key = '';
        protected $max_distance = 5000;
        
        public function __construct()
        {
            $this->key = get_option('gs_options')['wemap_key'] ? get_option('gs_options')['wemap_key'] : '';
            $this->max_distance = get_option('gs_options')['classroom_publish_noty_distance'] ? get_option('gs_options')['classroom_publish_noty_distance'] : $this->max_distance;
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
            
            if($tutors && is_array($tutors)) {
                foreach ( $tutors as $key => $tutor ) {
                    // kiểm tra địa chỉ của gia sư và bật/tắt thông báo
                    if( !get_the_author_meta( 'user_address', $tutor->ID) || !get_the_author_meta('user_prof_mail_sms_submit', $tutor->ID) ) { continue; }
                    
                    // kiểm tra địa chỉ của gia sư và bật/tắt thông báo
                    $tutor_point = $this->getCoordinates($this->getAddress($tutor->ID, 'tutor'));
                    $api_uri     = API_URI . '&point=' . $class_point . '&point=' . $tutor_point . '&key=' . $this->key;
                    $body        = json_decode( wp_remote_retrieve_body( wp_remote_get( esc_url_raw( $api_uri ) ) ), true );
                    if( !isset( $body['paths'] ) ) { continue; }
                    $distance    = $this->getMinDistance( $body['paths'] );
                    if($distance > $this->max_distance) { continue; }
                    $invalid_tutors[$tutor->ID] = $tutor->user_email;  
                }
            }

            if(!isset($invalid_tutors)) {
                foreach ( $tutors as $key => $tutor ) {
                    $invalid_tutors[$tutor->ID] = $tutor->user_email;
                }
            }

            return $invalid_tutors;
        } 

        public function setClassroomID($classroom_id)
        {
            return $this->classroom_id = $classroom_id;
        }

        function getMinDistance($data, $value = 'distance'){
            $min = isset($data[0]) ? $data[0][$value] : 0;
            foreach($data as $point){
                if($min > (float)$point[$value] && isset($point[$value])){
                    $min = $point[$value];
                }
            }
            return $min;
        }

        public function getAddress($id, $type = 'classroom')
        {
            $location = 'location';
            if($type == 'classroom') {
                $location = get_post_meta( $id, 'class_address', true );
            }else{
                $location = get_the_author_meta( 'user_address', $id ) . ', ' . get_the_author_meta( 'user_train_district', $id ) . ', ' . get_the_author_meta( 'user_train_province', $id );
            }
            return $location;
        }

        public function getCoordinates($location)
        {
            $coord = '';
            $body  = '';
            if($location) {
                $api_uri = SEARCH_URI . '?text=' . $location .'&key=' . $this->key;
                $body    = json_decode( wp_remote_retrieve_body( wp_remote_get( esc_url_raw( $api_uri ) ) ), true );
                $latitude   = isset($body['features']) ? (isset($body['features'][0]) ? $body['features'][0]['geometry']['coordinates'][0] : 0) : 0;
                $longitude  = isset($body['features']) ? (isset($body['features'][0]) ? $body['features'][0]['geometry']['coordinates'][1] : 0) : 0;
                $coord      = $longitude . ',' . $latitude;
            }
            
            return $coord;
        }
    }
}