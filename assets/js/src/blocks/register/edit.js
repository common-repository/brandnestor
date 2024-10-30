import { __ } from '@wordpress/i18n';

import {
  useBlockProps,
  InspectorControls,
  RichText,
} from '@wordpress/block-editor';

import {
  PanelBody,
  ButtonGroup,
  Button,
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
    label_username,
    label_email,
    label_firstname,
    label_lastname,
    show_firstname,
    show_lastname,
  } = attributes;

  const buttonGroupClasses = 'brandnestor-form-group brandnestor-align-' + button_align;

  return (
    <div {...useBlockProps()}>
      <InspectorControls key="setting">
        <PanelBody title={__('Content', 'brandnestor')}>
          <ToggleControl
            label={__('Show First Name Input', 'brandnestor')}
            checked={show_firstname}
            onChange={() => setAttributes({show_firstname: !show_firstname})}
            help={
              show_firstname
                ? __('Showing a first name input field', 'brandnestor')
                : __('Toggle to show the first name input field', 'brandnestor')
            }
            />
          <ToggleControl
            label={__('Show Last Name Input', 'brandnestor')}
            checked={show_lastname}
            onChange={() => setAttributes({show_lastname: !show_lastname})}
            help={
              show_lastname
                ? __('Showing a first name input field', 'brandnestor')
                : __('Toggle to show the last name input field', 'brandnestor')
            }
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
            value={label_username}
            onChange={(val) => setAttributes({label_username: val})}
            placeholder={__('Add a username label…', 'brandnestor')}
            />
          <input type="text" className="form-control" />
        </div>
        <div className="brandnestor-form-group">
          <RichText
            tagName="label"
            value={label_email}
            onChange={(val) => setAttributes({label_email: val})}
            placeholder={__('Add an email label…', 'brandnestor')}
            />
          <input type="email" className="form-control" />
        </div>
        {show_firstname && (
          <div className="brandnestor-form-group">
            <RichText
              tagName="label"
              value={label_firstname}
              onChange={(val) => setAttributes({label_firstname: val})}
              placeholder={__('Add a first name label…', 'brandnestor')}
              />
            <input type="text" className="form-control" />
          </div>
        )}
        {show_lastname && (
          <div className="brandnestor-form-group">
            <RichText
              tagName="label"
              value={label_lastname}
              onChange={(val) => setAttributes({label_lastname: val})}
              placeholder={__('Add a last name label…', 'brandnestor')}
              />
            <input type="text" className="form-control" />
          </div>
        )}
        <p className="brandnestor-form-group">{__('Registration confirmation will be emailed to you.')}</p>
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
          <a href="#">{__('Log in')}</a>
        </p>
      </form>
    </div>
  );
}
