!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=2)}({2:function(t,e,n){"use strict";var r;(r=jQuery)(function(){var t=r("#rank-math-feedback-form"),e=t.find(".rank-math-feedback-input-wrapper"),n=t.find("form"),o=r("#the-list").find('[data-slug*="404-monitor"] span.deactivate a');o.on("click",function(e){e.preventDefault(),t.fadeIn()}),t.on("change","input:radio",function(){var t=r(this).parent();e.removeClass("checked"),t.toggleClass("checked")}),t.on("click",".button-skip",function(){location.href=o.attr("href")}),t.on("click",".button-close",function(e){e.preventDefault(),t.fadeOut()}),n.on("submit",function(t){t.preventDefault(),n.find(".button-submit").text("").addClass("loading"),r.ajax({url:ajaxurl,type:"POST",dataType:"json",data:n.serialize()}).done(function(){location.href=o.attr("href")})}),r(".module-listing .rank-math-box:not(.active), .rank-math-404-redirect-btn").on("click",function(t){if(t.preventDefault(),"module-404-monitor"!==r(this).attr("for"))return r("#rank-math-feedback-form").fadeIn(),!1}),r("#rank-math-feedback-form").on("click",function(t){"rank-math-feedback-form"===t.target.id&&r(this).find(".button-close").trigger("click")})})}});