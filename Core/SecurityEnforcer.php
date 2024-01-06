<?php
class SecurityEnforcer
{
    private Db $db;
    private Logger $logger;
    private Toolset $toolset;

    function __construct(Db &$db)
    {
        $this->db = $db;
        $this->logger = new Logger($db);
        $this->toolset = new Toolset();
    }

    public function kick(string $reason = '', bool $ban = true) : void
    {
        if (isset($_SERVER['REMOTE_ADDR']))
        {
            $remoteAddr = $this->toolset->clean($_SERVER['REMOTE_ADDR']);
        }else{
            $remoteAddr = null;
        }

        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $userAgent = $this->toolset->clean($_SERVER['HTTP_USER_AGENT']);
        }else{
            $userAgent = null;
        }

        // ban?
        if ($ban != false)
        {
            if (!is_null($remoteAddr) && strlen($remoteAddr) > 0)
            {
                // ban forever
                // https://stackoverflow.com/a/3953378
                $dt = new DateTime('1st January 2999');
                $this->blacklist_create($remoteAddr, $dt, $reason);
            }
        }

        $data =
            [
                'Session' => $_SESSION ?? null,
                'Ip' => $remoteAddr,
                'UserAgent' => $userAgent,
                'CurrentScript' => $this->toolset->getScriptName()
            ];

        $this->logger->systemEntry($reason, $data);

        if (SETUP_MODE) $this->toolset->location(SITEMAP_PUBLIC_URL . SITEMAP_ERROR . '?reason=' . $reason);

        $this->toolset->location( SITEMAP_PUBLIC_URL . SITEMAP_ERROR);
    }

    // add to the blacklist and get a ticket key
    // the key is needed to update the blacklist entry
    public function blacklist_create(string $ip, DateTime $expiration, string $reason = '') : string
    {
        // check if ip entry exists
        if ($this->blacklist_check_status($ip)) return ''; // entry already exist

        $uid = 'uid' . $this->toolset->random_str(18);
        $this->db->create('BlackList',
            [
                'id' => NULL,
                'uid' => $uid,
                'ip' => $ip,
                'reason' => $reason,
                'expiration' => $expiration->format('Y-m-d H:i:s')
            ]);
        return $uid;
    }

    // return true if the ip is on the list
    public function blacklist_check_status(string $ip) : bool
    {
        $check = $this->db->customWhereClause('BlackList',
            [
                'ip' => $ip
            ]);

        if (count($check) > 0)
        {
            return true;
        }

        return false;
    }

    public function detectProxy_port_scan() : bool
    {
        $proxy_ports = array(80,81,8080,443,1080,6588,3128);
        foreach($proxy_ports as $test_port) {
            if(@fsockopen($_SERVER['REMOTE_ADDR'], $test_port, $errno, $errstr, 5)) {
                return true;
            }
        }

        return false;
    }

    public function detectProxy_header_test() : bool
    {
        $test_HTTP_proxy_headers = array(
            'HTTP_VIA',
            'VIA',
            'Proxy-Connection',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'HTTP_FORWARDED_FOR_IP',
            'X-PROXY-ID',
            'MT-PROXY-ID',
            'X-TINYPROXY',
            'X_FORWARDED_FOR',
            'FORWARDED_FOR',
            'X_FORWARDED',
            'FORWARDED',
            'CLIENT-IP',
            'CLIENT_IP',
            'PROXY-AGENT',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'FORWARDED_FOR_IP',
            'HTTP_PROXY_CONNECTION');

        foreach($test_HTTP_proxy_headers as $header){
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                return true;
            }
        }

        return false;
    }


}