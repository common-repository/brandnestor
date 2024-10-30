import { showMessage, showErrorMessage, makeRequest, Modal } from './settingsCommon.js';
import { createRuleSet } from './rule.js';
import SlimSelect from 'slim-select';

window.brandnestorSettingsCommon = {
  createRuleSet,
  makeRequest,
  showErrorMessage,
  showMessage,
  Modal,
};

function switchTab() {
  const tab = window.location.hash.split('=')[1] ??
    document.querySelector('.brandnestor nav a')?.hash.split('=')[1];

  document.querySelectorAll('.brandnestor .tab-panel').forEach(item => item.style.display = 'none');
  document.querySelector(`#${tab}`).style.display = 'block';

  document.querySelectorAll('.brandnestor nav a').forEach(item => item.classList.remove('active'));
  document.querySelector(`.brandnestor nav a[href="#tab=${tab}"]`).classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
  const $ = jQuery;
  window.addEventListener('hashchange', switchTab, false);
  switchTab();

  document.getElementById('link_reset_plugin').addEventListener('click', function(event) {
    if (!confirm(BRANDNESTOR_I18N.reset_plugin_message)) {
      event.preventDefault();
    }
  });

  document.querySelectorAll('.brandnestor-select.slim-select').forEach(item => {
    new SlimSelect({
      select: item,
      allowDeselect: true,
      placeholder: BRANDNESTOR_I18N.select_placeholder,
      closeOnSelect: !item.multiple,
    });
  });

  const request = makeRequest(BRANDNESTOR.ajax_url, BRANDNESTOR.nonce);

  // Admin Menus Rules
  {
    const brandnestorRuleSet = createRuleSet('#admin_menus_ruleset', 'admin_menus_rules');
    const refreshAdminMenusRules = async function(brandnestorRuleSet) {
      const data = await request('get_admin_menus_rules');
      brandnestorRuleSet.cleanup();
      brandnestorRuleSet.setData(data);
      brandnestorRuleSet.hydrate();

      for (const rule of brandnestorRuleSet) {
        rule.element.find('.form-rule-head').append('<p>' + BRANDNESTOR_I18N.menu_edit_hint + '</p>');
        rule.onDelete(function() {
          return request('delete_admin_menus_rule', { id: rule.id });
        });
      }
    }

    refreshAdminMenusRules(brandnestorRuleSet);

    brandnestorRuleSet.onAdd(() => {
      let menus = {};
      brandnestorRuleSet.repeatable.find('input[type="checkbox"]').each(function() {
        let name = JSON.parse(this.name);
        menus[name.menu] = 'on';
      });

      request('add_admin_menus_rule', JSON.stringify({ targets: brandnestorRuleSet.getSelected(), fields: menus }))
        .then(() => refreshAdminMenusRules(brandnestorRuleSet));
    });
  }

  // Bar Menus Rules
  {
    const brandnestorRuleSet = createRuleSet('#bar_menu_rules_ruleset', 'bar_menu_rules');
    const refreshAdminBarMenusRules = async function(brandnestorRuleSet) {
      const data = await request('get_bar_menu_rules');
      brandnestorRuleSet.cleanup();
      brandnestorRuleSet.setData(data);
      brandnestorRuleSet.hydrate();

      for (const rule of brandnestorRuleSet) {
        rule.onDelete(function() {
          return request('delete_bar_menu_rule', {id: rule.id});
        });
      }
    };
    refreshAdminBarMenusRules(brandnestorRuleSet);

    brandnestorRuleSet.onAdd(() => {
      let menus = {};
      brandnestorRuleSet.repeatable.find('input[type="checkbox"]').each(function() {
        let name = JSON.parse(this.name);
        menus[name.menu] = 'on';
      });

      request('add_bar_menu_rule', JSON.stringify({ targets: brandnestorRuleSet.getSelected(), fields: menus }))
        .then(() => refreshAdminBarMenusRules(brandnestorRuleSet));
    });
  }

  // Media Upload Button
  document.querySelectorAll('.upload-image').forEach(btn => btn.addEventListener('click', () => {
    const frame = wp.media.frames.file_name = wp.media({
      library: {
        type: 'image',
      },
      multiple: false,
    });

    frame.on('select', () => {
      const attachment = frame.state().get('selection').first().toJSON();

      btn.parentNode.querySelector('img')?.remove();
      btn.parentNode.querySelector('input').value = attachment.url;

      const img = document.createElement('img');
      img.src = attachment.url;

      btn.parentNode.prepend(img);
    });

    frame.open();
  }));

  // Form Group Collapse
  $('.collapse').on('click', '.collapse-toggle', function () {
    let $this = $(this);
    $this.closest('.collapse').find('ul').first().slideToggle(300);
    $this.toggleClass('open');
  });

  $('.collapse > .form-input').on('change', '.form-switch', function () {
    $(this).closest('.collapse').find('ul input').prop('checked', this.checked);
  });

  // Conditionally show form groups
  $('[data-condition]').each(function(index, elem) {
    const conditionInitiatorName = elem.dataset.condition;
    const conditionValue = elem.dataset.conditionValue;
    const $conditionInitiator = $(`#settings_form [name=${conditionInitiatorName}]`);
    const $elem = $(elem);

    $elem.hide();

    $conditionInitiator.on('change', function() {
      const $this = $(this);
      $elem.hide();
      if (($this.is(':checked') || $this.is('select'))
        && (conditionValue === $this.val() || (conditionValue === undefined && $this.val()))
      ) {
        $elem.show();
      } else {
        $elem.find('input').prop('checked', false);
      }
    });

    $conditionInitiator.each(function() {
      const $this = $(this);
      if ($this.is(':checked') || $this.is('select')) {
        $this.trigger('change');
      }
    });
  });

  document.querySelector('input[name="disable_wp_login"]').addEventListener('change', e => {
    if (e.target.checked) {
      const modal = new Modal(BRANDNESTOR_I18N.are_you_sure);
      modal.confirm(BRANDNESTOR_I18N.disable_login_confirm).then(ok => e.target.checked = ok);
    }
  });

  // Code Editors
  const dashboardHtml = document.getElementById('dashboard_html');
  const dashboardCss = document.getElementById('dashboard_css');
  const adminCss = document.getElementById('admin_css');

  const codeMirrorSettings = {
    lineNumbers: true,
    tabSize: 2,
    autoRefresh: true,
    matchTags: true,
    autoCloseTags: true,
  };

  const dashboardHtmlEditor = wp.CodeMirror.fromTextArea(dashboardHtml, {...codeMirrorSettings, mode: "htmlmixed"});
  const dashboardCssEditor = wp.CodeMirror.fromTextArea(dashboardCss, {...codeMirrorSettings, mode: "css"});
  const adminCssEditor = wp.CodeMirror.fromTextArea(adminCss, {...codeMirrorSettings, mode: "css"});

  // Save Settings
  $('#settings_form').on('submit', function(event) {
    event.preventDefault();

    let settings = Object.fromEntries(new FormData(this));

    settings.dashboard_html = dashboardHtmlEditor.getValue();
    settings.dashboard_css = dashboardCssEditor.getValue();
    settings.admin_css = adminCssEditor.getValue();

    request('save_settings', settings)
      .then(() => showMessage(BRANDNESTOR_I18N.settings_saved));
  });

  $('#settings_form .editable').on('keyup', function(event) {
    $(this).parent().siblings('label.form-input').find('input').val(this.innerText).trigger('change');
  });
});
