<?php
class Router
{
    private Db $db;
    private Session $session;
    private Toolset $toolset;

    function __construct(Db &$db)
    {
        $this->db = $db;
        $this->session = new Session();
        $this->toolset = new Toolset();
    }

    private function _routingPreparePassword(string $secret, string $redirectTo, string $salt) : string
    {
        return "$secret$redirectTo$salt";
    }

    public function genSLink(string $redirectTo) : string
    {
        $grain = $this->db->customWhereClause('Grain', ['redirectTo' => $redirectTo])[0];
        $password = $this->_routingPreparePassword($grain->secret, $grain->redirectTo, $this->session->read('salt'));
        $key = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);
        return $grain->redirectTo . '?uid=' . $grain->uid . '&skey=' . base64_encode($key);
    }

    public function routeLink(string $uid, string $key)
    {
        $grain = $this->db->customWhereClause('Grain', ['uid' => $uid])[0];
        $password = $this->_routingPreparePassword($grain->secret, $grain->redirectTo, $this->session->read('salt'));
        if (password_verify($password, base64_decode($key)))
        {
            $this->toolset->location($grain->redirectTo . '?skey=' . base64_encode($key));
        }
    }

    public function keyLinkValidator(string $key, string $scriptName) : bool
    {
        $grain = $this->db->customWhereClause('Grain', ['redirectTo' => $scriptName])[0];
        $password = $this->_routingPreparePassword($grain->secret, $scriptName, $this->session->read('salt'));

        return password_verify($password, base64_decode($key));
    }
}