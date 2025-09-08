<?php
/**
 * MikroTik RouterOS API Client
 * Simple PHP class untuk koneksi ke MikroTik Router
 */

class MikroTikAPI {
    private $host;
    private $port;
    private $username;
    private $password;
    private $socket;
    private $connected = false;

    public function __construct($host, $port = 8728, $username = 'admin', $password = '') {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Koneksi ke MikroTik Router
     */
    public function connect() {
        try {
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if (!$this->socket) {
                throw new Exception("Tidak bisa membuat socket");
            }

            $result = socket_connect($this->socket, $this->host, $this->port);
            if (!$result) {
                throw new Exception("Tidak bisa koneksi ke router: " . socket_strerror(socket_last_error()));
            }

            // Login ke router
            $this->write('/login');
            $this->write('=name=' . $this->username);
            $this->write('=password=' . $this->password);
            $this->write('');

            $response = $this->read();
            if (strpos($response, '!done') !== false) {
                $this->connected = true;
                return true;
            } else {
                throw new Exception("Login gagal: " . $response);
            }
        } catch (Exception $e) {
            $this->connected = false;
            throw new Exception("Koneksi gagal: " . $e->getMessage());
        }
    }

    /**
     * Disconnect dari router
     */
    public function disconnect() {
        if ($this->connected && $this->socket) {
            socket_close($this->socket);
            $this->connected = false;
        }
    }

    /**
     * Kirim command ke router
     */
    public function sendCommand($command, $params = []) {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        $this->write($command);
        foreach ($params as $key => $value) {
            $this->write('=' . $key . '=' . $value);
        }
        $this->write('');

        return $this->readResponse();
    }

    /**
     * Ambil data user aktif
     */
    public function getActiveUsers() {
        return $this->sendCommand('/ppp/active/print');
    }

    /**
     * Ambil data interface
     */
    public function getInterfaces() {
        return $this->sendCommand('/interface/print');
    }

    /**
     * Ambil data system resource
     */
    public function getSystemResource() {
        return $this->sendCommand('/system/resource/print');
    }

    /**
     * Write data ke socket
     */
    private function write($data) {
        $length = strlen($data);
        $packet = pack('N', $length) . $data;
        socket_write($this->socket, $packet);
    }

    /**
     * Read data dari socket
     */
    private function read() {
        $length = socket_read($this->socket, 4);
        if ($length === false) return '';
        
        $length = unpack('N', $length)[1];
        if ($length == 0) return '';
        
        return socket_read($this->socket, $length);
    }

    /**
     * Read response lengkap
     */
    private function readResponse() {
        $response = [];
        while (true) {
            $line = $this->read();
            if ($line === '') break;
            $response[] = $line;
        }
        return $response;
    }

    /**
     * Cek status koneksi
     */
    public function isConnected() {
        return $this->connected;
    }

    /**
     * Test koneksi sederhana
     */
    public function testConnection() {
        try {
            if ($this->connect()) {
                $result = $this->getSystemResource();
                $this->disconnect();
                return ['status' => 'success', 'data' => $result];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
?>
