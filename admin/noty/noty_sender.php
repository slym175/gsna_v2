<?php 
if(!class_exists('Noty_Sender')) {
    class Noty_Sender
    {

        // class instance
        static $instance;

        // class constructor
        public function __construct()
        {
            # code...
        }

        /** Singleton instance */
        public static function getInstance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function sendMail(...$arguments)
        {
            // $arguments[0] : $classroom_id (Mã lớp học gửi đi)
            // $arguments[1] : $to (Danh sách người nhận)

            if(!isset($arguments[1])) {
                return $this->sendNotyResponse(true, 'Chả biết gửi cho ai', 'Không có địa chỉ email được truyền vào');
            }

            $result = false;
            $to = get_option( 'admin_email' ) ? get_option( 'admin_email' ) : 'thuyhu9876@gmail.com';
            $classroom_id = isset($arguments[0]) ? $arguments[0] : 0; 
            $subject = get_option('gs_options')['classroom_publish_noty_mail_subject'] ? get_option('gs_options')['classroom_publish_noty_mail_subject'] : 'GỬI TỪ GIA SƯ NHẬT ANH';
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'Bcc: '. isset($arguments[1]) ? (is_array($arguments[1]) ? implode(',', $arguments[1]) : $arguments[1]) : $to
            );
            $message = $this->generateMessage(get_option('gs_options')['classroom_publish_noty_mail'] ? get_option('gs_options')['classroom_publish_noty_mail'] : 'Có lớp mới được đăng tải. Ghé thăm ' . get_bloginfo('url') . ' để tìm hiểu thêm.', $classroom_id);

            $mailResult = wp_mail( $to, $subject, $message, $headers );

            if($mailResult == false) {
                return $this->sendNotyResponse(false, 'Có lỗi xảy ra trong quá trình gửi tin nhắn', $message);
            }
            
            return $this->sendNotyResponse(true, 'Gửi tin nhắn thành công', $message);
        }

        public function sendSMS(...$arguments)
        {
            // $arguments[0] : $classroom_id (Mã lớp học gửi đi)
            // $arguments[1] : $to (Danh sách người nhận)
            $result = false;
            $to = '+84986114671';
            $classroom_id = isset($arguments[0]) ? $arguments[0] : 0; 
            $message = $this->generateMessage(get_option('gs_options')['classroom_publish_noty_sms'] ? get_option('gs_options')['classroom_publish_noty_sms'] : 'Có lớp mới được đăng tải. Ghé thăm ' . get_bloginfo('url') . ' để tìm hiểu thêm.', $classroom_id);
            if(is_array($arguments) && $arguments) {
                $to = isset($arguments[1]) ? (is_array($arguments[1]) ? implode(',', $arguments[1]) : $arguments[1]) : $to;
            }
        }

        private function sendNotyResponse($status = true, $message = '', $data = '')
        {
            $reponse = array(
                'status'    => $status,
                'message'   => $message,
                'data'      => $data
            );
            write_log($reponse);
            return $reponse;
        }

        private function generateMessage($message, $id)
        {
            $data = array(
                'class_ID'          => get_post_meta( $id, 'class_ID', true ),
                'class_name'        => get_the_title( $id ),
                'class_address'     => get_post_meta( $id, 'class_address', true ),
                'site_name'         => get_bloginfo('name'), 
                'site_url'          => get_bloginfo('url'), 
            );

            foreach($data as $key => $value) {
                $message = preg_replace('/{'.$key.'}/i', $value, $message);
            }
            
            return $message;
        }
    }
}

