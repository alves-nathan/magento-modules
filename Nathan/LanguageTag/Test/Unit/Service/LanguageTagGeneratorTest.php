<?php

declare(strict_types=1);

namespace Nathan\LanguageTag\Test\Unit\Service;

use Magento\Cms\Api\GetPageByIdentifierInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Nathan\LanguageTag\Service\LanguageTagGenerator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LanguageTagGeneratorTest extends TestCase
{
    /**
     * @return void
     * @throws LocalizedException
     * @throws Exception
     */
    public function testGenerateForCmsPageReturnsHreflangs(): void
    {
        $store = $this->createMock(Store::class);
        $store->method('getId')->willReturn(1);
        $store->method('getCode')->willReturn('en_us');
        $store->method('getBaseUrl')->willReturn('https://example.com/');

        $storeManager = $this->createMock(StoreManagerInterface::class);
        $storeManager->method('getStores')->willReturn([$store]);
        $storeManager->method('getStore')->with(1)->willReturn($store);

        $page = $this->createConfiguredMock(Page::class, [
            'getIdentifier' => 'about-us'
        ]);
        $storePage = $this->createConfiguredMock(Page::class, [
            'getId' => 123,
            'isActive' => true
        ]);

        $pageRepository = $this->createMock(PageRepositoryInterface::class);
        $pageRepository->method('getById')->willReturn($page);

        $getByIdentifier = $this->createMock(GetPageByIdentifierInterface::class);
        $getByIdentifier->method('execute')->willReturn($storePage);

        $urlRewrite = $this->createConfiguredMock(UrlRewrite::class, [
            'getRequestPath' => 'about-us'
        ]);

        $urlFinder = $this->createMock(UrlFinderInterface::class);
        $urlFinder->method('findOneByData')->willReturn($urlRewrite);

        $logger = $this->createMock(LoggerInterface::class);

        $pageResource = $this->createMock(PageResource::class);

        $generator = new LanguageTagGenerator(
            $storeManager,
            $pageRepository,
            $urlFinder,
            $logger,
            $pageResource,
            $getByIdentifier
        );

        $result = $generator->generateForCmsPage(123);

        $this->assertNotEmpty($result);
        $this->assertStringContainsString('hreflang="en-us"', $result[0]);
        $this->assertStringContainsString('https://example.com/about-us', $result[0]);
    }
}
