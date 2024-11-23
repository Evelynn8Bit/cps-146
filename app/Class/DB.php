<?php
require_once 'env.php';

class DB
{
    private static $CON = null;  // Static connection variable
    private static $pdo = null;  // Static PDO instance

    private function __construct()
    {
        loadEnv(__DIR__ . '/.env');
        self::$CON = [
            "host" => $_ENV['DB_HOST'],
            "port" => $_ENV['DB_PORT'],
            "dbname" => $_ENV['DB_DATABASE'],
            "username" => $_ENV['DB_USERNAME'],
            "password" => $_ENV['DB_PASSWORD'],
        ];
    }

    public static function getInstance()
    {
        if (self::$pdo === null) {
            new DB();  // Initialize the connection parameters
            self::connect();  // Establish the connection
        }
        return self::$pdo;  // Return the single PDO instance
    }

    private static function connect()
    {
        try {
            $connection = $_ENV['DB_CONNECTION'] ?? 'mysql';  // Default to MySQL if not provided
            $dsn = "$connection:host=" . self::$CON['host'] . ";port=" . self::$CON['port'] . ";dbname=" . self::$CON['dbname'] . ";charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$pdo = new PDO($dsn, self::$CON['username'], self::$CON['password'], $options);

            echo "Database connection established successfully!";
        } catch (Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function query($sql, $params = [])
    {
        try {
            $stmt = self::getInstance()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public static function execute($sql, $params = [])
    {
        try {
            $stmt = self::getInstance()->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            die("Query execution failed: " . $e->getMessage());
        }
    }

    public static function lastInsertId()
    {
        return self::getInstance()->lastInsertId();
    }

    // Fetch all users
    public static function get_users()
    {
        $sql = "SELECT * FROM users";
        return self::query($sql);
    }

    // Fetch all posts
    public static function get_posts()
    {
        $sql = "SELECT * FROM posts";
        return self::query($sql);
    }

    // Fetch all comments
    public static function get_comments()
    {
        $sql = "SELECT * FROM comments";
        return self::query($sql);
    }

    // Fetch all roles
    public static function get_roles()
    {
        $sql = "SELECT * FROM roles";
        return self::query($sql);
    }

    // Fetch all comment likes
    public static function get_comment_likes()
    {
        $sql = "SELECT * FROM comment_likes";
        return self::query($sql);
    }

    // Fetch all post likes
    public static function get_post_likes()
    {
        $sql = "SELECT * FROM post_likes";
        return self::query($sql);
    }

    // Fetch all followed users
    public static function get_user_followed_users()
    {
        $sql = "SELECT * FROM user_followed_user";
        return self::query($sql);
    }
}
