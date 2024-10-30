import { __ } from '@wordpress/i18n';

import {
  useBlockProps,
  InspectorControls,
  RichText,
} from '@wordpress/block-editor';

import {
  Button,
  ButtonGroup,
  PanelBody,
  TextControl,
  ToggleControl,
} from '@wordpress/components';

import {
  Icon,
  justifyLeft,
  justifyCenter,
  justifyRight,
  justifySpaceBetween,
} from '@wordpress/icons';

const icons = {
  left: justifyLeft,
  center: justifyCenter,
  right: justifyRight,
  justify: justifySpaceBetween,
};

export default function edit({attributes, setAttributes}) {
  const {
    button_align,
    label_button,
    label_new_password_button,
    label_login,
    label_password,
    label_remember,
    show_lost_password,
    show_register,
  } = attributes;

  const buttonGroupClasses = 'brandnestor-form-group brandnestor-align-' + button_align;

  return (
    <div {...useBlockProps()}>
      <InspectorControls key="setting">
        <PanelBody title={__('Content', 'brandnestor')}>
          <ToggleControl
            label={__('Show Lost Password', 'brandnestor')}
            checked={show_lost_password}
            onChange={() => setAttributes({show_lost_password: !show_lost_password})}
            help={
              show_lost_password
                ? __('Showing lost password link', 'brandnestor')
                : __('Toggle to show lost password link', 'brandnestor')
            }
            />
          <ToggleControl
            label={__('Show Register URL', 'brandnestor')}
            checked={show_register}
            onChange={() => setAttributes({show_register: !show_register})}
            help={
              show_register
                ? __('Showing register link', 'brandnestor')
                : __('Toggle to show register link', 'brandnestor')
            }
            />
          <TextControl
            label={__('New Password Button Text', 'brandnestor')}
            value={label_new_password_button}
            onChange={(val) => setAttributes({label_new_password_button: val})}
            />
          <fieldset>
            <legend>{__('Button Alignment', 'brandnestor')}</legend>
            <ButtonGroup>
              <Button isPressed={button_align === 'left'} onClick={() => setAttributes({button_align: 'left'})}>
                <Icon icon={icons.left} />
              </Button>
              <Button isPressed={button_align === 'center'} onClick={() => setAttributes({button_align: 'center'})}>
                <Icon icon={icons.center} />
              </Button>
              <Button isPressed={button_align === 'right'} onClick={() => setAttributes({button_align: 'right'})}>
                <Icon icon={icons.right} />
              </Button>
              <Button isPressed={button_align === 'justify'} onClick={() => setAttributes({button_align: 'justify'})}>
                <Icon icon={icons.justify} />
              </Button>
            </ButtonGroup>
          </fieldset>
        </PanelBody>

      </InspectorControls>

			<p className="brandnestor-form-message">{__('Sample message.', 'brandnestor')}</p>
			<p className="brandnestor-form-message error">{__('Sample error message.', 'brandnestor')}</p>

      <form method="post">
        <div className="brandnestor-form-group">
          <RichText
            tagName="label"
            value={label_login}
            onChange={(val) => setAttributes({label_login: val})}
            placeholder={__('Add a username label…', 'brandnestor')}
            />
          <input type="text" className="form-control" />
        </div>
        <div className="brandnestor-form-group">
          <RichText
            tagName="label"
            value={label_password}
            onChange={(val) => setAttributes({label_password: val})}
            placeholder={__('Add a password label…', 'brandnestor')}
            />
          <input type="text" className="form-control" />
        </div>
        <div className="brandnestor-form-group">
          <label className="brandnestor-checkbox">
            <input type="checkbox" disabled />
            <RichText
              tagName="span"
              value={label_remember}
              onChange={(val) => setAttributes({label_remember: val})}
              placeholder={__('Add text…')}
              />
          </label>
        </div>
        <div className={buttonGroupClasses}>
          <RichText
            tagName="button"
            type="button"
            value={label_button}
            className="brandnestor-button wp-block-button__link"
            onChange={(val) => setAttributes({label_button: val})}
            placeholder={__('Add text…')}
            />
        </div>
        <p className="brandnestor-form-group">
          {show_lost_password && (
            <a className="brandnestor-toggle-form" href="#">{__('Lost your password?')}</a>
          )}
          <span> | </span>
          {show_register && (
            <a href="#">{__('Register')}</a>
          )}
        </p>
      </form>
    </div>
  );
}
