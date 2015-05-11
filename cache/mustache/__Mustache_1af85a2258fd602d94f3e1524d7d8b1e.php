<?php

class __Mustache_1af85a2258fd602d94f3e1524d7d8b1e extends Mustache_Template
{
    private $lambdaHelper;
    protected $strictCallables = true;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!DOCTYPE html>
';
        $buffer .= $indent . '<html lang="ru" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
';
        $buffer .= $indent . '<head>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <title>';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '</title>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
';
        $buffer .= $indent . '    <meta http-equiv="content-language" content="ru" />
';
        $buffer .= $indent . '    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
';
        $buffer .= $indent . '    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
';
        $buffer .= $indent . '    <meta name="apple-mobile-web-app-capable" content="yes" />
';
        $buffer .= $indent . '    <meta name="author" lang="ru" content="Алексеева Анна | фотограф в Одессе" />
';
        $buffer .= $indent . '    <meta name="keywords" content="';
        $value = $this->resolveValue($context->find('keywords'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" />
';
        $buffer .= $indent . '    <meta name="description" content="';
        $value = $this->resolveValue($context->find('description'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= '" />
';
        $buffer .= $indent . '    <meta name="robots" content="all" />
';
        $buffer .= $indent . '    <meta name="revisit" content="7" />
';
        $buffer .= $indent . '    <link rel="shortcut icon" href="/images/favicon.png" type="image/png" />
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
';
        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->findInBlock('stylesheets'), $context, $indent);
        if ($value && !is_array($value) && !is_object($value)) {
            $buffer .= $value;
        } else {
            
                $buffer .= $indent . '
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <!--[if IE]>
';
        $buffer .= $indent . '	<link rel="stylesheet" type="text/css" media="screen" href="/css/ie.css" />
';
        $buffer .= $indent . '	<script src="/js/if_IE/html5.js"></script>
';
        $buffer .= $indent . '    <![endif]-->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/native/jquery-2.1.1.min.js\'></script>
';
        $buffer .= $indent . '    <!--<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>-->
';
        $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/jquery.easing.1.3.js\'></script>
';
        $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/native/jquery.mb.browser.min.js\'></script>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/functions.js\'></script>
';
        $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/web/who.is.online.js\'></script>
';
        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->findInBlock('scripts_head'), $context, $indent);
        if ($value && !is_array($value) && !is_object($value)) {
            $buffer .= $value;
        } else {
            
                $buffer .= $indent . '
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        if ($partial = $this->mustache->loadPartial('admin_panel')) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!--================================ header ===================================-->
';
        if ($partial = $this->mustache->loadPartial('global_menu')) {
            $buffer .= $partial->renderInternal($context);
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div id="wrapper-content">
';
        $buffer .= $indent . '    <div id="top-inset"></div>
';
        $buffer .= $indent . '    <!--[if IE 9]>
';
        $buffer .= $indent . '	<div class="alert alert-danger">
';
        $buffer .= $indent . '		<noindex>Вы используете устаревший браузер! Для комфортной работы рекомендуем установить новый
';
        $buffer .= $indent . '			<b>Firefox</b> или <b>Chrome</b> или <b>Internet Explorer</b> или <b>Opera 10</b>!
';
        $buffer .= $indent . '		</noindex>
';
        $buffer .= $indent . '	</div>
';
        $buffer .= $indent . '    <![endif]-->
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <noindex>
';
        $buffer .= $indent . '        <noscript>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '            <div class="alert alert-danger">
';
        $buffer .= $indent . '                <strong>В Вашем браузере отключен JavaScript.</strong><br> Для полноценной работы сайта (просмотра галереи фотографий) необходимо,
';
        $buffer .= $indent . '                <br>чтобы JavaScript был включен. Как включить — смотрите
';
        $buffer .= $indent . '                <a href="http://www.google.ru/support/adsense/bin/answer.py?answer=12654" target="_blank">здесь</a>.
';
        $buffer .= $indent . '                <br>После того, как Вы включите JavaScript, перезагрузите страницу (F5).
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </noscript>
';
        $buffer .= $indent . '    </noindex>
';
        $buffer .= $indent . '    <!--============================== end header =================================-->
';
        $buffer .= $indent . '    <!--=============================== content ===================================-->
';
        $buffer .= $indent . '    <div id="main" class="clearfix">
';
        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->findInBlock('content'), $context, $indent);
        if ($value && !is_array($value) && !is_object($value)) {
            $buffer .= $value;
        } else {
            
                $buffer .= $indent . '
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <!--============================= end content =================================-->
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<div class="clear"></div>
';
        $buffer .= $indent . '<div id="bottom-inset"></div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<footer>
';
        $buffer .= $indent . '    <!--=============================== соцсети ===================================-->
';
        $buffer .= $indent . '    <div class=\'social\'>
';
        $buffer .= $indent . '        <div class="h-mod">
';
        $buffer .= $indent . '						<div class="bb-img-red">
';
        $buffer .= $indent . '                <h3>Поделиться с друзьями:</h3>
';
        $buffer .= $indent . '						</div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '        <div class="share42init"></div>
';
        if ($partial = $this->mustache->loadPartial('online_widget')) {
            $buffer .= $partial->renderInternal($context, $indent . '			');
        }
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <!-- ============================== копирайт ==================================-->
';
        $buffer .= $indent . '    <div class=\'container\'>
';
        // 'debug_mode' inverted section
        $value = $context->find('debug_mode');
        if (empty($value)) {
            
            $buffer .= $indent . '
';
            if ($partial = $this->mustache->loadPartial('inc_foter_google')) {
                $buffer .= $partial->renderInternal($context, $indent . '				');
            }
            $buffer .= $indent . '
';
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '        <span class="copirait"><strong>© ';
        $value = $this->resolveValue($context->find('auto_copyright'), $context, $indent);
        $buffer .= call_user_func($this->mustache->getEscape(), $value);
        $buffer .= ' Алексеева Анна </strong></span><br>
';
        $buffer .= $indent . '        <span> cтудия&nbsp;&nbsp; <a target=\'_blank\' href=\'http://www.aleks.od.ua\' class=\'link-2\'>Creative ls</a></span>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '</footer>
';
        $buffer .= $indent . '
';
        $value = $this->resolveValue($context->findInBlock('scripts_footer'), $context, $indent);
        if ($value && !is_array($value) && !is_object($value)) {
            $buffer .= $value;
        } else {
            
                $buffer .= $indent . '
';
        }
        $buffer .= $indent . '
';
        // 'admin_mode' section
        $value = $context->find('admin_mode');
        $buffer .= $this->section443f8bdb64b789e1723be10a394be9b0($context, $indent, $value);
        $buffer .= $indent . '
';
        $buffer .= $indent . '<script type=\'text/javascript\' src=\'/js/menu.js\'></script>
';
        $buffer .= $indent . '<script type=\'text/javascript\' src=\'/js/share42/share42.js\'></script>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>';

        return $buffer;
    }

    private function section443f8bdb64b789e1723be10a394be9b0(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
    <script type=\'text/javascript\' src=\'/js/jquery-ui.min.js\'></script>
    <!-- <script type=\'text/javascript\' src=\'/js/jeditable/jquery.jeditable.js\'></script> -->
    <!--<script src=\'/js/native/alloy-3.0.1/build/aui/aui-min.js\'></script> -->
    <script src=\'/js/jqueryui-editable/js/jqueryui-editable.js\'></script>
    <script type=\'text/javascript\' src=\'/js/web/admin/ajax_title_edit.js\'></script>
    <script type=\'text/javascript\' src=\'/js/web/admin/admin.js\'></script>
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
                
                $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/jquery-ui.min.js\'></script>
';
                $buffer .= $indent . '    <!-- <script type=\'text/javascript\' src=\'/js/jeditable/jquery.jeditable.js\'></script> -->
';
                $buffer .= $indent . '    <!--<script src=\'/js/native/alloy-3.0.1/build/aui/aui-min.js\'></script> -->
';
                $buffer .= $indent . '    <script src=\'/js/jqueryui-editable/js/jqueryui-editable.js\'></script>
';
                $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/web/admin/ajax_title_edit.js\'></script>
';
                $buffer .= $indent . '    <script type=\'text/javascript\' src=\'/js/web/admin/admin.js\'></script>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
