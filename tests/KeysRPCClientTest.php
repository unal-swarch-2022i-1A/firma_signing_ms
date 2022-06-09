<?php declare(strict_types=1);
require dirname(__FILE__).'./../src/KeysRPCClient.php';
use PHPUnit\Framework\TestCase;
use App\KeysRPCClient as KeysRPCClient;

final class KeysRPCClientTest extends TestCase
{
    public function testPushAndPop(): void
    {
        $this->assertInstanceOf(
            KeysRPCClient::class,
            $KeysRPCClient = new KeysRPCClient()
        );
    }
}