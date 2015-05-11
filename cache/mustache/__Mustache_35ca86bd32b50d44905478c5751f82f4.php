<?php

class __Mustache_35ca86bd32b50d44905478c5751f82f4 extends Mustache_Template
{
    private $lambdaHelper;
    protected $strictCallables = true;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $newContext = array();

        // 'admin_mode' section
        $value = $context->find('admin_mode');
        $buffer .= $this->section8e339b8d75ed194da83be4a6a5fc35c2($context, $indent, $value);

        return $buffer;
    }

    private function section8e339b8d75ed194da83be4a6a5fc35c2(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
<div class="container-admin">
    <table class="floating-admin">
        <tr>
            <td colspan="4">
                <h4>ѕанель админа:</h4>
						</td>
        </tr>
        <tr>
            <td colspan="3">
                <span>¬ключить редактирование:</span>
            </td>
            <td>
                <div id="enable-edit" class="button_img_off"></div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span>¬ключить подсказки:</span>
            </td>
            <td>
                <div id="enable-help" class="button_img_off"></div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <a href="/admin.php?adm_out=1">выход</a>
            </td>
        </tr>
    </table>
</div>
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
                
                $buffer .= $indent . '<div class="container-admin">
';
                $buffer .= $indent . '    <table class="floating-admin">
';
                $buffer .= $indent . '        <tr>
';
                $buffer .= $indent . '            <td colspan="4">
';
                $buffer .= $indent . '                <h4>ѕанель админа:</h4>
';
                $buffer .= $indent . '						</td>
';
                $buffer .= $indent . '        </tr>
';
                $buffer .= $indent . '        <tr>
';
                $buffer .= $indent . '            <td colspan="3">
';
                $buffer .= $indent . '                <span>¬ключить редактирование:</span>
';
                $buffer .= $indent . '            </td>
';
                $buffer .= $indent . '            <td>
';
                $buffer .= $indent . '                <div id="enable-edit" class="button_img_off"></div>
';
                $buffer .= $indent . '            </td>
';
                $buffer .= $indent . '        </tr>
';
                $buffer .= $indent . '        <tr>
';
                $buffer .= $indent . '            <td colspan="3">
';
                $buffer .= $indent . '                <span>¬ключить подсказки:</span>
';
                $buffer .= $indent . '            </td>
';
                $buffer .= $indent . '            <td>
';
                $buffer .= $indent . '                <div id="enable-help" class="button_img_off"></div>
';
                $buffer .= $indent . '            </td>
';
                $buffer .= $indent . '        </tr>
';
                $buffer .= $indent . '        <tr>
';
                $buffer .= $indent . '            <td colspan="4">
';
                $buffer .= $indent . '                <a href="/admin.php?adm_out=1">выход</a>
';
                $buffer .= $indent . '            </td>
';
                $buffer .= $indent . '        </tr>
';
                $buffer .= $indent . '    </table>
';
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
