<?php

class PushNotification
{
    private $pem_file_name = "";
    private $pem_file_password = "";
    private $fcm_api_key = "";
    private $fcm_api = "https://fcm.googleapis.com/fcm/send";
    private $ios_api = "ssl://gateway.sandbox.push.apple.com:2195";

    public function __construct($fcm_key, $pem_file, $pem_pass)
    {
        self::setFcmApi($fcm_key);
        self::setPemFile($pem_file, $pem_pass);
    }

    public function setFcmApi($key, $url = "https://fcm.googleapis.com/fcm/send")
    {
        $this->fcm_api_key = $key;
        $this->fcm_api = $url;
    }

    public function setIOSApi($ios_api, $name, $password)
    {
        $this->ios_api = $ios_api;
        self::setPemFile($name, $password);
    }

    public function setPemFile($name, $password)
    {
        $this->pem_file_name = $name;
        $this->pem_file_password = $password;
    }

    public function sendNotIOS($msg, $regId)
    {
        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'local_cert', $this->pem_file_name);
        stream_context_set_option($context, 'ssl', 'passphrase', $this->pem_file_password);
        $socket = stream_socket_client($this->ios_api, $error, $errorString, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $context);
        if (!$socket) return [0, "(" . $error . ") => " . $errorString];

        $body['aps'] = [
            'alert' => [
                'action-loc-key' => "Open",
                'body' => $msg,
            ],
            'sound' => 'default',
            'content-available' => "Default"
        ];

        $encodedData = json_encode($body);
        $binaryString = chr(0) . pack('n', 32) . pack('H*', $regId) . pack('n', strlen($encodedData)) . $encodedData;

        if (fwrite($socket, $binaryString, strlen($binaryString))) {
            fclose($socket);
            return [1, null];
        }
        fclose($socket);
        return [0, null];

    }

    public function sendNotAnd($title, $msg, $regId)
    {
        $headers = [
            'Authorization: key=' . $this->fcm_api_key,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcm_api);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $not = [
            'message' => $msg,
            'title' => $title,
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
            'type' => 'message',
        ];

        $fields = [
            'registration_ids' => [$regId],
            'data' => $not,
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            $return = [0, curl_error($ch)];
            curl_close($ch);
            return $return;
        }

        curl_close($ch);
        return [1, null];

    }
}