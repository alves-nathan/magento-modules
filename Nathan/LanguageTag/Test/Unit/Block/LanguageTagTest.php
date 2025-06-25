<?php

declare(strict_types=1);

namespace Nathan\LanguageTag\Test\Unit\Block;

use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Context;
use Nathan\LanguageTag\Block\LanguageTag;
use Nathan\LanguageTag\Api\LanguageTagGeneratorInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class LanguageTagTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function testToHtmlReturnsEmptyWhenNotCmsPageView(): void
    {
        $request = $this->createMock(Http::class);
        $request->method('getFullActionName')->willReturn('catalog_product_view');
        $linkGenerator = $this->createMock(LanguageTagGeneratorInterface::class);
        $context = $this->createMock(Context::class);
        $block = new LanguageTag($context, $request, $linkGenerator);

        $this->assertSame('', $block->toHtml());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testToHtmlReturnsEmptyWhenPageIdIsMissing(): void
    {
        $request = $this->createMock(Http::class);
        $request->method('getFullActionName')->willReturn('cms_page_view');
        $request->method('getParam')->willReturn(null);

        $linkGenerator = $this->createMock(LanguageTagGeneratorInterface::class);
        $context = $this->createMock(Context::class);
        $block = new LanguageTag($context, $request, $linkGenerator);

        $this->assertSame('', $block->toHtml());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testToHtmlReturnsGeneratedLinks(): void
    {
        $request = $this->createMock(Http::class);
        $request->method('getFullActionName')->willReturn('cms_page_view');
        $request->method('getParam')->willReturn(123);

        $linkGenerator = $this->createMock(LanguageTagGeneratorInterface::class);
        $linkGenerator->method('generateForCmsPage')->with(123)->willReturn([
            '<link rel="alternate" hreflang="en-us" href="https://example.com/en/page"/>',
        ]);

        $context = $this->createMock(Context::class);
        $block = new LanguageTag($context, $request, $linkGenerator);

        $this->assertStringContainsString('hreflang="en-us"', $block->toHtml());
    }
}
