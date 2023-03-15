<?php

namespace System\view;

use System\view\traits\HasViewLoader;
use System\view\traits\HasExtendsContent;
use System\view\traits\HasIncludeContent;
use Exception;

class ViewBuilder
{
    use HasViewLoader, HasExtendsContent, HasIncludeContent;

    public $content;
    public array $vars = [];

    public function run($dir): void
    {
        $this->content = $this->viewLoader($dir);
        $this->checkExtendsContent();
        $this->checkIncludesContent();
        $this->checkCSRF();
        $this->checkEcho();
        $this->checkforeach();
        Composer::setViews($this->viewNameArray);
        $this->vars = Composer::getVars();
    }

    private function checkEcho(): void
    {
        preg_match_all('/{{\s*(.*?)\s*}}/', $this->content, $matches);
        foreach ($matches[0] as $key => $match) {
            $expression = $matches[1][$key];
            $this->content = str_replace($match, "<?= $expression ?>", $this->content);
        }
    }

    private function checkforeach(): void
    {
        $newHtmlContent = preg_replace('/@foreach\((.*?)\)/', '<?php foreach($1) { ?>', $this->content);
        $this->content = $newHtmlContent;
        $newHtmlContent = preg_replace('/@endforeach/', '<?php } ?>', $this->content);
        $this->content = $newHtmlContent;
    }

    private function checkCSRF(): void
    {
        $this->content = str_replace('@csrf', '{{ get_csrf_input() }}', $this->content);
    }

}
