<?php

declare(strict_types=1);

namespace Nathan\ButtonColor\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ButtonColor extends Template
{
    private const CONFIG_PATH = 'design/head/includes';

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        private ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getCss(): string
    {
        $config = $this->scopeConfig->getValue(self::CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        if (!$config || !preg_match('/#([a-f0-9]{6})/i', $config, $matches)) {
            return '';
        }

        $color = $matches[0];
        return <<<CSS
<style>
:root {
    --button-background: {$color};
}
button,
.button,
button.action,
.action.primary {
    background-color: var(--button-background, #000000) !important;
    border-color: var(--button-background, #000000) !important;
}
</style>
CSS;
    }
}
