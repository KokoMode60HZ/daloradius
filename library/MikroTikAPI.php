<?php
/**
 * MikroTik RouterOS API Client
 * Simple PHP class untuk koneksi ke MikroTik Router fisik
 */

class MikroTikAPI {
    private $host;
    private $port;
    private $username;
    private $password;
    private $connected = false;
    private $api;

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
        // Validasi IP dan port
        if (!filter_var($this->host, FILTER_VALIDATE_IP)) {
            throw new Exception("Login gagal: IP Address tidak valid");
        }
        if (!is_numeric($this->port) || $this->port < 1 || $this->port > 65535) {
            throw new Exception("Login gagal: Port API tidak valid");
        }

        // Koneksi ke router menggunakan API client (misal dengan RouterOS API PHP client)
        // Contoh menggunakan RouterOS API library (harus diinstall dan disesuaikan)
        $this->api = new RouterosAPI();
        $this->api->debug = false;
        if ($this->api->connect($this->host, $this->username, $this->password, $this->port)) {
            $this->connected = true;
            return true;
        } else {
            throw new Exception("Login gagal: tidak dapat terhubung ke router");
        }
    }

    /**
     * Disconnect dari router
     */
    public function disconnect() {
        if ($this->connected && $this->api) {
            $this->api->disconnect();
        }
        $this->connected = false;
    }

    /**
     * Ambil data user aktif
     */
    public function getActiveUsers() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }
        return $this->api->comm("/ppp/active/print");
    }

    /**
     * Ambil data interface
     */
    public function getInterfaces() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }
        return $this->api->comm("/interface/print");
    }

    /**
     * Ambil data system resource
     */
    public function getSystemResource() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }
        return $this->api->comm("/system/resource/print");
    }

    /**
     * Ambil data bandwidth monitoring
     */
    public function getBandwidthMonitoring() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }
        // Contoh monitoring traffic interface, sesuaikan dengan API router
        return $this->api->comm("/interface/monitor-traffic", ["interface" => "all", "once" => true]);
    }

    /**
     * Ambil data PPP profiles
     */
    public function getPPPProfiles() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }
        return $this->api->comm("/ppp/profile/print");
    }

    /**
     * Test koneksi dengan router fisik
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
