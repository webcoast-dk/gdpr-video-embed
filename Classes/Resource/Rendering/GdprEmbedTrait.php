<?php

declare(strict_types=1);

namespace WEBcoast\GdprVideoEmbed\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\View\StandaloneView;

trait GdprEmbedTrait
{
    protected function manipulateTagAttributes(string $tag, FileInterface $file)
    {
        if (preg_match('#(?<start><iframe)(?<attributes>(?: [a-z]+(?:="[^"]*")?)*)(?<end>></iframe>)#', $tag, $tagParts)) {
            $attributes = GeneralUtility::get_tag_attributes($tagParts['attributes']);
            $attributes['data-src'] = $attributes['src'] ?? null;
            $attributes['data-component'] = 'gdprVideoEmbed';
            $attributes['data-embed-type'] = $file->getExtension();
            unset($attributes['src']);

            return $tagParts['start'] . ' ' . GeneralUtility::implodeAttributes($attributes, false, true) . $tagParts['end'];
        }

        return $tag;
    }

    protected function renderOverlay(FileInterface $file): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->getRenderingContext()->setRequest(($view->getRenderingContext()->getRequest() ?? new Request())->withControllerExtensionName('GdprVideoEmbed'));
        $view->getRenderingContext()->getTemplatePaths()->fillDefaultsByPackageName('gdpr_video_embed');
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $config = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $viewConfig = $config['plugin.']['tx_gdprvideoembed.']['view.'] ?? [];
        $settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'GdprVideoEmbed');

        if (isset($viewConfig['templateRootPaths'])) {
            $view->setTemplateRootPaths($viewConfig['templateRootPaths']);
        }

        if (isset($viewConfig['layoutRootPaths'])) {
            $view->setLayoutRootPaths($viewConfig['layoutRootPaths']);
        }

        if (isset($viewConfig['partialRootPaths'])) {
            $view->setPartialRootPaths($viewConfig['partialRootPaths']);
        }

        $view->assign('file', $file);
        $view->assign('settings', $settings);

        return $view->render('Overlay');
    }
}
