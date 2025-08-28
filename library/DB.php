<?php
/*
 * PEAR DB Wrapper for mysqli
 * This file provides compatibility with PEAR DB while using mysqli
 */

// Define PEAR constants if not already defined
if (!defined('PEAR_ERROR_CALLBACK')) {
    define('PEAR_ERROR_CALLBACK', 1);
}
if (!defined('DB_FETCHMODE_ASSOC')) {
    define('DB_FETCHMODE_ASSOC', 1);
}

class DBResult {
    private $result;
    
    public function __construct($mysqli_result) {
        $this->result = $mysqli_result;
    }
    
    public function numRows() {
        return $this->result->num_rows;
    }
    
    public function fetchRow($fetchmode = null) {
        if ($fetchmode === DB_FETCHMODE_ASSOC) {
            return $this->result->fetch_assoc();
        } else {
            return $this->result->fetch_array();
        }
    }
    
    public function free() {
        $this->result->free();
    }
}

class DB {
    private static $instance = null;
    private $mysqli = null;
    
    public static function connect($dsn) {
        if (self::$instance === null) {
            self::$instance = new self($dsn);
        }
        return self::$instance;
    }
    
    private function __construct($dsn) {
        // Parse DSN: mysql://user:pass@host:port/database
        $parts = parse_url($dsn);
        
        $host = $parts['host'] ?? 'localhost';
        $port = $parts['port'] ?? 3306;
        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';
        $database = ltrim($parts['path'] ?? '', '/');
        
        $this->mysqli = new mysqli($host, $user, $pass, $database, $port);
        
        if ($this->mysqli->connect_error) {
            throw new Exception("Connection failed: " . $this->mysqli->connect_error);
        }
        
        $this->mysqli->set_charset("utf8");
    }
    
    public function query($sql) {
        $result = $this->mysqli->query($sql);
        if ($result === false) {
            throw new Exception("Query failed: " . $this->mysqli->error);
        }
        
        // Wrap mysqli_result to provide PEAR DB compatibility
        if ($result instanceof mysqli_result) {
            return new DBResult($result);
        }
        
        return $result;
    }
    
    public function fetchRow($result, $fetchmode = null) {
        if ($result instanceof DBResult) {
            return $result->fetchRow($fetchmode);
        }
        return false;
    }
    
    public function numRows($result) {
        if ($result instanceof DBResult) {
            return $result->numRows();
        }
        return 0;
    }
    
    public function freeResult($result) {
        if ($result instanceof DBResult) {
            $result->free();
        }
    }
    
    public function disconnect() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
    
    public function getMessage() {
        return $this->mysqli->error;
    }
    
    public static function isError($obj) {
        return $obj instanceof Exception;
    }
    
    // Additional methods for compatibility
    public function setErrorHandling($mode, $callback) {
        // mysqli doesn't have this feature, so we'll ignore it
        return true;
    }
    
    public function getOne($sql) {
        $result = $this->query($sql);
        if ($result instanceof DBResult && $result->numRows() > 0) {
            $row = $result->fetchRow();
            $result->free();
            return $row[0];
        }
        return null;
    }
    
    public function getRow($sql) {
        $result = $this->query($sql);
        if ($result instanceof DBResult && $result->numRows() > 0) {
            $row = $result->fetchRow(DB_FETCHMODE_ASSOC);
            $result->free();
            return $row;
        }
        return null;
    }
    
    public function getAll($sql) {
        $result = $this->query($sql);
        if ($result instanceof DBResult) {
            $rows = [];
            while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
                $rows[] = $row;
            }
            $result->free();
            return $rows;
        }
        return [];
    }
    
    public function escapeSimple($string) {
        return $this->mysqli->real_escape_string($string);
    }
    
    /**
     * Prepare statement untuk query yang aman
     */
    public function prepare($sql) {
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }
        return $stmt;
    }
    
    /**
     * Get last insert ID
     */
    public function insert_id() {
        return $this->mysqli->insert_id;
    }
} 