<?php

declare(strict_types=1);

namespace Yiranzai\Dht;

/**
 * Class HashTest
 * @package Yiranzai\Dht
 */
class HashTest extends \PHPUnit\Framework\TestCase
{

    public function testCache()
    {
        $dht = new Dht();

        $dht->addEntityNode('db_server_one')->addEntityNode('db_server_two');
        $resOne = $dht->getLocation('key_one');
        $resTwo = $dht->getLocation('key_one');
        $this->assertSame($resOne, $resTwo);
        $this->assertSame(json_encode($dht), $dht->toJson());
        $this->assertSame(json_encode($dht->toArray()), $dht->toJson());
        $this->assertSame($dht->toArray(), json_decode($dht->toJson(), true));
    }

    public function testLocationException()
    {
        $this->expectException(\Exception::class);
        $dht = new Dht();
        $this->assertFalse($dht->getLocation('test'));
    }

    public function testAlgo()
    {
        $dht = new Dht();
        $this->assertSame(
            (int)sprintf('%u', hash('sha256', 'algo_test')),
            $dht->algo('sha256')->hashGenerate('algo_test')
        );
    }

    public function testLinkMethod()
    {
        $dht  = new Dht();
        $data = $dht->virtualNodeNum(3)->addEntityNode('db_server_one')->toArray();
        $this->assertSame(3, $data['virtualNodeNum']);
        $this->assertCount(3, $data['locations']);
    }

    public function testConfig()
    {
        $dhtOne = new Dht([
        'virtualNodeNum' => 3,
        'algo'           => 'sha256',
        ]);
        $dhtOne->addEntityNode('db_server_one');
        $dataOne = json_decode($dhtOne->toJson(), true);
        $dhtTwo  = new Dht();
        $dataTwo = $dhtTwo->algo('sha256')->virtualNodeNum(3)->addEntityNode('db_server_one')->toArray();
        $this->assertSame($dataOne['virtualNodeNum'], $dataTwo['virtualNodeNum']);
        $this->assertSame($dataOne['locations'], $dataTwo['locations']);
    }

    public function testDeleteNode()
    {
        $dhtOne = new Dht();
        $dhtTwo = new Dht();
        $this->assertTrue($dhtOne->addEntityNode('db_server_one')
            ->addEntityNode('db_server_two')->existsNode('db_server_two'));
        $this->assertFalse($dhtTwo->addEntityNode('db_server_one')
            ->addEntityNode('db_server_two')->deleteEntityNode('db_server_two')->existsNode('db_server_two'));
    }

    /**
     * Test that true does in fact equal true
     */
    public function testNode()
    {
        $dht = new Dht();
        $this->assertTrue($dht->addEntityNode('db_server_one')->existsNode('db_server_one'));
    }


    /**
     * test same node exception
     */
    public function testSameNodeException()
    {
        $this->expectException(\Exception::class);
        $dht = new Dht();
        $dht->addEntityNode('db_server_one')->addEntityNode('db_server_one');
    }
}
