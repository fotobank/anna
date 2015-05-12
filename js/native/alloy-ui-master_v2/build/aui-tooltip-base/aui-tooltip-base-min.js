YUI.add("aui-tooltip-base",function(e,t){var n=e.Lang,r=e.getClassName,i=r("tooltip-arrow"),s=r("tooltip-inner");e.Tooltip=e.Base.create("tooltip",e.Widget,[e.WidgetCssClass,e.WidgetPosition,e.WidgetStdMod,e.WidgetToggle,e.WidgetAutohide,e.WidgetPositionAlign,e.WidgetPositionAlignSuggestion,e.WidgetPositionConstrain,e.WidgetTransition,e.WidgetTrigger],{initializer:function(){var t=this;e.after(t._afterUiSetTrigger,t,"_uiSetTrigger")},renderUI:function(){var t=this,n=t.get("boundingBox"),r=t.get("contentBox");r.addClass(s),n.append(e.Tooltip.TEMPLATES.arrow)},bindUI:function(){var t=this,n=t.get("trigger");n&&n.on("hover",e.bind(t._onBoundingBoxMouseenter,t),e.bind(t._onBoundingBoxMouseleave,t)),t.get("boundingBox").on("hover",e.bind(t._onBoundingBoxMouseenter,t),e.bind(t._onBoundingBoxMouseleave,t))},_uiSetVisible:function(e){var t=this,n=t.get("boundingBox");t._widgetUiSetVisible(e),n.setStyle("opacity",e?t.get("opacity"):0),e&&t._loadBodyContentFromTitle()},_afterUiSetTrigger:function(e){var t=this;t.suggestAlignment(e)},_loadBodyContentFromTitle:function(){var t=this,n,r,i,s;i=t.get("formatter"),n=t.get("trigger");if(!n)return;r=n.getAttribute("data-title"),s=n.getAttribute("title")||r,i&&(s=i.call(t,s)),r||n.removeAttribute("title").setAttribute("data-title",s),t.setStdModContent(e.WidgetStdMod.BODY,n&&s||t.get("bodyContent"))},_onBoundingBoxMouseenter:function(){var e=this;e.show()},_onBoundingBoxMouseleave:function(){var e=this;e.hide()},_widgetUiSetVisible:e.Widget.prototype._uiSetVisible},{CSS_PREFIX:r("tooltip"),ATTRS:{animated:{value:!0},constrain:{value:!0},formatter:{validator:e.Lang.isFunction},opacity:{value:.8},triggerShowEvent:{validator:n.isString,value:"mouseenter"}},TEMPLATES:{arrow:'<div class="'+i+'"></div>'}})},"2.5.0",{requires:["event-hover","widget","widget-autohide","widget-position","widget-position-align","widget-position-constrain","widget-stack","widget-stdmod","aui-classnamemanager","aui-component","aui-widget-cssclass","aui-widget-toggle","aui-widget-transition","aui-widget-trigger","aui-widget-position-align-suggestion","aui-node-base"]});