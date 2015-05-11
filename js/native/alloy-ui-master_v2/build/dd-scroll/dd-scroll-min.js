YUI.add("dd-scroll",function(e,t){var n=function(){n.superclass.constructor.apply(this,arguments)},r,i,s="host",o="buffer",u="parentScroll",a="windowScroll",f="scrollTop",l="scrollLeft",c="offsetWidth",h="offsetHeight";n.ATTRS={parentScroll:{value:!1,setter:function(e){return e?e:!1}},buffer:{value:30,validator:e.Lang.isNumber},scrollDelay:{value:235,validator:e.Lang.isNumber},host:{value:null},windowScroll:{value:!1,validator:e.Lang.isBoolean},vertical:{value:!0,validator:e.Lang.isBoolean},horizontal:{value:!0,validator:e.Lang.isBoolean}},e.extend(n,e.Base,{_scrolling:null,_vpRegionCache:null,_dimCache:null,_scrollTimer:null,_getVPRegion:function(){var e={},t=this.get(u),n=this.get(o),r=this.get(a),i=r?[]:t.getXY(),s=r?"winWidth":c,p=r?"winHeight":h,d=r?t.get(f):i[1],v=r?t.get(l):i[0];return e={top:d+n,right:t.get(s)+v-n,bottom:t.get(p)+d-n,left:v+n},this._vpRegionCache=e,e},initializer:function(){var t=this.get(s);t.after("drag:start",e.bind(this.start,this)),t.after("drag:end",e.bind(this.end,this)),t.on("drag:align",e.bind(this.align,this)),e.one("win").on("scroll",e.bind(function(){this._vpRegionCache=null},this))},_checkWinScroll:function(e){var t=this._getVPRegion(),n=this.get(s),r=this.get(a),i=n.lastXY,c=!1,h=this.get(o),p=this.get(u),d=p.get(f),v=p.get(l),m=this._dimCache.w,g=this._dimCache.h,y=i[1]+g,b=i[1],w=i[0]+m,E=i[0],S=b,x=E,T=d,N=v;this.get("horizontal")&&(E<=t.left&&(c=!0,x=i[0]-(r?h:0),N=v-h),w>=t.right&&(c=!0,x=i[0]+(r?h:0),N=v+h)),this.get("vertical")&&(y>=t.bottom&&(c=!0,S=i[1]+(r?h:0),T=d+h),b<=t.top&&(c=!0,S=i[1]-(r?h:0),T=d-h)),T<0&&(T=0,S=i[1]),N<0&&(N=0,x=i[0]),S<0&&(S=i[1]),x<0&&(x=i[0]),e?(n.actXY=[x,S],n._alignNode([x,S],!0),i=n.actXY,n.actXY=[x,S],n._moveNode({node:p,top:T,left:N}),!T&&!N&&this._cancelScroll()):c?this._initScroll():this._cancelScroll()},_initScroll:function(){this._cancelScroll(),this._scrollTimer=e.Lang.later(this.get("scrollDelay"),this,this._checkWinScroll,[!0],!0)},_cancelScroll:function(){this._scrolling=!1,this._scrollTimer&&(this._scrollTimer.cancel(),delete this._scrollTimer)},align:function(e){this._scrolling&&(this._cancelScroll(),e.preventDefault()),this._scrolling||this._checkWinScroll()},_setDimCache:function(){var e=this.get(s).get("dragNode");this._dimCache={h:e.get(h),w:e.get(c)}},start:function(){this._setDimCache()},end:function(){this._dimCache=null,this._cancelScroll()}}),e.namespace("Plugin"),r=function(){r.superclass.constructor.apply(this,arguments)},r.ATTRS=e.merge(n.ATTRS,{windowScroll:{value:!0,setter:function(t){return t&&this.set(u,e.one("win")),t}}}),e.extend(r,n,{initializer:function(){this.set("windowScroll",this.get("windowScroll"))}}),r.NAME=r.NS="winscroll",e.Plugin.DDWinScroll=r,i=function(){i.superclass.constructor.apply(this,arguments)},i.ATTRS=e.merge(n.ATTRS,{node:{value:!1,setter:function(t){var n=e.one(t);return n?this.set(u,n):t!==!1&&e.error("DDNodeScroll: Invalid Node Given: "+t),n}}}),e.extend(i,n,{initializer:function(){this.set("node",this.get("node"))}}),i.NAME=i.NS="nodescroll",e.Plugin.DDNodeScroll=i,e.DD.Scroll=n},"patched-v3.16.0",{requires:["dd-drag"]});
