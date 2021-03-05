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
            // $arguments[2] : $subject (Tiêu đề mail)
            // $arguments[3] : $message (Nội dung mail)

            $result = false;
            $to = 'thuyhu9876@gmail.com';
            $subject = 'GỬI TỪ GIA SƯ NHẬT ANH';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $message = 'Có lớp mới được đăng tải. Ghé thăm ' . get_bloginfo('url') . ' để tìm hiểu thêm.';
            if(is_array($arguments) && $arguments) {
                $to = isset($arguments[1]) ? (is_array($arguments[1]) ? implode(',', $arguments[1]) : $arguments[1]) : $to;
                $subject = isset($arguments[2]) ? $arguments[2] : $subject;
                $message = isset($arguments[3]) ? $this->generateMessage( $arguments[3], isset($arguments[0]) ? $arguments[0] : 0 ) : $message;
            }

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
            // $arguments[2] : $message (Nội dung mail)
            $result = false;
            $to = '+84986114671';
            $message = 'Có lớp mới được đăng tải. Ghé thăm ' . get_bloginfo('url') . ' để tìm hiểu thêm.';
            if(is_array($arguments) && $arguments) {
                $to = isset($arguments[1]) ? (is_array($arguments[1]) ? implode(',', $arguments[1]) : $arguments[1]) : $to;
                $message = isset($arguments[2]) ? $this->generateMessage( $arguments[2], isset($arguments[0]) ? $arguments[0] : 0 ) : $message;
            }
        }

        private function sendNotyResponse($status = true, $message = '', $data = '')
        {
            return array(
                'status'    => $status,
                'message'   => $message,
                'data'      => $data
            );
        }

        private function generateMessage($message, $id)
        {
            $data = array(
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

