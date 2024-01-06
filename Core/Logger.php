<?php
class Logger
{
    private Db $db;

    function __construct(Db &$db)
    {
        $this->db = $db;
    }

    public function newEntry
    (
        string $method,
        array | null $request,
        array | null $request2,
        string $input,
        string | null $ip,
        string | null $userAgent,
        string $currentScript
    ) : void
    {
        $this->db->create('LoggingEntry',
            [
                'id' => NULL,
                'method' => $method,
                'request' => json_encode($request, JSON_PRETTY_PRINT),
                'request2' => json_encode($request2, JSON_PRETTY_PRINT),
                'input' => $input,
                'ip' => $ip,
                'userAgent' => $userAgent,
                'currentScript' => $currentScript
            ]);
    }

    public function systemEntry(string $reason, array $data = null) : void
    {
        $this->db->create('LoggingSystemEntry',
            [
                'id' => NULL,
                'reason' => $reason,
                'data' => json_encode($data, JSON_PRETTY_PRINT)
            ]);
    }
}