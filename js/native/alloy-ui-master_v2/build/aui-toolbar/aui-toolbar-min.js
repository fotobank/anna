YUI.add("aui-toolbar",function(e,t){var n=e.Lang,r=n.isArray,i=n.isString,s=n.isFunction,o=e.getClassName,u=o("btn"),a=o("btn","group"),f=o("btn","group","checkbox"),l=o("btn","group","radio"),c=o("btn","group","vertical");e.Toolbar=e.Component.create({NAME:"toolbar",ATTRS:{children:{validator:r},toolbarRenderer:{valueFn:function(){return new e.ToolbarRenderer}}},UI_ATTRS:["children"],isSupportedWidget:function(t){return e.instanceOf(t,e.Button)||e.instanceOf(t,e.ToggleButton)||e.instanceOf(t,e.ButtonGroup)},prototype:{CONTENT_TEMPLATE:null,TEMPLATES:{button:'<button class="aui-btn">{content}</button>',icon:'<i class="{cssClass}"></i>',group:'<div class="aui-btn-group {cssClass}"></div>'},bindUI:function(){var e=this,t=e.get("boundingBox");t.delegate(["click","mousemove","focus"],e._onUserInitInteraction,"."+u,e)},add:function(t,n){var r=this,i=r.get("boundingBox"),s=r.get("toolbarRenderer");i.insert(s.render(e.Array(t)),n)},clear:function(){var e=this,t=e.get("boundingBox");t.get("children").remove()},getEnclosingWidget:function(t){return e.instanceOf(t,e.EventFacade)&&(t=t.domEvent?t.domEvent.target:t.target),e.Widget.getByNode(t)},item:function(t){var n=this,r=n.get("boundingBox").get("children").item(t),i;return n._initEnclosingWidgetIfNeeded(r),i=n.getEnclosingWidget(r),e.Toolbar.isSupportedWidget(i)?i:r},remove:function(e){var t=this,n=t.get("boundingBox");return n.get("children").item(e).remove()},_onUserInitInteraction:function(e){var t=this,n=e.currentTarget;t._initEnclosingWidgetIfNeeded(n)},_initEnclosingWidgetIfNeeded:function(t){if(!t||t.getData("enclosingWidgetInitialized"))return;t.setData("enclosingWidgetInitialized",!0);var n=e.Widget.getByNode(t),r=e.instanceOf(n,e.Button),i=e.instanceOf(n,e.ButtonGroup);if(r||i)return;var s=t.ancestor("."+u,!0);s&&(e.Button.hasWidgetLazyConstructorData(t)?(new e.Button(e.Button.getWidgetLazyConstructorFromNodeData(t)),e.Button.setWidgetLazyConstructorNodeData(t,null)):t.plug(e.Plugin.Button));var o=t.ancestor("."+a,!0);if(o){var c;o.hasClass(f)?c="checkbox":o.hasClass(l)&&(c="radio"),c&&new e.ButtonGroup({boundingBox:o,type:c,render:!0})}},_uiSetChildren:function(e){var t=this;if(!e)return;t.clear(),t.add(e)}}});var h=function(){};h.prototype={TEMPLATES:{button:e.Button.prototype.TEMPLATE,group:'<div class="'+a+' {cssClass}"></div>',icon:'<i class="{cssClass}" />'},RENDERER:{button:function(t){var r=this,i=t.value,s=i.domType||"button",o,a;if(e.instanceOf(i,e.Button)||e.instanceOf(i,e.ToggleButton))return i.get("boundingBox");a=e.one(i.boundingBox||i.srcNode);if(a)try{a.setAttribute("type",s)}catch(f){}else a=e.Node.create(e.ButtonExt.getTypedButtonTemplate(r.TEMPLATES.button,s));o=[u,i.cssClass],i.primary&&o.push(e.ButtonCore.CLASS_NAMES.PRIMARY),a.addClass(o.join(" ")),i.id&&a.setAttribute("id",i.id),i.label&&a.append(i.label);if(i.icon){var l=n.sub(r.TEMPLATES.icon,{cssClass:i.icon});e.Button.syncIconUI(a,l,i.iconAlign)}return i.title&&a.setAttribute("title",i.title),e.Button.setWidgetLazyConstructorNodeData(a,i),a},group:function(t){var r=this,i=t.value,s=t.groupType,o=t.orientation,u=[];if(e.instanceOf(i,e.ButtonGroup))return i.get("boundingBox");s==="checkbox"?u.push(f):s==="radio"&&u.push(l),o==="vertical"&&u.push(c);var a=e.Node.create(n.sub(r.TEMPLATES.group,{cssClass:u.join(" ")}));return e.Array.each(i,function(t,n){var s=r.renderNode(t);a.appendChild(s),e.Toolbar.isSupportedWidget(t)||e.Button.setWidgetLazyConstructorNodeData(s,i[n])}),a}},render:function(t){var n=this;if(!t)return;var r=e.one(e.config.doc).invoke("createDocumentFragment");return e.Array.each(t,function(e){r.appendChild(n.renderNode(e))}),r},renderNode:function(t){var n=this,r,i;if(e.Toolbar.isSupportedWidget(t))return t.render().get("boundingBox");r=n._getChildRenderHints(t),i=n.RENDERER[r.renderer];if(s(i))return i.call(n,r)},_getChildRenderHints:function(t){var n=null,s="normal",o;return e.instanceOf(t,e.Button)?o="button":e.instanceOf(t,e.ButtonGroup)?o="group":r(t)?(o="group",n=i(t[0])?t.shift():null,s=i(t[0])?t.shift():"normal"):o="button",{groupType:n,orientation:s,renderer:o,value:t}}},e.ToolbarRenderer=h},"2.5.0",{requires:["arraylist","arraylist-add","aui-component","aui-button-core"]});
