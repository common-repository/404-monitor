!function(e){var n={};function t(r){if(n[r])return n[r].exports;var i=n[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,t),i.l=!0,i.exports}t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:r})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(t.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var i in e)t.d(r,i,function(n){return e[n]}.bind(null,i));return r},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=0)}([function(e,n,t){"use strict";var r;(r=jQuery)(function(){window.rankMathAdmin={init:function(){this.misc(),this.dependencyManager()},misc:function(){r(".cmb-group-text-only,.cmb-group-fix-me").each(function(){var e=r(this),n=e.find(".cmb-repeatable-group"),t=n.find("> .cmb-row:eq(0) > .cmb-th");e.prepend('<div class="cmb-th"><label>'+t.find("h2").text()+"</label></div>"),n.find(".cmb-add-row").append('<span class="cmb2-metabox-description">'+t.find("p").text()+"</span>"),t.parent().remove()})},dependencyManager:function(){var e=this,n=r(".cmb-form, .rank-math-metabox-wrap");r(".cmb-repeat-group-wrap",n).each(function(){var e=r(this),n=e.next(".rank-math-cmb-dependency.hidden");n.length&&e.find("> .cmb-td").append(n)}),r(".rank-math-cmb-dependency",n).each(function(){e.loopDependencies(r(this))}),r("input, select",n).on("change",function(){var n=r(this).attr("name");r('span[data-field="'+n+'"]').each(function(){e.loopDependencies(r(this).closest(".rank-math-cmb-dependency"))})})},checkDependency:function(e,n,t){return"string"==typeof n&&n.includes(",")&&"="===t?n.includes(e):"string"==typeof n&&n.includes(",")&&"!="===t?!n.includes(e):"="===t&&e===n||"=="===t&&e===n||">="===t&&e>=n||"<="===t&&n>=e||">"===t&&e>n||"<"===t&&n>e||"!="===t&&e!==n},loopDependencies:function(e){var n,t=this,i=e.data("relation");e.find("span").each(function(){var e=r(this),a=e.data("value"),c=e.data("comparison"),o=r("[name='"+e.data("field")+"']"),d=o.val();o.is(":radio")&&(d=o.filter(":checked").val()),o.is(":checkbox")&&(d=o.is(":checked"));var u=t.checkDependency(d,a,c);if("or"===i&&u)return n=!0,!1;"and"===i&&(n=void 0===n?u:n&&u)});var a=e.closest(".rank-math-cmb-group");a.length||(a=e.closest(".cmb-row")),n?a.slideDown(300):a.hide()}},window.rankMathAdmin.init()})}]);