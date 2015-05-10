YUI.add("dd-ddm-base",function(e,t){var n=function(){n.superclass.constructor.apply(this,arguments)};n.NAME="ddm",n.ATTRS={dragCursor:{value:"move"},clickPixelThresh:{value:3},clickTimeThresh:{value:1e3},throttleTime:{value:-1},dragMode:{value:"point",setter:function(e){return this._setDragMode(e),e}}},e.extend(n,e.Base,{_createPG:function(){},_active:null,_setDragMode:function(t){t===null&&(t=e.DD.DDM.get("dragMode"));switch(t){case 1:case"intersect":return 1;case 2:case"strict":return 2;case 0:case"point":return 0}return 0},CSS_PREFIX:e.ClassNameManager.getClassName("dd"),_activateTargets:function(){},_drags:[],activeDrag:!1,_regDrag:function(e){return this.getDrag(e.get("node"))?!1:(this._active||this._setupListeners(),this._drags.push(e),!0)},_unregDrag:function(t){var n=[];e.Array.each(this._drags,function(e){e!==t&&(n[n.length]=e)}),this._drags=n},_setupListeners:function(){this._createPG(),this._active=!0;var t=e.one(e.config.doc);t.on("mousemove",e.throttle(e.bind(this._docMove,this),this.get("throttleTime"))),t.on("mouseup",e.bind(this._end,this))},_start:function(){this.fire("ddm:start"),this._startDrag()},_startDrag:function(){},_endDrag:function(){},_dropMove:function(){},_end:function(){this.activeDrag&&(this._shimming=!1,this._endDrag(),this.fire("ddm:end"),this.activeDrag.end.call(this.activeDrag),this.activeDrag=null)},stopDrag:function(){return this.activeDrag&&this._end(),this},_shimming:!1,_docMove:function(e){this._shimming||this._move(e)},_move:function(e){this.activeDrag&&(this.activeDrag._move.call(this.activeDrag,e),this._dropMove())},cssSizestoObject:function(e){var t=e.split(" ");switch(t.length){case 1:t[1]=t[2]=t[3]=t[0];break;case 2:t[2]=t[0],t[3]=t[1];break;case 3:t[3]=t[1]}return{top:parseInt(t[0],10),right:parseInt(t[1],10),bottom:parseInt(t[2],10),left:parseInt(t[3],10)}},getDrag:function(t){var n=!1,r=e.one(t);return r instanceof e.Node&&e.Array.each(this._drags,function(e){r.compareTo(e.get("node"))&&(n=e)}),n},swapPosition:function(t,n){t=e.DD.DDM.getNode(t),n=e.DD.DDM.getNode(n);var r=t.getXY(),i=n.getXY();return t.setXY(i),n.setXY(r),t},getNode:function(t){return t instanceof e.Node?t:(t&&t.get?e.Widget&&t instanceof e.Widget?t=t.get("boundingBox"):t=t.get("node"):t=e.one(t),t)},swapNode:function(t,n){t=e.DD.DDM.getNode(t),n=e.DD.DDM.getNode(n);var r=n.get("parentNode"),i=n.get("nextSibling");return i===t?r.insertBefore(t,n):n===t.get("nextSibling")?r.insertBefore(n,t):(t.get("parentNode").replaceChild(n,t),r.insertBefore(t,i)),t}}),e.namespace("DD"),e.DD.DDM=new n},"patched-v3.16.0",{requires:["node","base","yui-throttle","classnamemanager"]});
