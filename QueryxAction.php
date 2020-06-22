<?php 

    namespace QueryX\Common\LiteDatabase;

    class Action extends \QueryX\Common\LiteDatabase {
        public $collection = null;
        public $document = null;
        private $collectionDir = null;

        function __construct($collection, $dir) {
            $this->collection = $collection;
            $this->collectionDir = $dir;
        }

        public function read() {
            $datas = $this->getArray();
            if($datas != false && $this->document != null) {
                return $datas[$this->document];
            }
        }

        public function update($datas = []) {
            $parentlyDatas = $this->getArray();
            if($parentlyDatas != false && $this->document != null) {
                $doc = $this->document;
                $currentlyDatas = array_merge($parentlyDatas[$doc], $datas);
                $currentlyDatas = array_merge($parentlyDatas, [
                    $doc    =>  $currentlyDatas
                ]);
                $this->write($currentlyDatas);
            }
        }

        public function document($name) {
            $datas = $this->getArray();
            if($datas != false) {
                if(isset($datas[$name])) {
                    $this->document = $name;
                    return $this;
                } else die('Document <b>' . $name . '</b> not found');
            }
        }

        public function insert($document, $datas = []) {
            $currentlyDatas = $this->getArray();
            if(isset($currentlyDatas[$document])) unset($currentlyDatas[$document]);
            $currentlyDatas[$document] = $datas;
            $this->write($currentlyDatas);
        }

        private $limit = -1; // -1 == unlimited
        public function limit(int $limit) {
            $this->limit = $limit;
            return $this;
        }

        private function getArray() {
            if(is_file($this->collectionDir . $this->collection . '.lite')) {
                $datas = json_decode($this->decryptString(file_get_contents($this->collectionDir . $this->collection . '.lite')), true);
                return $this->limit == -1 ? $datas : array_slice($datas, 0, $this->limit);
            } else return false;
        }

        private function write($datas) {
            $target = $this->collectionDir . $this->collection . '.lite';
            $datas = json_encode($datas);
            $datas = $this->encryptString($datas);
            file_put_contents($target, $datas);
        }

        public function readAll() {
            $datas = $this->getArray();
            if($datas != false) return $datas;
            else die('Cannot get collection datas');
        }

        /**
         * ==
         * ===
         * <
         * >
         * <=
         * >=
         * !=
         * !==
         */
        private $results = [];
        public function where($key, $operator, $value) {
            $collect = $this->collection;
            $datas = $this->getArray();
            if($datas != false) {
                foreach($datas as $keyAry => $doc) {
                    $dataInKey = $doc[$key];
                    $result = eval('?><?php return ' . $dataInKey . $operator . $value . '; ?>');
                    if($result === true) $this->results[$keyAry] = $doc;
                }

                return $this;
            }
        }

        public function get() {
            return $this->results;
        }

        public function deleteThis() {
            $this->limit(-1);
            $datas = $this->getArray();
            if($datas != false && $this->document == null) {
                // collection
                unlink($this->collectionDir . $this->collection . '.lite');
            } else if($datas != false && $this->document != null) {
                // document
                unset($datas[$this->document]);
                $this->write($datas);
            }
        }
    }

?>