<?php

class __Mustache_44f9d5a5dae08fd0a6cebe5fa87d5897 extends Mustache_Template
{
    private $lambdaHelper;
    protected $strictCallables = true;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $newContext = array();

        
            // 'content' block_arg
            $value = $this->section9a408f58ed841978b56edb5a4a611317($context, '', true);
            $newContext['content'] = $indent . $value;
            // 'scripts_footer' block_arg
            $value = $this->section39f9bb25e54dcf1855abe7096c05efad($context, '', true);
            $newContext['scripts_footer'] = $indent . $value;
        
        if ($parent = $this->mustache->LoadPartial('base')) {
            $context->pushBlockContext($newContext);
            $buffer .= $parent->renderInternal($context, $indent);
            $context->popBlockContext();
        }

        return $buffer;
    }

    private function sectionF9dc820c3449fac567debd40c53e3acd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
                              <div class=\'item\'>
                                  <img src=\'/{{{img_src_head_index_slide}}}\' alt=\'свадебный фотограф Алексеева Анна, свадебные фотосессии в Одессе\'>
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
                
                $buffer .= $indent . '                              <div class=\'item\'>
';
                $buffer .= $indent . '                                  <img src=\'/';
                $value = $this->resolveValue($context->find('img_src_head_index_slide'), $context, $indent);
                $buffer .= $value;
                $buffer .= '\' alt=\'свадебный фотограф Алексеева Анна, свадебные фотосессии в Одессе\'>
';
                $buffer .= $indent . '                              </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionF771d89a58e3dad39f277da4583c64ba(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
                                  <div class=\'owl-dot\'><strong>0</strong>{{i}}</div>
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
                
                $buffer .= $indent . '                                  <div class=\'owl-dot\'><strong>0</strong>';
                $value = $this->resolveValue($context->find('i'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section394da235695684e969f2219c8131b65d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = ' title="Двойной щелчок для редактирования"';
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
                
                $buffer .= ' title="Двойной щелчок для редактирования"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section78f3f3d7a805ae4f506e33faec641ab3(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
                          <div class="actions">
                              <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
                              <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
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
                
                $buffer .= $indent . '                          <div class="actions">
';
                $buffer .= $indent . '                              <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
';
                $buffer .= $indent . '                              <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
';
                $buffer .= $indent . '                          </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section537df280a48e315e4d2e30abd45af94e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
                    <li id="head-{{id_title}}">
                        <a href="#tab-{{id_title}}" class="navlink"
													{{#admin_mode}} title="Двойной щелчок для редактирования"{{/admin_mode}}>{{name_title}}</a>
											{{#admin_mode}}
                          <div class="actions">
                              <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
                              <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
                          </div>
											{{/admin_mode}}
                    </li>
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
                
                $buffer .= $indent . '                    <li id="head-';
                $value = $this->resolveValue($context->find('id_title'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '">
';
                $buffer .= $indent . '                        <a href="#tab-';
                $value = $this->resolveValue($context->find('id_title'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '" class="navlink"
';
                $buffer .= $indent . '													';
                // 'admin_mode' section
                $value = $context->find('admin_mode');
                $buffer .= $this->section394da235695684e969f2219c8131b65d($context, $indent, $value);
                $buffer .= '>';
                $value = $this->resolveValue($context->find('name_title'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '</a>
';
                // 'admin_mode' section
                $value = $context->find('admin_mode');
                $buffer .= $this->section78f3f3d7a805ae4f506e33faec641ab3($context, $indent, $value);
                $buffer .= $indent . '                    </li>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionC625772ea9a4f43938c1f55590f437f1(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '

									<div id="addButton" class="button_img"><span>Добавить раздел</span></div>
                  <div id="dialog-confirm" title="Удалить запись?">
                      <span>Запись будет удалена из базы данных!</span>
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
                
                $buffer .= $indent . '
';
                $buffer .= $indent . '									<div id="addButton" class="button_img"><span>Добавить раздел</span></div>
';
                $buffer .= $indent . '                  <div id="dialog-confirm" title="Удалить запись?">
';
                $buffer .= $indent . '                      <span>Запись будет удалена из базы данных!</span>
';
                $buffer .= $indent . '                  </div>
';
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section06df9401443ff064c79e6c8b683b1988(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
                                      <div class="actions">
                                          <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
                                          <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
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
                
                $buffer .= $indent . '                                      <div class="actions">
';
                $buffer .= $indent . '                                          <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
';
                $buffer .= $indent . '                                          <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
';
                $buffer .= $indent . '                                      </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9a408f58ed841978b56edb5a4a611317(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '

<section>
    <div class="tabs clearfix">
        <table class="text-head-laitbox">
            <tbody>
            <tr>
                <td class="header" style="text-align: right;">
                    <span class="phone"><a onclick="goog_report_conversion(\'tel: 067-76-84-086\')" href="#">(067)-76-84-086</a></span><br>
                    <a href="http://{{HTTP_HOST}}/portfolio.php#tab-1">свадебная фотосъёмка</a>,
                    <a href="http://{{HTTP_HOST}}/portfolio.php#tab-3">студийные фотосессии</a>, Одесса, Алексеева Анна
                </td>
            </tr>
            <tr>
                <td>
                    <div id="slide">
                        <div id="owl-head" class="owl-carousel owl-theme">
													{{#items}}
                              <div class=\'item\'>
                                  <img src=\'/{{{img_src_head_index_slide}}}\' alt=\'свадебный фотограф Алексеева Анна, свадебные фотосессии в Одессе\'>
                              </div>
													{{/items}}
                        </div>

                        <div class=\'owl-head-pags-hide\'>
                            <div class=\'owl-head-pags\'>
															{{#pags}}
                                  <div class=\'owl-dot\'><strong>0</strong>{{i}}</div>
															{{/pags}}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>


        <section>
            <div class="col-1">

                <div class="h-mod">
                    <h2>Главная:</h2>
                </div>

                <ul class="list-title">
                    {{#li_title}}
                    <li id="head-{{id_title}}">
                        <a href="#tab-{{id_title}}" class="navlink"
													{{#admin_mode}} title="Двойной щелчок для редактирования"{{/admin_mode}}>{{name_title}}</a>
											{{#admin_mode}}
                          <div class="actions">
                              <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
                              <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
                          </div>
											{{/admin_mode}}
                    </li>
										{{/li_title}}
                </ul>
							{{#admin_mode}}

									<div id="addButton" class="button_img"><span>Добавить раздел</span></div>
                  <div id="dialog-confirm" title="Удалить запись?">
                      <span>Запись будет удалена из базы данных!</span>
                  </div>

							{{/admin_mode}}
							{{{EditTitle}}}

            </div>
        </section>

        <div class="col-2">
            <div class="h-mod">
                <div class="bb-img-red">
                <h3>Коротко о разделе:</h3>
                    </div>
            </div>
            <div id="pageContent" class="block_wrapper_body">


                <ul>
                    <li class="block_body">

                        <ul class="block_wrap block_rounded">
                            <li>
                                <h4>О свадьбах</h4>
                            </li>
                            <li>
                                <div class="focal-point border">
                                    <div><img src="/files/slides/slide-3.jpg" alt=""></div>
                                </div>
                            </li>
                            <li>
                                <p id="tab-1-1" class="edit-txt">Меня зовут Алексеева Анна, я профессиональный фотограф, живу и работаю в Одессе. Я специализируюсь на проведении свадебных фотосъёмок в Одессе, а в качестве свадебного фотографа работаю уже более 10 лет. Провожу также уличные и студийные персональные, семейные, детские фотосессии, рекламные и интерьерные фотосъёмки. Преимущественно снимаю в Одессе, но выезжаю и по Украине.
                                    Для уточнения стоимости фотосессий и заказа фотосъёмки звоните 067-76-84-086. С радостью отвечу на все ваши вопросы, проконсультирую по планированию и организации фотосъёмок, местам для проведения уличных и свадебных фотосессий, планированию свадебного дня и свадебной фотосессии, выбору фотостудии для студийных фотосессий.
                                </p>
                            </li>
                            <li class="bb1 mbm">
                            </li>
                            <li>
                                <a data-hover="aleks.od.ua" class="link-2" href="http://www.aleks.od.ua">aleks.od.ua</a>
                            </li>
                        </ul>
                    </li>
                    <li class="block_body">
                        <ul>
                            <li>
                                <div class="focal-point border">
																	{{#admin_mode}}
                                      <div class="actions">
                                          <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
                                          <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
                                      </div>
																	{{/admin_mode}}
                                    <div><img src="/files/slides/slide-2.jpg" alt=""></div>
                                </div>
                            </li>


                            <li>
                                <div class="focal-point border">
																	{{#admin_mode}}
                                      <div class="actions">
                                          <a href="#" title="Изменить или добавить текст меню." class="edit">Edit</a>
                                          <a href="#" title="Удалить раздел из базы данных без возможности восстановления." class="delete">Delete</a>
                                      </div>
																	{{/admin_mode}}
                                    <div><img src="/files/portfolio/06_Портреты/002.jpg" alt=""></div>
                                </div>
                            </li>


                            <li>
                                <div class="focal-point border">
                                    <div><img src="files/slides/slide-3.jpg" alt=""></div>
                                </div>
                            </li>
                            <li>
                                <div class="focal-point border">
                                    <div><img src="files/slides/slide-3.jpg" alt=""></div>
                                </div>
                            </li>
                            <li>
                                <div class="focal-point border">
                                    <div><img src="files/slides/slide-2.jpg" alt=""></div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="block_body">
                        <ul class="block_wrap block_rounded">
                            <li><h4>О свадьбах</h4></li>
                            <li>
                                <div class="focal-point border">
                                    <div><img src="files/slides/slide-1.jpg" alt=""></div>
                                </div>
                            </li>
                            <li>
                                <p id="tab-1-2" class="edit-txt">Уникальность свадьбы в том, что это самый первый памятный и волнующий праздник новой молодой семьи. Незабываемое событие для возлюбленной пары. Которое в полном смысле этого слова старается сделать незабываемым свадебный фотограф, чтобы сохранить воспоминания об этом празднике на живых и ярких фотографиях, снятых профессионально и с любовью к своему делу.

                                    Для меня, как свадебного фотографа, снимать свадьбу – это большая ответственность, которую мне доверили молодожены. Во время фотосессии свадьбы я использую как постановочную так и репортажную съемку.</p>
                                <br /></li>
                            <li><a data-hover="aleks.od.ua"  class="link-2" href="http://www.aleks.od.ua">aleks.od.ua</a></li>
                        </ul>
                    </li>
                </ul>


                <div id="tab-1" class="tab-content">

                    <p id="tab-1-1" class="edit-txt bb1">Уникальность свадьбы в том, что это самый первый памятный и волнующий праздник
                        новой молодой семьи. Незабываемое событие для возлюбленной пары. Которое в полном смысле
                        этого слова старается сделать незабываемым свадебный фотограф, чтобы сохранить воспоминания
                        об этом празднике на живых и ярких фотографиях, снятых профессионально и с любовью к своему
                        делу. Для меня, как свадебного фотографа, снимать свадьбу – это большая ответственность,
                        которую мне доверили молодожены. Во время фотосессии свадьбы я использую как постановочную
                        так и репортажную съемку.</p>

                </div>
                <div id="tab-2" class="tab-content">

                    <p id="tab-2-1" class="edit-txt bb1">Фотокниги это современное, новое и интересное направление в оформлении альбомов. Я делаю
                        дизайн и осуществляю печать различных форматов фотокниг: cвадебные, школьные (выпускники),
                        детские(роддом, крестины, дни рождения), семейные(отдых,путешествия) и др. В отличие от
                        обычного фотоальбома, фотокнига выглядит более привлекательно и при надлежашем хранении
                        намного больше сохраняется и будет еще долгие годы радовать Вас и Ваших друзей приятными
                        воспоминаниями, запечатленными на её страницах. Вы можете заказать у меня изготовление
                        фотокниги и из своих домашних фотографий. Вам для этого необходимо лишь предоставить мне их
                        в цифровом виде или в виде фотографий. </p>

                </div>
                <div id="tab-3" class="tab-content">

                    <p id="tab-3-1" class="edit-txt bb1">Репортажная съемка с банкетов, свадеб и корпоративов. Обработанные фотографии обычно выдаются
                        на DVD.
                        Также возможно изготовление слайд - шоу из выбранных заказчиком фотографий. Для заказа
                        данной услуги Вы можете воспользоваться подарочным сертификатом.</p>

                </div>
                <div id="tab-4" class="tab-content">

                    <p id="tab-4-1" class="edit-txt bb1">В разделе представленнные фотографии в основном с соревнований по художественной гимнастике,
                        проводимых в разных городах Украины и за рубежом. Условия проведения фотосессии обсуждаются
                        при личной встрече или по телефону.</p>

                </div>
                <div id="tab-5" class="tab-content">

                    <p id="tab-5-1" class="edit-txt bb1">Почасовая выездная съемка деток. В парке, на море или просто у Вас дома. Отличная память на
                        долгие годы, ведь наши детки подрастают очень быстро и этих милых мгновений уже не вернешь.
                        Звоните и записывайтесь на фотосессию. :) Так же я снимаю утренники в детских садах и делаю
                        виньетки для выпускников детского сада.</p>

                </div>
                <div id="tab-6" class="tab-content">

                    <p id="tab-6-1" class="edit-txt bb1">Фотографии снятые на прогулке, банкете или в студии. Это наиболее мой любимый и не простой
                        жанр в фотографии. Портретная съемка разносторонняя и увлекательная, передающая чувства,
                        характер и эмоции модели, а так же выражающая сюжет фотографии и показывающая индивидуальную
                        личность модели. Лучше всего получаются портреты, когда человек не подозревает о присутствии
                        фотокамеры. Если у Вас не индивидуальная фотосессия и Вы заметили что Вас снимает фотограф,
                        старайтесь не позировать и не "делать лицо". В этом случае снимки получаются наиболее
                        выразительными, естественными и живыми. Наоборот на прогулке, когда Вы с фотографом
                        тет-а-тет старайтесь расслабиться и думайте о чем-то радостном и приятном, о том что
                        возможно уже произошло или должно произойти в Вашей жизни.</p>

                </div>
                <div id="tab-7" class="tab-content">

                    <p id="tab-7-1" class="edit-txt bb1">Съемка с выпускных вечеров и образцы школьных виньеток, снятых при помощи выездной фотостудии
                        прямо в школе, что безусловно очень удобно как школьникам так и их родителям. </p>

                </div>

            </div>
        </div>

        <!--==============================новости================================-->
        <div class="col-3">

            <div class="h-mod">
                <div class="bb-img-red">
                <h3>Акции и новости:</h3>
										</div>
            </div>
            <div class="clock-container">
                <div class="clock">
                    <div id="Date"></div>
                    <ul>
                        <li id="hours"></li>
                        <li id="point">:</li>
                        <li id="min"></li>
                        <li id="point">:</li>
                        <li id="sec"></li>
                    </ul>

                </div>
            </div>
					{{{filenews}}}

        </div>

        <div class="clear"></div>
        <div id=\'new-gal\'>
					{{{carousel}}}
        </div>


    </div>
</section>

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
                
                $buffer .= $indent . '
';
                $buffer .= $indent . '<section>
';
                $buffer .= $indent . '    <div class="tabs clearfix">
';
                $buffer .= $indent . '        <table class="text-head-laitbox">
';
                $buffer .= $indent . '            <tbody>
';
                $buffer .= $indent . '            <tr>
';
                $buffer .= $indent . '                <td class="header" style="text-align: right;">
';
                $buffer .= $indent . '                    <span class="phone"><a onclick="goog_report_conversion(\'tel: 067-76-84-086\')" href="#">(067)-76-84-086</a></span><br>
';
                $buffer .= $indent . '                    <a href="http://';
                $value = $this->resolveValue($context->find('HTTP_HOST'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/portfolio.php#tab-1">свадебная фотосъёмка</a>,
';
                $buffer .= $indent . '                    <a href="http://';
                $value = $this->resolveValue($context->find('HTTP_HOST'), $context, $indent);
                $buffer .= call_user_func($this->mustache->getEscape(), $value);
                $buffer .= '/portfolio.php#tab-3">студийные фотосессии</a>, Одесса, Алексеева Анна
';
                $buffer .= $indent . '                </td>
';
                $buffer .= $indent . '            </tr>
';
                $buffer .= $indent . '            <tr>
';
                $buffer .= $indent . '                <td>
';
                $buffer .= $indent . '                    <div id="slide">
';
                $buffer .= $indent . '                        <div id="owl-head" class="owl-carousel owl-theme">
';
                // 'items' section
                $value = $context->find('items');
                $buffer .= $this->sectionF9dc820c3449fac567debd40c53e3acd($context, $indent, $value);
                $buffer .= $indent . '                        </div>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                        <div class=\'owl-head-pags-hide\'>
';
                $buffer .= $indent . '                            <div class=\'owl-head-pags\'>
';
                // 'pags' section
                $value = $context->find('pags');
                $buffer .= $this->sectionF771d89a58e3dad39f277da4583c64ba($context, $indent, $value);
                $buffer .= $indent . '                            </div>
';
                $buffer .= $indent . '                        </div>
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '                </td>
';
                $buffer .= $indent . '            </tr>
';
                $buffer .= $indent . '            </tbody>
';
                $buffer .= $indent . '        </table>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <section>
';
                $buffer .= $indent . '            <div class="col-1">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                <div class="h-mod">
';
                $buffer .= $indent . '                    <h2>Главная:</h2>
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                <ul class="list-title">
';
                // 'li_title' section
                $value = $context->find('li_title');
                $buffer .= $this->section537df280a48e315e4d2e30abd45af94e($context, $indent, $value);
                $buffer .= $indent . '                </ul>
';
                // 'admin_mode' section
                $value = $context->find('admin_mode');
                $buffer .= $this->sectionC625772ea9a4f43938c1f55590f437f1($context, $indent, $value);
                $buffer .= $indent . '							';
                $value = $this->resolveValue($context->find('EditTitle'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </section>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <div class="col-2">
';
                $buffer .= $indent . '            <div class="h-mod">
';
                $buffer .= $indent . '                <div class="bb-img-red">
';
                $buffer .= $indent . '                <h3>Коротко о разделе:</h3>
';
                $buffer .= $indent . '                    </div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            <div id="pageContent" class="block_wrapper_body">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                <ul>
';
                $buffer .= $indent . '                    <li class="block_body">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                        <ul class="block_wrap block_rounded">
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <h4>О свадьбах</h4>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                $buffer .= $indent . '                                    <div><img src="/files/slides/slide-3.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <p id="tab-1-1" class="edit-txt">Меня зовут Алексеева Анна, я профессиональный фотограф, живу и работаю в Одессе. Я специализируюсь на проведении свадебных фотосъёмок в Одессе, а в качестве свадебного фотографа работаю уже более 10 лет. Провожу также уличные и студийные персональные, семейные, детские фотосессии, рекламные и интерьерные фотосъёмки. Преимущественно снимаю в Одессе, но выезжаю и по Украине.
';
                $buffer .= $indent . '                                    Для уточнения стоимости фотосессий и заказа фотосъёмки звоните 067-76-84-086. С радостью отвечу на все ваши вопросы, проконсультирую по планированию и организации фотосъёмок, местам для проведения уличных и свадебных фотосессий, планированию свадебного дня и свадебной фотосессии, выбору фотостудии для студийных фотосессий.
';
                $buffer .= $indent . '                                </p>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li class="bb1 mbm">
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <a data-hover="aleks.od.ua" class="link-2" href="http://www.aleks.od.ua">aleks.od.ua</a>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                        </ul>
';
                $buffer .= $indent . '                    </li>
';
                $buffer .= $indent . '                    <li class="block_body">
';
                $buffer .= $indent . '                        <ul>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                // 'admin_mode' section
                $value = $context->find('admin_mode');
                $buffer .= $this->section06df9401443ff064c79e6c8b683b1988($context, $indent, $value);
                $buffer .= $indent . '                                    <div><img src="/files/slides/slide-2.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                // 'admin_mode' section
                $value = $context->find('admin_mode');
                $buffer .= $this->section06df9401443ff064c79e6c8b683b1988($context, $indent, $value);
                $buffer .= $indent . '                                    <div><img src="/files/portfolio/06_Портреты/002.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                $buffer .= $indent . '                                    <div><img src="files/slides/slide-3.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                $buffer .= $indent . '                                    <div><img src="files/slides/slide-3.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                $buffer .= $indent . '                                    <div><img src="files/slides/slide-2.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                        </ul>
';
                $buffer .= $indent . '                    </li>
';
                $buffer .= $indent . '                    <li class="block_body">
';
                $buffer .= $indent . '                        <ul class="block_wrap block_rounded">
';
                $buffer .= $indent . '                            <li><h4>О свадьбах</h4></li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <div class="focal-point border">
';
                $buffer .= $indent . '                                    <div><img src="files/slides/slide-1.jpg" alt=""></div>
';
                $buffer .= $indent . '                                </div>
';
                $buffer .= $indent . '                            </li>
';
                $buffer .= $indent . '                            <li>
';
                $buffer .= $indent . '                                <p id="tab-1-2" class="edit-txt">Уникальность свадьбы в том, что это самый первый памятный и волнующий праздник новой молодой семьи. Незабываемое событие для возлюбленной пары. Которое в полном смысле этого слова старается сделать незабываемым свадебный фотограф, чтобы сохранить воспоминания об этом празднике на живых и ярких фотографиях, снятых профессионально и с любовью к своему делу.
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                                    Для меня, как свадебного фотографа, снимать свадьбу – это большая ответственность, которую мне доверили молодожены. Во время фотосессии свадьбы я использую как постановочную так и репортажную съемку.</p>
';
                $buffer .= $indent . '                                <br /></li>
';
                $buffer .= $indent . '                            <li><a data-hover="aleks.od.ua"  class="link-2" href="http://www.aleks.od.ua">aleks.od.ua</a></li>
';
                $buffer .= $indent . '                        </ul>
';
                $buffer .= $indent . '                    </li>
';
                $buffer .= $indent . '                </ul>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                <div id="tab-1" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-1-1" class="edit-txt bb1">Уникальность свадьбы в том, что это самый первый памятный и волнующий праздник
';
                $buffer .= $indent . '                        новой молодой семьи. Незабываемое событие для возлюбленной пары. Которое в полном смысле
';
                $buffer .= $indent . '                        этого слова старается сделать незабываемым свадебный фотограф, чтобы сохранить воспоминания
';
                $buffer .= $indent . '                        об этом празднике на живых и ярких фотографиях, снятых профессионально и с любовью к своему
';
                $buffer .= $indent . '                        делу. Для меня, как свадебного фотографа, снимать свадьбу – это большая ответственность,
';
                $buffer .= $indent . '                        которую мне доверили молодожены. Во время фотосессии свадьбы я использую как постановочную
';
                $buffer .= $indent . '                        так и репортажную съемку.</p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div id="tab-2" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-2-1" class="edit-txt bb1">Фотокниги это современное, новое и интересное направление в оформлении альбомов. Я делаю
';
                $buffer .= $indent . '                        дизайн и осуществляю печать различных форматов фотокниг: cвадебные, школьные (выпускники),
';
                $buffer .= $indent . '                        детские(роддом, крестины, дни рождения), семейные(отдых,путешествия) и др. В отличие от
';
                $buffer .= $indent . '                        обычного фотоальбома, фотокнига выглядит более привлекательно и при надлежашем хранении
';
                $buffer .= $indent . '                        намного больше сохраняется и будет еще долгие годы радовать Вас и Ваших друзей приятными
';
                $buffer .= $indent . '                        воспоминаниями, запечатленными на её страницах. Вы можете заказать у меня изготовление
';
                $buffer .= $indent . '                        фотокниги и из своих домашних фотографий. Вам для этого необходимо лишь предоставить мне их
';
                $buffer .= $indent . '                        в цифровом виде или в виде фотографий. </p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div id="tab-3" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-3-1" class="edit-txt bb1">Репортажная съемка с банкетов, свадеб и корпоративов. Обработанные фотографии обычно выдаются
';
                $buffer .= $indent . '                        на DVD.
';
                $buffer .= $indent . '                        Также возможно изготовление слайд - шоу из выбранных заказчиком фотографий. Для заказа
';
                $buffer .= $indent . '                        данной услуги Вы можете воспользоваться подарочным сертификатом.</p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div id="tab-4" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-4-1" class="edit-txt bb1">В разделе представленнные фотографии в основном с соревнований по художественной гимнастике,
';
                $buffer .= $indent . '                        проводимых в разных городах Украины и за рубежом. Условия проведения фотосессии обсуждаются
';
                $buffer .= $indent . '                        при личной встрече или по телефону.</p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div id="tab-5" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-5-1" class="edit-txt bb1">Почасовая выездная съемка деток. В парке, на море или просто у Вас дома. Отличная память на
';
                $buffer .= $indent . '                        долгие годы, ведь наши детки подрастают очень быстро и этих милых мгновений уже не вернешь.
';
                $buffer .= $indent . '                        Звоните и записывайтесь на фотосессию. :) Так же я снимаю утренники в детских садах и делаю
';
                $buffer .= $indent . '                        виньетки для выпускников детского сада.</p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div id="tab-6" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-6-1" class="edit-txt bb1">Фотографии снятые на прогулке, банкете или в студии. Это наиболее мой любимый и не простой
';
                $buffer .= $indent . '                        жанр в фотографии. Портретная съемка разносторонняя и увлекательная, передающая чувства,
';
                $buffer .= $indent . '                        характер и эмоции модели, а так же выражающая сюжет фотографии и показывающая индивидуальную
';
                $buffer .= $indent . '                        личность модели. Лучше всего получаются портреты, когда человек не подозревает о присутствии
';
                $buffer .= $indent . '                        фотокамеры. Если у Вас не индивидуальная фотосессия и Вы заметили что Вас снимает фотограф,
';
                $buffer .= $indent . '                        старайтесь не позировать и не "делать лицо". В этом случае снимки получаются наиболее
';
                $buffer .= $indent . '                        выразительными, естественными и живыми. Наоборот на прогулке, когда Вы с фотографом
';
                $buffer .= $indent . '                        тет-а-тет старайтесь расслабиться и думайте о чем-то радостном и приятном, о том что
';
                $buffer .= $indent . '                        возможно уже произошло или должно произойти в Вашей жизни.</p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '                <div id="tab-7" class="tab-content">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                    <p id="tab-7-1" class="edit-txt bb1">Съемка с выпускных вечеров и образцы школьных виньеток, снятых при помощи выездной фотостудии
';
                $buffer .= $indent . '                        прямо в школе, что безусловно очень удобно как школьникам так и их родителям. </p>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <!--==============================новости================================-->
';
                $buffer .= $indent . '        <div class="col-3">
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '            <div class="h-mod">
';
                $buffer .= $indent . '                <div class="bb-img-red">
';
                $buffer .= $indent . '                <h3>Акции и новости:</h3>
';
                $buffer .= $indent . '										</div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '            <div class="clock-container">
';
                $buffer .= $indent . '                <div class="clock">
';
                $buffer .= $indent . '                    <div id="Date"></div>
';
                $buffer .= $indent . '                    <ul>
';
                $buffer .= $indent . '                        <li id="hours"></li>
';
                $buffer .= $indent . '                        <li id="point">:</li>
';
                $buffer .= $indent . '                        <li id="min"></li>
';
                $buffer .= $indent . '                        <li id="point">:</li>
';
                $buffer .= $indent . '                        <li id="sec"></li>
';
                $buffer .= $indent . '                    </ul>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '                </div>
';
                $buffer .= $indent . '            </div>
';
                $buffer .= $indent . '					';
                $value = $this->resolveValue($context->find('filenews'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '        <div class="clear"></div>
';
                $buffer .= $indent . '        <div id=\'new-gal\'>
';
                $buffer .= $indent . '					';
                $value = $this->resolveValue($context->find('carousel'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '        </div>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '    </div>
';
                $buffer .= $indent . '</section>
';
                $buffer .= $indent . '
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section39f9bb25e54dcf1855abe7096c05efad(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (is_object($value) && is_callable($value)) {
            $source = '
<script type=\'text/javascript\' src=\'/js/owl.carousel/owl.carousel.min.js\'></script>
<script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>

<script type=\'text/javascript\' src=\'/js/jquery.jurii.ajax-load.js\'></script>
<script type=\'text/javascript\' src=\'/js/web/jquery.jurii.ajax.hash.control.js\'></script>
<script type="text/javascript" src="/js/jquery-contained-sticky-scroll.js"></script>
<script type=\'text/javascript\' src=\'/js/index.slider.js\'></script>
<script type=\'text/javascript\' src=\'/js/clock.js\'></script>
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
                
                $buffer .= '<script type=\'text/javascript\' src=\'/js/owl.carousel/owl.carousel.min.js\'></script>
';
                $buffer .= $indent . '<script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
';
                $buffer .= $indent . '<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
';
                $buffer .= $indent . '
';
                $buffer .= $indent . '<script type=\'text/javascript\' src=\'/js/jquery.jurii.ajax-load.js\'></script>
';
                $buffer .= $indent . '<script type=\'text/javascript\' src=\'/js/web/jquery.jurii.ajax.hash.control.js\'></script>
';
                $buffer .= $indent . '<script type="text/javascript" src="/js/jquery-contained-sticky-scroll.js"></script>
';
                $buffer .= $indent . '<script type=\'text/javascript\' src=\'/js/index.slider.js\'></script>
';
                $buffer .= $indent . '<script type=\'text/javascript\' src=\'/js/clock.js\'></script>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
