<?php

declare(strict_types=1);

namespace WEBcoast\GdprVideoEmbed\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer;

class VimeoGdprRenderer extends VimeoRenderer
{
    use GdprEmbedTrait;

    public function getPriority()
    {
        return 2;
    }

    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false): string
    {
        $iframe = parent::render($file, $width, $height, $options, $usedPathsRelativeToCurrentScript);
        $alteredIframe = $this->manipulateTagAttributes($iframe, $file);
        if ($iframe !== $alteredIframe) {
            return $alteredIframe . $this->renderOverlay($file);
        }

        return $iframe;
    }
}
