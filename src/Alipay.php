<?php
namespace Karnin\Alipay;

class Alipay
{
    private $config = [
        'partner' => '',
        'private_key' => '',
        'alipay_public_key' => '',
        'cacert' => '',
        'sign_type' => 'RSA',
        'input_charset' => 'utf-8',
        'service' => 'mobile.securitypay.pay',
        'transport' => 'http',
    ];

    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            if (array_key_exists($key, $this->config)) {
                $this->config[$key] = $config[$key];
            }
        }
        $this->config['cacert']='../cacert.pem';
    }

    public function sign($data)
    {
        $result = [];
        $result['partner'] = $this->config['partner'];
        $result['seller_id'] = $this->config['partner'];
        $result['service'] = $this->config['service'];
        $result['payment_type'] = '1';
        $result['_input_charset'] = $this->config['input_charset'];
        $result['out_trade_no'] = $data['out_trade_no'];
        $result['subject'] = $data['subject'];
        $result['body'] = $data['body'];
        $result['total_fee'] = $data['total_fee'];
        $result['notify_url'] = $data['notify_url'];
        $d = Core::createLinkstring($data);
        $rsa_sign = urlencode(Rsa::rsaSign($data, $this->config['private_key']));
        $d = $d . '&sign=' . '"' . $rsa_sign . '"' . '&sign_type=' . '"' . $this->config['sign_type'] . '"';
        return $d;
    }

    public function notify(){

    }
}