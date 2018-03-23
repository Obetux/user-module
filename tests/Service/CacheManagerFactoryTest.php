<?php

namespace App\Tests\Service;

use App\Service\CacheManagerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Simple\NullCache;

/**
 * Class CacheManagerFactoryTest
 * @package App\Tests\Service
 */
class CacheManagerFactoryTest extends TestCase
{
    /**
     * @throws \ErrorEception
     */
    public function testCacheManagerFilesystem()
    {
        $cmf = new CacheManagerFactory('filesystem', '');
        $cache = $cmf->cacheManager('app');

        $this->assertInstanceOf(FilesystemCache::class, $cache);
    }

    /**
     * @throws \ErrorEception
     */
    public function testCacheManagerNull()
    {
        $cmf = new CacheManagerFactory('null', '');
        $cache = $cmf->cacheManager('app');

        $this->assertInstanceOf(NullCache::class, $cache);
    }
}
