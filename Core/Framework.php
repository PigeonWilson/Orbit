<?php
if (!defined('PREVENT_DIRECT_FILE_ACCESS_CONST')) die();
class Framework
{
    // dict
    public readonly array $common;
    public readonly array $common_animal;
    public readonly array $common_color;
    public readonly array $common_fruit;
    public readonly array $common_metal;

    // database layer
    public Db $db;

    // session layer
    public Session $session;

    // common tools and utilities
    public Toolset $toolset;

    // logging
    public Logger $logger;

    // security and rules enforcement
    public SecurityEnforcer $se;

    // link generator
    public Router $router;


    function __construct($dbHost, $dbName, $dbUsername, $dbPassword)
    {
        $this->db = new Db($dbHost, $dbName, $dbUsername, $dbPassword);
        $this->session = new Session();
        $this->toolset = new Toolset();
        $this->logger = new Logger($this->db);
        $this->se = new SecurityEnforcer($this->db);
        $this->router = new Router($this->db);

        // load dict
        $common_content = file_get_contents('Core' . DIRECTORY_SEPARATOR . 'Dict' . DIRECTORY_SEPARATOR . 'common.txt');
        $common_content_animal = file_get_contents('Core' . DIRECTORY_SEPARATOR . 'Dict' . DIRECTORY_SEPARATOR . 'common_animal.txt');
        $common_content_color = file_get_contents('Core' . DIRECTORY_SEPARATOR . 'Dict' . DIRECTORY_SEPARATOR . 'common_color.txt');
        $common_content_fruit = file_get_contents('Core' . DIRECTORY_SEPARATOR . 'Dict' . DIRECTORY_SEPARATOR . 'common_fruit.txt');
        $common_content_metal = file_get_contents('Core' . DIRECTORY_SEPARATOR . 'Dict' . DIRECTORY_SEPARATOR . 'common_metal.txt');

        // initialize dict
        $this->common = explode(',', $common_content);
        $this->common_animal = explode(',', $common_content_animal);
        $this->common_color = explode(',', $common_content_color);
        $this->common_fruit = explode(',', $common_content_fruit);
        $this->common_metal = explode(',', $common_content_metal);
    }

    // runs on page load
    public function onPageLoad()
    {
        // set routing salt
        $this->session->create('salt', $this->toolset->random_str(128));

        // set form token
        $this->session->create('token', $this->toolset->random_str(128));

        // common variables
        $_METHOD = $this->toolset->clean($_SERVER['REQUEST_METHOD']);
        $_PATH_INFO = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/')) ?? [];
        $_INPUT = file_get_contents('php://input');
        $_IP = $this->toolset->clean($_SERVER['REMOTE_ADDR']);
        $_USER_AGENT = $this->toolset->clean($_SERVER['HTTP_USER_AGENT']) ?? null;
        $_CURRENT_SCRIPT = $this->toolset->getScriptName();

        // check if ip is on the list, if it is, kill the script
        if ($this->se->blacklist_check_status($_IP)) $this->se->kick('the system detected an invalid use of our system and blocked your ip. Contact the admin if you think it is a mistake.', false);

        // routing validation
        $poe_exception_list = [SITEMAP_TESTING, SITEMAP_INDEX, SITEMAP_INVITE_ACTIVATION, SITEMAP_ERROR];
        if (!in_array($this->toolset->getScriptName(), $poe_exception_list))
        {
            if (!isset($_REQUEST['skey'])) $this->se->kick('routing: invalid operation, skey is missing. ban = false', false);

            if (!$this->router->keyLinkValidator($_REQUEST['skey'], $this->toolset->getScriptName()))
            {
                $this->se->kick('routing: skey is invalid. ban = true');
            }
        }

        // proxy detector
        if (!ALLOW_PROXY)
        {
            if ($this->se->detectProxy_header_test() || $this->se->detectProxy_port_scan())
            {
                if (!SETUP_MODE)
                {
                    $this->se->kick('no proxy allowed');
                }
            }
        }

        // request logger
        $this->logger->newEntry($_METHOD, $_PATH_INFO, $_REQUEST, $_INPUT, $_IP, $_USER_AGENT, $_CURRENT_SCRIPT);

        // variables cleaning, ALWAYS use $_REQUEST, confirm eligibility with $_METHOD
        foreach (array_keys($_REQUEST) as $key)
        {
            // code injection detection
            if
            (
                $key != 'password' && // password can contain any char
                str_contains($_REQUEST[$key], '<')
                || str_contains($_REQUEST[$key], '>')
            ){
                $this->se->kick('code injection detected');
            }

            // no dirty values allowed
            $_REQUEST[$key] = $this->toolset->clean($_REQUEST[$key]);

            // no dirty keys allowed
            if ($key != $this->toolset->clean($key))
            {
                unset($_REQUEST[$key]);
                // kick and ban
                $this->se->kick('no dirty key allowed');
            }
        }


    }
}