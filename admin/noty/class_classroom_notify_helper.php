<?php 

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
            $class_point = getCoordinates(getAddress($this->classroom_id, 'classroom'));
            
            $terms = get_the_terms( $this->classroom_id, 'class_subject' );
            
            if($tutors && is_array($tutors)) {
                foreach ( $tutors as $key => $tutor ) {
                    // kiểm tra địa chỉ của gia sư và bật/tắt thông báo
                    if( !get_the_author_meta( 'user_address', $tutor->ID) || !get_the_author_meta('user_prof_mail_sms_submit', $tutor->ID) ) { continue; }
                    $subs = get_the_author_meta( 'user_prof_subject', $tutor->ID );

                    if( array_filter( $terms, function($term) {
                        if( in_array( $term->term_id, is_array($subs) && isset($subs) ? $subs : array() ) ) {
                            return true;
                        }
                        return false;
                    } ) ) {
                        $tutor_point = get_the_author_meta( 'user_coordinates', $tutor->ID );
                        if($tutor_point) { continue; }
                        $distance    = $this->distanceBetween2Points( $class_point, $tutor_point );
                        if($distance > $this->max_distance) { continue; }
                        $invalid_tutors[$tutor->ID] = $tutor->user_email;
                    }
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

        private function distanceBetween2Points($point1, $point2) {
            if( $point1 == null || $point2 == null ) return 0;
            $pi80 = M_PI / 180;
            $lat1 = floatval(explode(',', $point1)[1]) * $pi80;
            $lng1 = floatval(explode(',', $point1)[0]) * $pi80;
            $lat2 = floatval(explode(',', $point2)[1]) * $pi80;
            $lng2 = floatval(explode(',', $point2)[0]) * $pi80;

            $r = 6372.797; // earth radius
            $dlat = $lat2 - $lat1;
            $dlng = $lng2 - $lng1;
            $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $km = $r * $c;

            return $km * 1000;
        }
    }
}