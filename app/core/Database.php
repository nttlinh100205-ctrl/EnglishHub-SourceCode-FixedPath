<?php
/**
 * Kết nối MySQL — hỗ trợ nhiều database trên cùng host/port.
 *
 * Dùng:
 *   $pdo = (new Database())->getConnection();              // DB_NAME (english_learning)
 *   $pdo = (new Database('learning'))->getConnection();    // english_learning
 *   $pdo = (new Database('hub'))->getConnection();         // english_hub
 *   $pdo = Database::conn('hub');
 *   $pdo = Database::conn('learning');
 */
class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    /** Cache kết nối theo tên DB để không mở PDO lặp */
    private static $pool = [];

    /**
     * @param string|null $which  null|'learning'|'hub'|tên database thật
     */
    public function __construct($which = null) {
        $this->host     = DB_HOST;
        $this->port     = DB_PORT;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->db_name  = self::resolveName($which);
    }

    public static function resolveName($which = null) {
        if ($which === null || $which === '' || $which === 'default') {
            return defined('DB_NAME') ? DB_NAME : 'english_learning';
        }
        $key = strtolower((string)$which);
        if ($key === 'learning' || $key === 'learn') {
            return defined('DB_NAME_LEARNING') ? DB_NAME_LEARNING : 'english_learning';
        }
        if ($key === 'hub' || $key === 'english_hub') {
            return defined('DB_NAME_HUB') ? DB_NAME_HUB : 'english_hub';
        }
        // Tên database tùy ý
        return (string)$which;
    }

    public function getConnection() {
        $name = $this->db_name;
        if (isset(self::$pool[$name]) && self::$pool[$name] instanceof PDO) {
            $this->conn = self::$pool[$name];
            return $this->conn;
        }

        try {
            $dsn = 'mysql:host=' . $this->host
                 . ';port=' . $this->port
                 . ';dbname=' . $name
                 . ';charset=utf8mb4';

            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            self::$pool[$name] = $this->conn;
        } catch (PDOException $e) {
            die('Lỗi kết nối CSDL [' . htmlspecialchars($name) . '] (cổng ' . $this->port . '): ' . $e->getMessage());
        }
        return $this->conn;
    }

    /** Shortcut: Database::conn('hub') */
    public static function conn($which = null) {
        return (new self($which))->getConnection();
    }

    public function getDbName() {
        return $this->db_name;
    }
}
