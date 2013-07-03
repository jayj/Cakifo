/*
* MultiSelect v0.9.8
* Copyright (c) 2012 Louis Cuny
*
* This program is free software. It comes without any warranty, to
* the extent permitted by applicable law. You can redistribute it
* and/or modify it under the terms of the Do What The Fuck You Want
* To Public License, Version 2, as published by Sam Hocevar. See
* http://sam.zoy.org/wtfpl/COPYING for more details.
*/

!function(b){var a=function(d,c){this.options=c;this.$element=b(d);this.$container=b("<div/>",{"class":"ms-container"});this.$selectableContainer=b("<div/>",{"class":"ms-selectable"});this.$selectionContainer=b("<div/>",{"class":"ms-selection"});this.$selectableUl=b("<ul/>",{"class":"ms-list",tabindex:"-1"});this.$selectionUl=b("<ul/>",{"class":"ms-list",tabindex:"-1"});this.scrollTo=0;this.sanitizeRegexp=new RegExp("\\W+","gi");this.elemsSelector="li:visible:not(.ms-optgroup-label,.ms-optgroup-container)"};a.prototype={constructor:a,init:function(){var e=this,d=this.$element;if(d.next(".ms-container").length===0){d.css({position:"absolute",left:"-9999px"});d.attr("id",d.attr("id")?d.attr("id"):Math.ceil(Math.random()*1000)+"multiselect");this.$container.attr("id","ms-"+d.attr("id"));d.find("option").each(function(){e.generateLisFromOption(this)});this.$selectionUl.find(".ms-optgroup-label").hide();if(e.options.selectableHeader){e.$selectableContainer.append(e.options.selectableHeader)}e.$selectableContainer.append(e.$selectableUl);if(e.options.selectableFooter){e.$selectableContainer.append(e.options.selectableFooter)}if(e.options.selectionHeader){e.$selectionContainer.append(e.options.selectionHeader)}e.$selectionContainer.append(e.$selectionUl);if(e.options.selectionFooter){e.$selectionContainer.append(e.options.selectionFooter)}e.$container.append(e.$selectableContainer);e.$container.append(e.$selectionContainer);d.after(e.$container);e.activeMouse(e.$selectableUl);e.activeKeyboard(e.$selectableUl);var f=e.options.dblClick?"dblclick":"click";e.$selectableUl.on(f,".ms-elem-selectable",function(){e.select(b(this).data("ms-value"))});e.$selectionUl.on(f,".ms-elem-selection",function(){e.deselect(b(this).data("ms-value"))});e.activeMouse(e.$selectionUl);e.activeKeyboard(e.$selectionUl);d.on("focus",function(){e.$selectableUl.focus()})}var c=d.find("option:selected").map(function(){return b(this).val()}).get();e.select(c,"init");if(typeof e.options.afterInit==="function"){e.options.afterInit.call(this,this.$container)}},generateLisFromOption:function(j){var f=this,l=f.$element,g="",o=b(j);for(var h=0;h<j.attributes.length;h++){var n=j.attributes[h];if(n.name!=="value"){g+=n.name+'="'+n.value+'" '}}var q=b("<li "+g+"><span>"+o.text()+"</span></li>"),d=q.clone(),m=o.val(),i=f.sanitize(m,f.sanitizeRegexp);q.data("ms-value",m).addClass("ms-elem-selectable").attr("id",i+"-selectable");d.data("ms-value",m).addClass("ms-elem-selection").attr("id",i+"-selection").hide();if(o.prop("disabled")||l.prop("disabled")){d.addClass(f.options.disabledClass);q.addClass(f.options.disabledClass)}var k=o.parent("optgroup");if(k.length>0){var e=k.attr("label"),c=f.sanitize(e,f.sanitizeRegexp),t=f.$selectableUl.find("#optgroup-selectable-"+c),r=f.$selectionUl.find("#optgroup-selection-"+c);if(t.length===0){var s='<li class="ms-optgroup-container"></li>',p='<ul class="ms-optgroup"><li class="ms-optgroup-label"><span>'+e+"</span></li></ul>";t=b(s);r=b(s);t.attr("id","optgroup-selectable-"+c);r.attr("id","optgroup-selection-"+c);t.append(b(p));r.append(b(p));if(f.options.selectableOptgroup){t.find(".ms-optgroup-label").on("click",function(){var u=k.children(":not(:selected)").map(function(){return b(this).val()}).get();f.select(u)});r.find(".ms-optgroup-label").on("click",function(){var u=k.children(":selected").map(function(){return b(this).val()}).get();f.deselect(u)})}f.$selectableUl.append(t);f.$selectionUl.append(r)}t.children().append(q);r.children().append(d)}else{f.$selectableUl.append(q);f.$selectionUl.append(d)}},activeKeyboard:function(c){var d=this;c.on("focus",function(){b(this).addClass("ms-focus")}).on("blur",function(){b(this).removeClass("ms-focus")}).on("keydown",function(f){switch(f.which){case 40:case 38:f.preventDefault();f.stopPropagation();d.moveHighlight(b(this),(f.which===38)?-1:1);return;case 32:f.preventDefault();f.stopPropagation();d.selectHighlighted(c);return;case 37:case 39:f.preventDefault();f.stopPropagation();d.switchList(c);return}})},moveHighlight:function(e,k){var c=e.find(this.elemsSelector),l=c.filter(".ms-hover"),n=null,d=c.first().outerHeight(),o=e.height(),g="#"+this.$container.prop("id");c.off("mouseenter");c.removeClass("ms-hover");if(k===1){n=l.nextAll(this.elemsSelector).first();if(n.length===0){var f=l.parent();if(f.hasClass("ms-optgroup")){var j=f.parent(),m=j.next(":visible");if(m.length>0){n=m.find(this.elemsSelector).first()}else{n=c.first()}}else{n=c.first()}}}else{if(k===-1){n=l.prevAll(this.elemsSelector).first();if(n.length===0){var f=l.parent();if(f.hasClass("ms-optgroup")){var j=f.parent(),i=j.prev(":visible");if(i.length>0){n=i.find(this.elemsSelector).last()}else{n=c.last()}}else{n=c.last()}}}}if(n.length>0){n.addClass("ms-hover");var h=e.scrollTop()+n.position().top-o/2+d/2;e.scrollTop(h)}},selectHighlighted:function(c){var e=c.find(this.elemsSelector),d=e.filter(".ms-hover").first();if(d.length>0){if(c.parent().hasClass("ms-selectable")){this.select(d.data("ms-value"))}else{this.deselect(d.data("ms-value"))}e.removeClass("ms-hover")}},switchList:function(c){c.blur();if(c.parent().hasClass("ms-selectable")){this.$selectionUl.focus()}else{this.$selectableUl.focus()}},activeMouse:function(c){var d=this;c.on("mousemove",function(){var e=c.find(d.elemsSelector);e.on("mouseenter",function(){e.removeClass("ms-hover");b(this).addClass("ms-hover")})})},refresh:function(){this.destroy();this.$element.multiSelect(this.options)},destroy:function(){b("#ms-"+this.$element.attr("id")).remove();this.$element.removeData("multiselect")},select:function(k,c){if(typeof k==="string"){k=[k]}var f=this,d=this.$element,g=b.map(k,function(m){return(f.sanitize(m,f.sanitizeRegexp))}),h=this.$selectableUl.find("#"+g.join("-selectable, #")+"-selectable").filter(":not(."+f.options.disabledClass+")"),e=this.$selectionUl.find("#"+g.join("-selection, #")+"-selection").filter(":not(."+f.options.disabledClass+")"),l=d.find("option:not(:disabled)").filter(function(){return(b.inArray(this.value,k)>-1)});if(h.length>0){h.addClass("ms-selected").hide();e.addClass("ms-selected").show();l.prop("selected",true);var j=f.$selectableUl.children(".ms-optgroup-container");if(j.length>0){j.each(function(){var m=b(this).find(".ms-elem-selectable");if(m.length===m.filter(".ms-selected").length){b(this).find(".ms-optgroup-label").hide()}});var i=f.$selectionUl.children(".ms-optgroup-container");i.each(function(){var m=b(this).find(".ms-elem-selection");if(m.filter(".ms-selected").length>0){b(this).find(".ms-optgroup-label").show()}})}else{if(f.options.keepOrder){e.insertAfter(f.$selectionUl.find(".ms-selected").last())}}if(c!=="init"){d.trigger("change");if(typeof f.options.afterSelect==="function"){f.options.afterSelect.call(this,k)}}}},deselect:function(j){if(typeof j==="string"){j=[j]}var e=this,c=this.$element,f=b.map(j,function(l){return(e.sanitize(l,e.sanitizeRegexp))}),g=this.$selectableUl.find("#"+f.join("-selectable, #")+"-selectable"),d=this.$selectionUl.find("#"+f.join("-selection, #")+"-selection").filter(".ms-selected"),k=c.find("option").filter(function(){return(b.inArray(this.value,j)>-1)});if(d.length>0){g.removeClass("ms-selected").show();d.removeClass("ms-selected").hide();k.prop("selected",false);var i=e.$selectableUl.children(".ms-optgroup-container");if(i.length>0){i.each(function(){var l=b(this).find(".ms-elem-selectable");if(l.filter(":not(.ms-selected)").length>0){b(this).find(".ms-optgroup-label").show()}});var h=e.$selectionUl.children(".ms-optgroup-container");h.each(function(){var l=b(this).find(".ms-elem-selection");if(l.filter(".ms-selected").length===0){b(this).find(".ms-optgroup-label").hide()}})}c.trigger("change");if(typeof e.options.afterDeselect==="function"){e.options.afterDeselect.call(this,j)}}},select_all:function(){var e=this.$element,d=e.val();e.find('option:not(":disabled")').prop("selected",true);this.$selectableUl.find(".ms-elem-selectable").filter(":not(."+this.options.disabledClass+")").addClass("ms-selected").hide();this.$selectionUl.find(".ms-optgroup-label").show();this.$selectableUl.find(".ms-optgroup-label").hide();this.$selectionUl.find(".ms-elem-selection").filter(":not(."+this.options.disabledClass+")").addClass("ms-selected").show();this.$selectionUl.focus();e.trigger("change");if(typeof this.options.afterSelect==="function"){var c=b.grep(e.val(),function(f){return b.inArray(f,d)<0});this.options.afterSelect.call(this,c)}},deselect_all:function(){var d=this.$element,c=d.val();d.find("option").prop("selected",false);this.$selectableUl.find(".ms-elem-selectable").removeClass("ms-selected").show();this.$selectionUl.find(".ms-optgroup-label").hide();this.$selectableUl.find(".ms-optgroup-label").show();this.$selectionUl.find(".ms-elem-selection").removeClass("ms-selected").hide();this.$selectableUl.focus();d.trigger("change");if(typeof this.options.afterDeselect==="function"){this.options.afterDeselect.call(this,c)}},sanitize:function(d,c){return(d.replace(c,"_"))}};b.fn.multiSelect=function(){var d=arguments[0],c=arguments;return this.each(function(){var g=b(this),f=g.data("multiselect"),e=b.extend({},b.fn.multiSelect.defaults,g.data(),typeof d==="object"&&d);if(!f){g.data("multiselect",(f=new a(this,e)))}if(typeof d==="string"){f[d](c[1])}else{f.init()}})};b.fn.multiSelect.defaults={selectableOptgroup:false,disabledClass:"disabled",dblClick:false,keepOrder:false};b.fn.multiSelect.Constructor=a}(window.jQuery);

(function($){

	$(document).ready( function() {
		$('#cakifo_theme_settings-headlines_category').multiSelect({
			keepOrder: true,
      selectableHeader: "<div class='custom-header'>Selectable terms</div>",
      selectionHeader: "<div class='custom-header'>Selected terms</div>",
		});
	});

})(jQuery);
