!function(a){"use strict";a(function(){function b(a,b){var d=b.find(".rbm-field-colorpicker");d.find('input[name^="_rbm_repeater"]').appendTo(b.find("label")),d.find(".wp-picker-container").remove(),c(d)}function c(b){b.each(function(){a(this).find('input[type="text"]').wpColorPicker(a(this).data())})}var d=a(".rbm-field-colorpicker"),e=a(".rbm-field-repeater");d.length&&c(d),e.length&&e.on("add-item",b)})}(jQuery),function(a){"use strict";a(function(){function b(b,d){var e=a(".rbm-field-datepicker");e.find('input[type="text"]').removeClass("hasDatepicker").removeAttr("id"),c(e)}function c(b){b.each(function(){var b=a(this).find('input[type="hidden"]').attr("name"),c=["beforeShow","beforeShowDay","calculateWeek","onChangeMonthYear","onClose","onSelect"],d={};RBM_FieldHelpers["datepicker_args_"+b]&&(d=RBM_FieldHelpers["datepicker_args_"+b]),a.each(d,function(a,b){c.indexOf(a)!==-1&&(d[a]=window[b])}),d.altField='[name="'+b+'"]',d.altFormat="yymmdd",a(this).find(".rbm-field-datepicker-preview").datepicker(d)})}var d=a(".rbm-field-datepicker"),e=a(".rbm-field-repeater");e.length&&(e.on("add-item",b),e.on("repeater-init",b)),d.length&&c(d)})}(jQuery),function(a){"use strict";a(function(){function b(b,d){var e=a(".rbm-field-datetimepicker");e.find('input[type="text"]').removeClass("hasDatepicker").removeAttr("id"),c(e)}function c(b){b.each(function(){var b=a(this).find('input[type="hidden"]').attr("name"),c=["beforeShow","beforeShowDay","calculateWeek","onChangeMonthYear","onClose","onSelect"],d={};RBM_FieldHelpers["datetimepicker_args_"+b]&&(d=RBM_FieldHelpers["datetimepicker_args_"+b]),a.each(d,function(a,b){c.indexOf(a)!==-1&&(d[a]=window[b])}),d.altField='[name="'+b+'"]',d.altFormat="yymmdd",d.altTimeFormat="HH:mm",d.altFieldTimeOnly=!1,d.timeFormat="hh:mm tt",d.controlType="select",a(this).find(".rbm-field-datetimepicker-preview").datetimepicker(d)})}var d=a(".rbm-field-datetimepicker"),e=a(".rbm-field-repeater");d.length&&c(d),e.length&&(e.on("add-item",b),e.on("repeater-init",b))})}(jQuery),function(a){"use strict";a(function(){function b(a,b){var d=b.find(".rbm-field-list");c(d)}function c(b){b.each(function(){var b=a(this).attr("data-name"),c={};b&&"undefined"!=typeof RBM_FieldHelpers&&"undefined"!=typeof RBM_FieldHelpers.list_fields&&"undefined"!=typeof RBM_FieldHelpers.list_fields[b]&&(c=RBM_FieldHelpers.list_fields[b],a(this).find("ul[data-rbm-list]").sortable(c))})}var d=a(".rbm-field-list"),e=a(".rbm-field-repeater");d.length&&c(d),e.length&&e.on("add-item",b)})}(jQuery),function(a){"use strict";window.rbm_instantiate_media_uploaders=function(){var b,c;wp.media&&(b=wp.media.frames.meta_image_frame=wp.media({title:"Choose Media"}),b.on("select",function(){var a=b.state().get("selection").first().toJSON();switch(c.find(".media-id").val(a.id),c.find(".upload-media").hide(),c.find(".remove-media").show(),c.data("type")){case"image":c.find(".image-preview").attr("src",a.url);break;default:c.find(".media-url").html(a.url)}}),a(document).on("click",".rbm-media-uploader .upload-media",function(d){d.preventDefault(),c=a(this).closest(".rbm-media-uploader");c.data("type");b.open()}),a(document).on("click",".rbm-media-uploader .remove-media",function(b){switch(a(this).siblings(".upload-media").show(),a(this).hide(),a(this).siblings(".media-id").val(""),c=a(this).closest(".rbm-media-uploader"),c.data("type")){case"image":var d=a(this).siblings(".image-preview");d.attr("src",d.data("placeholder")||"");break;default:var e=a(this).siblings(".media-url");e.html(e.data("placeholder")||"&nbsp;")}c=!1}))},a(rbm_instantiate_media_uploaders)}(jQuery),function(a){"use strict";a(document).on("click","[data-number-increase], [data-number-decrease]",function(){var b,c=a(this).siblings(".rbm-field-input"),d=parseInt(a(this).attr("data-number-interval")),e=parseInt(a(this).attr("data-number-alt-interval")),f=window.ld_gb_shift_key_down===!0?e:d,g=c.val();g=""===g?0:parseInt(g),b="undefined"!=typeof a(this).data("number-increase")?g+f:g-f,c.val(b).change()}),a(document).on("keydown",'.rbm-field-number input[type="text"]',function(b){var c;38===b.which?c=a(this).siblings("[data-number-increase]"):40==b.which&&(c=a(this).siblings("[data-number-decrease]")),c&&(c.click(),b.preventDefault())}),a(document).on("change",'.rbm-field-number input[type="text"]',function(){var b=a(this).siblings("[data-number-increase]").attr("data-number-max"),c=a(this).siblings("[data-number-decrease]").attr("data-number-min"),d=a(this).val();"none"!=b&&d>parseInt(b)?a(this).val(b):"none"!=c&&d<parseInt(c)?a(this).val(c):a(this).val(d.match(/\d*\.?\d+/))})}(jQuery),function(a){"use strict";a(function(){function b(b){var c=b.find("select.rbm-select2");c.each(function(){var b=a(this).siblings(".select2-container");b.length&&b.remove(),a(this).show().select2()})}a(".rbm-field-repeater").each(function(){var c=a(this).find(".rbm-field-repeater-list");c.data("repeater-list");a(this).repeater({show:function(){a(this).slideDown(),b(a(this));var c=a(this).closest(".rbm-field-repeater");c.hasClass("collapsable")&&(a(this).addClass("opened").removeClass("closed"),a(this).find(".rbm-field-repeater-header span.collapsable-title").html(a(this).find(".rbm-field-repeater-header span.collapsable-title").data("collapsable-title-default")),a(this).find(".collapse-icon").css({transform:"rotate(-180deg)"})),a(this).trigger("add-item",[a(this)])},hide:function(b){confirm("Are you sure you want to delete this element?")&&(a(this).slideUp(400,b),a(this).trigger("delete-item",[a(this)]))},ready:function(a){c.on("sortupdate",a)},isFirstItemUndeletable:!1}),c.sortable({axis:"y",handle:".rbm-field-repeater-handle",forcePlaceholderSize:!0,placeholder:"rbm-sortable-placeholder",update:function(b,d){c.trigger("list-update",[a(this).closest(".rbm-field-repeater")])}}),a(this).find(".rbm-field-repeater-row.dummy").remove(),a(this).hasClass("collapsable")&&a(this).find(".rbm-field-repeater-content").hide(),a(this).trigger("repeater-init",[a(this)])}),a(document).on("click touchend",".rbm-field-repeater.collapsable [data-repeater-collapsable-handle]",function(){var b=a(this).closest(".rbm-field-repeater-row"),c=b.find(".rbm-field-repeater-content").first(),d=b.hasClass("opened")?"closing":"opening",e=a(this).find(".collapse-icon");"opening"==d?(c.stop().slideDown(),e.css({transform:"rotate(-180deg)"}),b.addClass("opened"),b.removeClass("closed")):(c.stop().slideUp(),e.css({transform:"rotate(0deg)"}),b.addClass("closed"),b.removeClass("opened"))})})}(jQuery),function(a){"use strict";a(function(){function b(b){b.each(function(){var b=a(this),e=a(this).find("table");e.find("thead th").first().find("[data-table-delete-column]").prop("disabled",!0),e.find("tbody tr").first().find("[data-table-delete-row]").prop("disabled",!0),e.find("tbody").sortable({axis:"y",handle:".rbm-field-table-sort",update:d}),a(this).find("[data-table-create-row]").click(function(){var d=a(this).closest(".rbm-field-table").find("table"),e=d.find("tbody tr").first().clone(!0,!0);e.find("[data-table-delete-row]").prop("disabled",!1),e.find('input[type="text"]').val(""),d.find("tbody").append(e),c(b)}),a(this).find("[data-table-create-column]").click(function(){var d=a(this).closest(".rbm-field-table").find("table"),e=d.find("thead th").first().clone(!0,!0),f=d.find("tbody td").first().clone(!0,!0);e.find('input[type="text"]').val(""),e.find("[data-table-delete-column]").prop("disabled",!1),f.find('input[type="text"]').val(""),d.find("thead tr th:last-of-type").before(e),d.find("tbody tr td:last-of-type").before(f),c(b)}),a(this).find("[data-table-delete-row]").click(function(){a(this).closest("tr").remove(),c(b)}),a(this).find("[data-table-delete-column]").click(function(){var d=a(this).closest("table"),e=a(this).closest("th").index();d.find("th:nth-child("+(e+1)+")").remove(),d.find("td:nth-child("+(e+1)+")").remove(),c(b)})})}function c(b){b.each(function(){a(this).find("thead th").each(function(){if(a(this).hasClass("actions"))return!0;var b=a(this).index(),c=a(this).find('input[type="text"]').attr("name"),d="["+b+"]";a(this).find('input[type="text"]').attr("name",c.replace(/\[\d]/,d))}),a(this).find("tbody td").each(function(){if(a(this).hasClass("actions"))return 0===a(this).closest("tr").index()?a(this).find("[data-table-delete-row]").prop("disabled",!0):a(this).find("[data-table-delete-row]").prop("disabled",!1),!0;var b=a(this).closest("tr").index(),c=a(this).index(),d=a(this).find('input[type="text"]').attr("name"),e="["+b+"]["+c+"]";a(this).find('input[type="text"]').attr("name",d.replace(/\[\d]\[\d]/,e))})})}function d(a,b,d){c(b.item)}var e=a(".rbm-field-table");e.length&&b(e)})}(jQuery),function(a){"use strict";a(function(){function b(b,d){var e=a(".rbm-field-timepicker");e.find('input[type="text"]').removeClass("hasDatepicker").removeAttr("id"),c(e)}function c(b){b.each(function(){var b=a(this).find('input[type="hidden"]').attr("name"),c=["beforeShow","beforeShowDay","calculateWeek","onChangeMonthYear","onClose","onSelect"],d={};RBM_FieldHelpers["timepicker_args_"+b]&&(d=RBM_FieldHelpers["timepicker_args_"+b]),a.each(d,function(a,b){c.indexOf(a)!==-1&&(d[a]=window[b])}),d.altField='[name="'+b+'"]',d.altTimeFormat="HH:mm",d.altFieldTimeOnly=!1,d.timeFormat="hh:mm tt",d.controlType="select",a(this).find(".rbm-field-timepicker-preview").timepicker(d)})}var d=a(".rbm-field-timepicker"),e=a(".rbm-field-repeater");d.length&&c(d),e.length&&(e.on("add-item",b),e.on("repeater-init",b))})}(jQuery),function(a,b){"use strict";function c(){d(),l.length&&(r=l.data("post-id"),e())}function d(){l=a("#rbm-field-user-keys[data-ajax]"),l.length&&(m=l.find(".rbm-user-keys-container"),n=l.find("table.rbm-user-keys"),o=l.find("[data-user-key-add]"),q=l.find("[data-user-key-delete-all]"),p=l.find('[name="_rbm_new_user_email"]'))}function e(){o.click(f),l.on("click","[data-user-key-delete]",g),q.click(h),p.keypress(k)}function f(){var c=l.find('[name="_rbm_new_user_email"]').val(),d=l.find('.rbm-user-keys-input-error[data-input="_rbm_new_user_email"]');return c.length?(d.hide(),void a.post(ajaxurl,{action:b.userkeys_ajax_add_user_action,rbm_field_helpers_nonce:b.nonce,email:c,post_ID:r},function(a){"success"==a.status?(i(a.user_email,a.user_key,a.edit_link),p.val("")):"fail"==a.status&&a.error_msg&&alert(a.error_msg)})):void d.show()}function g(c){var d=a(this).data("email");c.preventDefault(),confirm("Are you sure you want to delete this user?")&&a.post(ajaxurl,{action:b.userkeys_ajax_delete_user_action,rbm_field_helpers_nonce:b.nonce,post_ID:r,email:d},function(a){"success"==a.status?j(a.user_email):"fail"==a.status&&a.error_msg&&alert(a.error_msg)})}function h(c){c.preventDefault(),confirm("Are you sure you want to delete ALL users?")&&a.post(ajaxurl,{action:b.userkeys_ajax_delete_users_action,rbm_field_helpers_nonce:b.nonce,post_ID:r},function(a){"success"==a.status?m.fadeOut(300,function(){n.find("tbody tr:not([data-template])").remove()}):"fail"==a.status&&a.error_msg&&alert(a.error_msg)})}function i(a,b,c){var d=n.find("tr[data-template]").clone();d.removeAttr("data-template"),d.attr("data-email",a),d.find("[data-user-email]").text(a),d.find("[data-user-link]").text(c).attr("href",c),d.find("[data-user-key-delete]").attr("data-email",a),n.find("tbody tr:not([data-template])").length||m.fadeIn(),n.find("tbody").append(d),d.fadeIn()}function j(b){n.find('tr[data-email="'+b+'"]').fadeOut(300,function(){a(this).remove(),n.find("tbody tr:not([data-template])").length||m.fadeOut()})}function k(a){"13"==a.charCode&&(a.preventDefault(),o.click())}var l,m,n,o,p,q,r;a(c)}(jQuery,RBM_FieldHelpers),function(a){"use strict";function b(b){for(var c=a(b[0]).closest(".rbm-field-repeater-list").data("repeater-list"),d=new RegExp("("+c+"_)\\d(_.*?)","i"),e=new RegExp("("+c+"\\[)\\d(\\]\\[.*?\\])","i"),g=tinymce.editors.slice(0),h=0;h<g.length;h++)g[h].id.indexOf(c)>-1&&tinymce.execCommand("mceRemoveEditor",!0,g[h].id);b.each(function(b,g){var h=a(g).attr("data-id").replace(d,"$1"+b+"$2");a(g).attr("data-id",h),a(g).find(".button").each(function(){a(this).data("editor")&&(a(this).attr("data-editor","_rbm_"+h),a(this).data("editor","_rbm_"+h))}),a(g).find("[id*="+c+"]").each(function(){a(this).attr("id",a(this).attr("id").replace(d,"$1"+b+"$2")),a(this).data("wp-editor-id")&&a(this).attr("data-wp-editor-id","_rbm_"+h)});var i=a(g).find("textarea.wp-editor-area");i.attr("name",i.attr("name").replace(e,"$1"+b+"$2"));var j={selector:i.attr("id"),plugins:"charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,wpembed",content_css:"/wp-includes/css/dashicons.css,/wp-includes/js/tinymce/skins/wordpress/wp-content.css",menubar:!1,toolbar1:"bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,dfw,wp_adv,currentprojects,singleproject",toolbar2:"formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",toolbar3:"",toolbar4:"",skin:!1};f||(j.plugins=j.plugins.replace(/wpembed,?/gi,"")),a(g).hasClass("teeny")&&(j.toolbar1="bold,italic,underline,blockquote,strikethrough,bullist,numlist,alignleft,aligncenter,alignright,undo,redo,link,unlink,fullscreen",j.toolbar2=""),tinyMCEPreInit.mceInit["_rbm_"+i.attr("id")]=j,tinymce.init(j),tinymce.execCommand("mceAddEditor",!1,i.attr("id")),quicktags({id:i.attr("id"),buttons:"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"}),QTags._buttonsInit();var k=a(g).find(".wp-editor-wrap").hasClass("html-active")?"html":"tmce";switchEditors.go(i.attr("id"),k),f||"html"!=k||setTimeout(function(){a(g).find(".wp-switch-editor.switch-html").click()},100)})}function c(a,c){var d=c.parent().find(".rbm-field-wysiwyg");d.length&&b(d)}var d=RBM_FieldHelpers.wp_version.split(".")[0].toString(),e=RBM_FieldHelpers.wp_version.split(".")[1].toString(),f=!0;("4"==d&&e>="8"||d.toString()>"4")&&(f=!1),a(function(){var b=a(".rbm-field-repeater");b.length&&b.on("add-item list-update delete-item",c)})}(jQuery);
//# sourceMappingURL=rbm-field-helpers-admin.js.map