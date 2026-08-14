if(tinymce.majorVersion + '.' + tinymce.minorVersion == '6.8.3'){
	initPurchaseEditor();
}

function initPurchaseEditor(selector, settings) {
  selector = typeof selector == "undefined" ? ".tinymce" : selector;
  var _editorSelectorCheck = document.querySelectorAll(selector);

  if (_editorSelectorCheck.length === 0) {
    return;
  }

  _editorSelectorCheck.forEach(function (el) {
    if (el.classList.contains("tinymce-manual")) {
      el.classList.remove("tinymce");
    }
  });

  // Original settings
  var _settings = {
    branding: false, // Ensure branding is set to false
    selector: selector,
    browser_spellcheck: true,
    height: 400,
    skin: "oxide", // Updated skin for TinyMCE 6.8.3

    relative_urls: false,
    inline_styles: true,
    verify_html: false,
    cleanup: false,
    autoresize_bottom_margin: 25,
    valid_elements: "+*[*]",
    valid_children: "+body[style], +style[type]",
    apply_source_formatting: false,
    remove_script_host: false,
    removed_menuitems: "newdocument restoredraft",
    forced_root_block: "p",
    autosave_restore_when_empty: false,
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    setup: function (editor) {
      // Default fontsize is 12
      editor.on("init", function () {
        editor.getBody().style.fontSize = "12pt";
      });
    },
    table_default_styles: {
      // Default all tables width 100%
      width: "100%",
    },
    plugins: [
     
    ],
    toolbar:
      "fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | image link | bullist numlist | restoredraft",
    //file_picker_callback: elFinderBrowser,
    contextmenu:
      "link image inserttable | cell row column deletetable | paste copy",
  };

  // Add the rtl to the settings if is true
  if (isRTL == "true") {
    _settings.directionality = "rtl";
    _settings.plugins.push("directionality");
  }

  // Possible settings passed to be overwritten or added
  if (typeof settings != "undefined") {
    for (var key in settings) {
      if (key != "append_plugins") {
        _settings[key] = settings[key];
      } else {
        _settings["plugins"].push(settings[key]);
      }
    }
  }

  // Init the editor
  tinymce.init(_settings).then((editors) => {
    document.dispatchEvent(new Event("app.editor.initialized"));
  });

  // Add custom CSS to hide the branding
  var style = document.createElement('style');
  style.innerHTML = '.tox-promotion { display: none !important; }';
  document.head.appendChild(style);

  return tinymce.activeEditor;
}

function init_pur_ajax_search(type, selector, server_data, url) {
  var ajaxSelector = $("body").find(selector);

  if (ajaxSelector.length) {
    var options = {
      ajax: {
        url:
          typeof url == "undefined"
            ? admin_url + "purchase/get_relation_data"
            : url,
        data: function () {
          var data = {};
          data.type = type;
          data.rel_id = "";
          data.q = "{{{q}}}";
          if (typeof server_data != "undefined") {
            jQuery.extend(data, server_data);
          }
          return data;
        },
      },
      locale: {
        emptyTitle: app.lang.search_ajax_empty,
        statusInitialized: app.lang.search_ajax_initialized,
        statusSearching: app.lang.search_ajax_searching,
        statusNoResults: app.lang.not_results_found,
        searchPlaceholder: app.lang.search_ajax_placeholder,
        currentlySelected: app.lang.currently_selected,
      },
      requestDelay: 500,
      cache: false,
      preprocessData: function (processData) {
        var bs_data = [];
        var len = processData.length;
        for (var i = 0; i < len; i++) {
          var tmp_data = {
            value: processData[i].id,
            text: processData[i].name,
          };
          if (processData[i].subtext) {
            tmp_data.data = {
              subtext: processData[i].subtext,
            };
          }
          bs_data.push(tmp_data);
        }
        return bs_data;
      },
      preserveSelectedPosition: "after",
      preserveSelected: true,
    };
    if (ajaxSelector.data("empty-title")) {
      options.locale.emptyTitle = ajaxSelector.data("empty-title");
    }
    ajaxSelector.selectpicker().ajaxSelectPicker(options);
  }
}