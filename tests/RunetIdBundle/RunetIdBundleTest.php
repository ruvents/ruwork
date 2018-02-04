<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle;

use RunetId\Client\RunetIdClient;
use Ruwork\BundleTest\AbstractBundleTestCase;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class RunetIdBundleTest extends AbstractBundleTestCase
{
    public function testServices(): void
    {
        $this->loadBundleExtension([
            'key' => 'key',
            'secret' => 'secret',
        ]);
        $this->exposeService('ruwork_runet_id.client.default');
        $this->exposeService('ruwork_runet_id.client_container');
        $this->exposeService(RunetIdClient::class);
        $this->compile();

        $client = $this->container->get('ruwork_runet_id.client.default');
        $clientContainer = $this->container->get('ruwork_runet_id.client_container');

        $this->assertInstanceOf(RunetIdClient::class, $client);

        $this->assertSame($client, $this->container->get(RunetIdClient::class));
        $this->assertTrue($clientContainer->has('default'));
        $this->assertFalse($clientContainer->has('some'));
        $this->assertSame($client, $clientContainer->get('default'));
    }

    protected function getBundle(): BundleInterface
    {
        return new RuworkRunetIdBundle();
    }
}
