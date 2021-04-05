<?php


namespace Tests\Feature\Providers;


trait InvalidUuidProvider
{
    public function invalidUuidProvider(): array
    {
        return [
            [' '],
            ['{}'],
            ['not an uuid'],
            ['710a2c6a-bca2-42cb-8d84-partial'],
            ['710a2c6a-bca2-42cb-8d84-f77039a43416-trailing']
        ];
    }
}
