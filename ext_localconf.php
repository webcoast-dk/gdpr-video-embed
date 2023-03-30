<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::class)->registerRendererClass(\WEBcoast\GdprVideoEmbed\Resource\Rendering\YoutubeGdprRenderer::class);
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::class)->registerRendererClass(\WEBcoast\GdprVideoEmbed\Resource\Rendering\VimeoGdprRenderer::class);
