<?php
/**
 * MikroTik Simulator - Mock data untuk demo
 * Menggantikan MikroTikAPI.php untuk simulasi tanpa router fisik
 */

class MikroTikSimulator {
    private $host;
    private $port;
    private $username;
    private $password;
    private $connected = false;

    public function __construct($host, $port = 8728, $username = 'admin', $password = '') {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Simulasi koneksi ke MikroTik Router
     */
    public function connect() {
        // Simulasi delay koneksi
        usleep(500000); // 0.5 detik
        
        // Simulasi validasi kredensial
        if ($this->username == 'admin' && $this->password == '') {
            $this->connected = true;
            return true;
        } elseif ($this->username == 'api-user' && $this->password == 'api123') {
            $this->connected = true;
            return true;
        } else {
            throw new Exception("Login gagal: username/password salah");
        }
    }

    /**
     * Disconnect dari router
     */
    public function disconnect() {
        $this->connected = false;
    }

    /**
     * Simulasi data user aktif
     */
    public function getActiveUsers() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        // Mock data user aktif
        return [
            [
                'name' => 'pelanggan001',
                'address' => '192.168.1.100',
                'uptime' => '2d 5h 30m',
                'bytes-in' => '1.2GB',
                'bytes-out' => '800MB',
                'service' => 'pppoe'
            ],
            [
                'name' => 'pelanggan002', 
                'address' => '192.168.1.101',
                'uptime' => '1d 12h 15m',
                'bytes-in' => '2.1GB',
                'bytes-out' => '1.5GB',
                'service' => 'pppoe'
            ],
            [
                'name' => 'pelanggan003',
                'address' => '192.168.1.102', 
                'uptime' => '5h 45m',
                'bytes-in' => '500MB',
                'bytes-out' => '300MB',
                'service' => 'pppoe'
            ],
            [
                'name' => 'hotspot001',
                'address' => '192.168.1.200',
                'uptime' => '3d 8h 20m',
                'bytes-in' => '3.2GB',
                'bytes-out' => '2.8GB',
                'service' => 'hotspot'
            ]
        ];
    }

    /**
     * Simulasi data interface
     */
    public function getInterfaces() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        return [
            [
                'name' => 'ether1',
                'type' => 'ether',
                'mtu' => '1500',
                'mac-address' => '00:0C:42:12:34:56',
                'running' => 'true',
                'disabled' => 'false'
            ],
            [
                'name' => 'ether2',
                'type' => 'ether', 
                'mtu' => '1500',
                'mac-address' => '00:0C:42:12:34:57',
                'running' => 'true',
                'disabled' => 'false'
            ],
            [
                'name' => 'pppoe-out1',
                'type' => 'pppoe-out',
                'mtu' => '1480',
                'mac-address' => '00:0C:42:12:34:58',
                'running' => 'true',
                'disabled' => 'false'
            ]
        ];
    }

    /**
     * Simulasi data system resource
     */
    public function getSystemResource() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        return [
            [
                'version' => '7.12.1',
                'build-time' => '2023-10-15 12:30:45',
                'free-memory' => '45MB',
                'total-memory' => '128MB',
                'cpu' => 'MIPS 74Kc V4.12',
                'cpu-count' => '1',
                'cpu-frequency' => '600MHz',
                'free-hdd-space' => '2.1GB',
                'total-hdd-space' => '8GB',
                'architecture-name' => 'mipsbe',
                'board-name' => 'RB750',
                'uptime' => '15d 8h 30m'
            ]
        ];
    }

    /**
     * Simulasi data bandwidth monitoring
     */
    public function getBandwidthMonitoring() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        return [
            [
                'interface' => 'ether1',
                'rx-bits-per-second' => '15.2Mbps',
                'tx-bits-per-second' => '8.7Mbps',
                'rx-packets-per-second' => '1250',
                'tx-packets-per-second' => '980'
            ],
            [
                'interface' => 'pppoe-out1',
                'rx-bits-per-second' => '12.8Mbps', 
                'tx-bits-per-second' => '6.3Mbps',
                'rx-packets-per-second' => '1100',
                'tx-packets-per-second' => '850'
            ]
        ];
    }

    /**
     * Simulasi data PPP profiles
     */
    public function getPPPProfiles() {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        return [
            [
                'name' => 'paket-10mb',
                'local-address' => '192.168.1.1',
                'remote-address' => '192.168.1.100-192.168.1.200',
                'rate-limit' => '10M/10M',
                'only-one' => 'yes'
            ],
            [
                'name' => 'paket-20mb',
                'local-address' => '192.168.1.1', 
                'remote-address' => '192.168.1.201-192.168.1.250',
                'rate-limit' => '20M/20M',
                'only-one' => 'yes'
            ],
            [
                'name' => 'hotspot-guest',
                'local-address' => '192.168.1.1',
                'remote-address' => '192.168.1.251-192.168.1.254',
                'rate-limit' => '2M/2M',
                'only-one' => 'no'
            ]
        ];
    }

    /**
     * Cek status koneksi
     */
    public function isConnected() {
        return $this->connected;
    }

    /**
     * Test koneksi dengan simulasi
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

    /**
     * Simulasi command umum
     */
    public function sendCommand($command, $params = []) {
        if (!$this->connected) {
            throw new Exception("Tidak terhubung ke router");
        }

        // Simulasi delay response
        usleep(200000); // 0.2 detik

        switch ($command) {
            case '/ppp/active/print':
                return $this->getActiveUsers();
            case '/interface/print':
                return $this->getInterfaces();
            case '/system/resource/print':
                return $this->getSystemResource();
            case '/interface/monitor-traffic':
                return $this->getBandwidthMonitoring();
            case '/ppp/profile/print':
                return $this->getPPPProfiles();
            default:
                return ['message' => 'Command not implemented in simulator'];
        }
    }
}
?>
