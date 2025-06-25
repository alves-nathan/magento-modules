<?php

declare(strict_types=1);

namespace Nathan\LanguageTag\Block;

use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;
use Nathan\LanguageTag\Api\LanguageTagGeneratorInterface;
use Throwable;

class LanguageTag extends AbstractBlock
{
    /**
     * @param Context $context
     * @param Http $request
     * @param LanguageTagGeneratorInterface $linkGenerator
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly Http $request,
        private readonly LanguageTagGeneratorInterface $linkGenerator,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function toHtml(): string
    {
        try {
            if ($this->request->getFullActionName() !== 'cms_page_view') {
                return '';
            }

            $pageId = $this->getPageId();
            if (!$pageId) {
                return '';
            }

            return implode("\n", $this->linkGenerator->generateForCmsPage($pageId));
        } catch (Throwable $e) {
            $this->_logger->error('[LanguageTag] Block rendering failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * @return int|null
     */
    private function getPageId(): ?int
    {
        $pageId = $this->request->getParam('page_id') ?? $this->request->getParam('id');
        return is_numeric($pageId) ? (int) $pageId : null;
    }
}
