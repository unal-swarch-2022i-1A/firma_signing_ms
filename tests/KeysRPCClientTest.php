<?php declare(strict_types=1);
require dirname(__FILE__).'./../src/KeysRPCClient.php';
use PHPUnit\Framework\TestCase;
use App\KeysRPCClient as KeysRPCClient;

final class KeysRPCClientTest extends TestCase
{
    public function testInstance(): void
    {
        $this->assertInstanceOf(
            KeysRPCClient::class,
            $KeysRPCClient = new KeysRPCClient()
        );
    }    

    public function testGetPrivateKey(): void 
    {
        $KeysRPCClient = new KeysRPCClient();
        $response = $KeysRPCClient->run("private",1);            
        echo "Response: ".$response . PHP_EOL;
        $this->assertNotNull( 
            $response, 
            "response is null or not"
        );         
        echo "F1". PHP_EOL;
    }    

    public function testGenerateKeys(): void 
    {
        $KeysRPCClient = new KeysRPCClient();
        $response = $KeysRPCClient->run("generate",1);            
        echo "Response: ".$response . PHP_EOL;
        $this->assertNotNull( 
            $response, 
            "response is null or not"
        );                 
    }


}    
