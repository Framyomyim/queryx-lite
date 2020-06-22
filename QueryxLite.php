<?php 

    /**
     * 
     */

    namespace QueryX\Common;

    use Exception;

    class LiteDatabase {
        
        private $configs = [
            'collectionDir'     =>  '',
            'configFile'        =>  ''
        ];

        private $encodeKey = 'd593eba4020f2aae22af0c7232e8ca547f9a94c4';

        private $statusConnection = 0;

        function __construct() {
            require_once __DIR__ . '/QueryxAction.php';
        }

        public function set($name = null, $value = null) {
            if($name !== null && $value !== null) in_array($name, array_keys($this->configs)) ? $this->configs[$name] = $value : false;
            else return $this;
        }

        public function getConfig($encoded = true) {
            return $encoded ? $this->encryptString(join(' | ', $this->configs)) : $this->configs;
        }

        protected
        function encryptString($value) : string {
            if(!$value) return false;

            $key = sha1($this->encodeKey);
            $strLen = strlen($value);
            $keyLen = strlen($key);
            $j = 0;
            $crypttext = '';
            for($i = 0; $i < $strLen; $i++) {
                $ordStr = ord(substr($value, $i, 1));
                if($j == $keyLen) $j = 0;
                $ordKey = ord(substr($key, $j, 1));
                $j++;
                $crypttext .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
            }
    
            return $crypttext;
        }
    
        protected function decryptString($value) : string {
            if(!$value) return false;

            $key = sha1($this->encodeKey);
            $strLen = strlen($value);
            $keyLen = strlen($key);
            $j = 0;
            $decrypttext = '';
            for($i = 0; $i < $strLen; $i += 2) {
                $ordStr = hexdec(base_convert(strrev(substr($value, $i, 2)), 36, 16));
                if($j == $keyLen) $j = 0;
                $ordKey = ord(substr($key, $j, 1));
                $j++;
                $decrypttext .= chr($ordStr - $ordKey);
            }
    
            return $decrypttext;
        }

        public function createConfig($username, $password = null) : void {
            $config = json_encode([
                'username' => $username,
                'password' => $password
            ]);
            $folders = explode('/', $this->configs['configFile']);
            array_pop($folders);
            $folders = join('/', $folders);
            if(is_dir($folders)) file_put_contents($this->configs['configFile'] . '.lite', $this->encryptString($config));
            else {
                mkdir($folders);
                file_put_contents($this->configs['configFile'] . '.lite', $this->encryptString($config));
            }
        }
        
        private function getAdminConfig() : array {
            $data = $this->decryptString(file_get_contents($this->configs['configFile'] . '.lite'));
            $data = json_decode($data, true);
            return $data;
        }

        public function createCollection(string $name) : void {
            if(is_dir($this->configs['collectionDir'])) {
                if(is_file($this->configs['collectionDir'] . $name . '.lite')) die('Collection already exists');
                else file_put_contents($this->configs['collectionDir'] . $name . '.lite', $this->encryptString('{}'));
            } else {
                die('Please create folder for keep collections');
            }
        }

        public function __call($method, $arguments) {
            if(!method_exists($this, $method)) die('Method <b>' . $method . '</b> does not exist');
        }

        public function checkConnection() {
            if($this->statusConnection === false) die('Connection is not established');
            else return true;
        }

        public function collection($name) {
            return new \QueryX\Common\LiteDatabase\Action($name, $this->configs['collectionDir']);
        }
    }

?>