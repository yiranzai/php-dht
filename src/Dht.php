<?php

namespace Yiranzai\Dht;

/**
 * Class Hash
 * @package Yiranzai\Dht
 */
class Dht implements \JsonSerializable
{

    public const DEFAULT_ALGO = 'time33';
    /**
     * @var string
     */
    protected $algo = self::DEFAULT_ALGO;
    /**
     * all node cache
     *
     * @var array
     */
    protected $locations = [];
    /**
     * virtual node num
     *
     * @var int
     */
    protected $virtualNodeNum = 24;
    /**
     * entity node cache
     *
     * @var
     */
    protected $nodes = [];

    /**
     * Hash constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $key => $item) {
                if ($key === 'algo') {
                    $this->algo($item);
                    continue;
                }
                $this->$key = $item;
            }
        }
    }

    /**
     * @param string $str
     * @return $this
     */
    public function algo(string $str): self
    {
        if ($this->isSupportHashAlgos($str)) {
            $this->algo = $str;
        }
        return $this;
    }

    /**
     * @param string $str
     * @return bool
     */
    public function isSupportHashAlgos(string $str): bool
    {
        return $str === self::DEFAULT_ALGO || in_array($str, $this->supportHashAlgos(), true);
    }

    /**
     * @return array
     */
    public function supportHashAlgos(): array
    {
        return hash_algos();
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        $json = json_encode($this->jsonSerialize());
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = array();
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * @param string $str
     * @return int
     */
    public function hashGenerate(string $str): int
    {
        if ($this->algo === self::DEFAULT_ALGO) {
            return $this->time33($str);
        }
        return (int)sprintf('%u', hash($this->algo, $str));
    }

    /**
     * @param string $str
     * @return int
     */
    protected function time33(string $str): int
    {
        $hash = 0;
        $str  = md5($str);
        $len  = 32;
        for ($i = 0; $i < $len; $i++) {
            $hash = ($hash << 5) + $hash + ord($str{$i});
        }
        return $hash & 0x7FFFFFFF;
    }

    /**
     * 寻找字符串所在的机器位置
     * @param string $key
     * @return bool|mixed
     */
    public function getLocation(string $key)
    {
        if (empty($this->locations)) {
            throw new \RuntimeException('This nodes is empty, please add a node');
        }

        $position = $this->hashGenerate($key);
        //默认取第一个节点
        $node = current($this->locations);
        foreach ($this->locations as $k => $v) {
            //如果当前的位置，小于或等于节点组中的一个节点，那么当前位置对应该节点
            if ($position <= $k) {
                $node = $v;
                break;
            }
        }
        return $node;
    }

    /**
     * 添加一个节点
     * @param string $node
     * @return Dht
     */
    public function addEntityNode(string $node): self
    {
        if ($this->existsNode($node)) {
            throw new \RuntimeException('This node already exists');
        }
        $this->nodes[$node] = [];
        //生成虚拟节点
        for ($i = 0; $i < $this->virtualNodeNum; $i++) {
            $tmp                   = $this->hashGenerate($node . $i);
            $this->locations[$tmp] = $node;
            $this->nodes[$node][]  = $tmp;
        }
        //对节点排序
        ksort($this->locations, SORT_NUMERIC);
        return $this;
    }

    /**
     * @param string $node
     * @return bool
     */
    public function existsNode(string $node): bool
    {
        return array_key_exists($node, $this->nodes);
    }

    /**
     * delete a entity node
     *
     * @param $node
     * @return Dht
     */
    public function deleteEntityNode($node): self
    {
        foreach ($this->nodes[$node] as $v) {
            unset($this->locations[$v]);
        }
        unset($this->nodes[$node]);
        return $this;
    }

    /**
     * @param int $num
     * @return $this
     */
    public function virtualNodeNum(int $num): self
    {
        $this->virtualNodeNum = $num;
        return $this;
    }
}
