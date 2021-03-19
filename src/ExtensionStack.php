<?php

namespace Interop\Template\Extension;

use Interop\Template\TemplateEngineInterface;

final class ExtensionStack implements ExtensionInterface
{
    /** @var ExtensionInterface[] */
    private array $extensions;

    public function __construct(ExtensionInterface ...$extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @param string            $templateName
     * @param array             $vars
     * @param TemplateEngineInterface $next
     * @return string
     */
    public function render(string $templateName, array $vars, TemplateEngineInterface $next) : string
    {
        $renderer = $next;

        // Build execution stack
        $extensions = $this->extensions;
        while ($extension = array_pop($extensions)) {
            $renderer = new class($renderer, $extension) implements TemplateEngineInterface
            {
                private TemplateEngineInterface $renderer;
                private ExtensionInterface $extension;
                public function __construct(TemplateEngineInterface $renderer, ExtensionInterface $extension)
                {
                    $this->renderer = $renderer;
                    $this->extension = $extension;
                }
                public function render(string $templateName, array $parameters = []): string
                {
                    return $this->extension->render($templateName, $parameters, $this->renderer);
                }
            };
        }

        return $renderer->render($templateName, $vars);
    }
}
