<?php

class __Mustache_43984bf10b316a2fd000e214ca0d9059 extends Mustache_Template
{
    private $lambdaHelper;
    protected $strictCallables = true;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<article>
';
        $buffer .= $indent . '    <div id="header">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <nav class="centered-navigation">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="centered-navigation-wrapper">
';
        $buffer .= $indent . '                <a href="/index.php" class="mobile-logo">
';
        $buffer .= $indent . '                    <img src="/images/mobile-logo.png" alt="mobile-logo">
';
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '                <a href="" class="centered-navigation-menu-button">
';
        $buffer .= $indent . '                    <span class="mobile-menu">MENU</span>
';
        $buffer .= $indent . '                </a>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '                  <header>
';
        $buffer .= $indent . '                      <ul class="centered-navigation-menu"
';
        $buffer .= $indent . '                              >
';
        $buffer .= $indent . '                          <li class="nav-link"
';
        $buffer .= $indent . '																	><h1>Алексеева Анна<span id="profession">фотограф в Одессе</span></h1><div class="line-head"></div></li
';
        $buffer .= $indent . '                                  >
';
        // 'razdel' section
        $value = $context->find('razdel');
        $buffer .= $this->sectionBe01f5b77f5aefb111724663404e6c8a($context, $indent, $value);
        $buffer .= $indent . '                      </ul>
';
        $buffer .= $indent . '                  </header>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </nav>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</article>';

        return $buffer;
    }

    private function sectionBe01f5b77f5aefb111724663404e6c8a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
                          <li class="nav-link {{current}}"
																	><a href="{{global_menu_href}}">{{{global_menu_name}}}</a></li
                                  >
							';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '                          <li class="nav-link ';
                $value = $this->resolveValue($context->find('current'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '"
';
                $buffer .= $indent . '																	><a href="';
                $value = $this->resolveValue($context->find('global_menu_href'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">';
                $value = $this->resolveValue($context->find('global_menu_name'), $context, $indent);
                $buffer .= $value;
                $buffer .= '</a></li
';
                $buffer .= $indent . '                                  >
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
