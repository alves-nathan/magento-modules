<?php
namespace Nathan\LanguageTag\Api;

use Magento\Framework\Exception\LocalizedException;

interface LanguageTagGeneratorInterface
{
    /**
     * @param int $pageId
     * @return string[]
     * @throws LocalizedException
     */
    public function generateForCmsPage(int $pageId): array;
}
