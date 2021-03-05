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

        public function sendMail($message)
        {
            # code...
            $mailResult = false;
            $to = "thuyhu9876@gmail.com";
            $subject = 'GỬI TỪ QUANG TRUNG';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            $mailResult = wp_mail( $to, $subject, $message, $headers );

            if($mailResult == false) {
                return $this->sendNotyResponse(false, 'Có lỗi xảy ra trong quá trình gửi tin nhắn', $message);
            }
            
            return $this->sendNotyResponse(true, 'Gửi tin nhắn thành công', $message);
        }

        public function sendSMS($message)
        {
            
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

