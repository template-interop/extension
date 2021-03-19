<?php

namespace Interop\Template\Extension;

use Interop\Template\TemplateEngineInterface;

interface ExtensionInterface
{
    /**
     * @param string $templateName
     * @param array $vars
     * @param TemplateEngineInterface $next
     * @return string
     */
    public function render(string $templateName, array $vars, TemplateEngineInterface $next) : string;
}
