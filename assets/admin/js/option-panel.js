!function(t){var n={};function e(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,e),r.l=!0,r.exports}e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{enumerable:!0,get:o})},e.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},e.t=function(t,n){if(1&n&&(t=e(t)),8&n)return t;if(4&n&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(e.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&n&&"string"!=typeof t)for(var r in t)e.d(o,r,function(n){return t[n]}.bind(null,r));return o},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},e.p="",e(e.s=4)}({4:function(t,n,e){"use strict";var o;(o=jQuery)(function(){window.rankMath_MonitorOptions={init:function(){this.misc()},misc:function(){o(".reset-options").on("click",function(){return!!confirm("Are you sure? You want to reset settings.")&&(o(window).off("beforeunload"),!0)});var t=o(".rank-math-tabs");setTimeout(function(){localStorage.removeItem(t.attr("id"))},1e3),o(".save-options").on("click",function(){var n=o("> .rank-math-tabs-navigation > a.active",t);return localStorage.setItem(t.attr("id"),n.attr("href")),o(window).off("beforeunload"),!0});var n=!1;o(window).on("load",function(){o(".cmb-form").on("change","input, textarea, select",function(){n=!0})}),o(window).on("beforeunload",function(){if(n)return"Are you sure? You didn't finish the form!"}),o(".custom-sep").on("keyup",function(){var t=o(this),n=t.text();t.closest("li").find("input.cmb2-option").val(n).trigger("change")})}},window.rankMath_MonitorOptions.init()})}});