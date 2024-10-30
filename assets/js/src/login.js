import visibleIcon from '../../image/icon_visible.svg';
import hiddenIcon from '../../image/icon_hidden.svg';

jQuery(document).ready(function ($) {
  const selectors = {
    toggleFormButton: '.brandnestor-toggle-form',
    loginForm: '#login_form',
    lostPasswordForm: '#lost_password_form',
    hidePwButton: '.hide-pw',
    userPassword: '#password',
  };

  const elements = {
    $toggleFormButton: $(selectors.toggleFormButton),
    $loginForm: $(selectors.loginForm),
    $lostPasswordForm: $(selectors.lostPasswordForm),
    $hidePwButton: $(selectors.hidePwButton),
    $userPassword: $(selectors.userPassword),
  };

  elements.$hidePwButton.html(visibleIcon);
  const hidePwToggle = function(show) {
    elements.$hidePwButton
      .attr({
        'aria-label': show ? wp.i18n.__('Show password') : wp.i18n.__('Hide password')
      })
      .html(show ? visibleIcon : hiddenIcon);
  };

  elements.$toggleFormButton.on('click', function (e) {
    e.preventDefault();
    elements.$loginForm.slideToggle(400);
    elements.$lostPasswordForm.slideToggle(400);
  });

  elements.$hidePwButton.on('click', function (e) {
    e.preventDefault();

    if (elements.$userPassword.attr('type') === 'password') {
      elements.$userPassword.attr('type', 'text');
      hidePwToggle(false);
    } else {
      elements.$userPassword.attr('type', 'password');
      hidePwToggle(true);
    }

    elements.$userPassword.focus();
  })

  const checkPasswordStrength = function(wrapper, field) {
    const passwordStrengthHint = wrapper.find('.brandnestor-password-strength');
    const strength = wp.passwordStrength.meter(field.val(), wp.passwordStrength.userInputDisallowedList());

    passwordStrengthHint.removeClass('short bad good strong');

    switch (strength) {
    case 0:
      passwordStrengthHint.addClass('short').html(pwsL10n.short);
      break;
    case 1:
      passwordStrengthHint.addClass('bad').html(pwsL10n.bad);
      break;
    case 2:
      passwordStrengthHint.addClass('bad').html(pwsL10n.bad);
      break;
    case 3:
      passwordStrengthHint.addClass('good').html(pwsL10n.good);
      break;
    case 4:
      passwordStrengthHint.addClass('strong').html(pwsL10n.strong);
      break;
    case 5:
      passwordStrengthHint.addClass('bad').html(pwsL10n.mismatch);
      break;
    }

    return strength;
  };

  $(document.body).on('keyup change', '#reset_password_form #password_1', function() {
    const $submit = $('#reset_password_form button[type="submit"]');
    const $passwordStrengthHint = $('.brandnestor-password-strength');
    const $field = $('#password_1');

    let strength = checkPasswordStrength($('#reset_password_form'), $field);

    if ($field.val().length > 0 && strength !== -1 && strength < 3) {
      $submit.attr('disabled', 'disabled');
    } else {
      $submit.prop('disabled', false);
    }
  });
});
