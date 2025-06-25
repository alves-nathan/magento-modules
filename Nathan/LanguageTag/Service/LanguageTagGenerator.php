<?php

declare(strict_types=1);

namespace Nathan\LanguageTag\Service;

use Exception;
use Magento\Cms\Api\GetPageByIdentifierInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Nathan\LanguageTag\Api\LanguageTagGeneratorInterface;
use Psr\Log\LoggerInterface;

readonly class LanguageTagGenerator implements LanguageTagGeneratorInterface
{
    /**
     * @param StoreManagerInterface $storeManager
     * @param PageRepositoryInterface $pageRepository
     * @param UrlFinderInterface $urlFinder
     * @param LoggerInterface $logger
     * @param PageResource $pageResource
     * @param GetPageByIdentifierInterface $getByIdentifier
     */
    public function __construct(
        private StoreManagerInterface $storeManager,
        private PageRepositoryInterface $pageRepository,
        private UrlFinderInterface $urlFinder,
        private LoggerInterface $logger,
        private PageResource $pageResource,
        private GetPageByIdentifierInterface $getByIdentifier
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function generateForCmsPage(int $pageId): array
    {
        $links = [];
        $page = $this->pageRepository->getById($pageId);
        $identifier = $page->getIdentifier();

        foreach ($this->storeManager->getStores() as $store) {
            try {
                $storeId = (int) $store->getId();
                $storePage = $this->getByIdentifier->execute($identifier, $storeId);
                if (!$storePage->isActive()) {
                    continue;
                }

                $urlRewrite = $this->urlFinder->findOneByData([
                    UrlRewrite::ENTITY_ID => $storePage->getId(),
                    UrlRewrite::ENTITY_TYPE => 'cms-page',
                    UrlRewrite::STORE_ID => $storeId,
                ]);
                if (!$urlRewrite) {
                    continue;
                }

                $baseUrl = $this->storeManager
                    ->getStore($storeId)
                    ->getBaseUrl();

                $href = $baseUrl . ltrim($urlRewrite->getRequestPath(), '/');

                $lang = str_replace('_', '-', strtolower($store->getCode()));
                $links[] = sprintf(
                    '<link rel="alternate" hreflang="%s" href="%s"/>',
                    htmlspecialchars($lang, ENT_QUOTES),
                    htmlspecialchars($href, ENT_QUOTES)
                );
            } catch (Exception $e) {
                $this->logger->warning("[LanguageTagGenerator]: " . $e->getMessage());
            }
        }

        return $links;
    }
}
