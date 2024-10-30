import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';

registerBlockType('brandnestor/login', {
  title: __('BrandNestor Login', 'brandnestor'),
  description: __('A custom Login widget for WordPress', 'brandnestor'),
  icon: 'unlock',
  category: 'widgets',
  attributes: {
    button_align: {
      type: 'string',
      default: 'left',
    },
    label_login: {
      type: 'string',
      default: '',
    },
    label_password: {
      type: 'string',
      default: '',
    },
    label_remember: {
      type: 'string',
      default: __('Remember Me'),
    },
    label_button: {
      type: 'string',
      default: '',
    },
    label_new_password_button: {
      type: 'string',
      default: __('Get New Password', 'brandnestor'),
    },
    show_lost_password: {
      type: 'boolean',
      default: true,
    },
    show_register: {
      type: 'boolean',
      default: false,
    },
    style: {
      type: 'object',
    },
  },
  supports: {
    color: {
      gradients: true,
    },
    html: false,
    spacing: {
      margin: true,
      padding: true,
    },
  },
  edit,
});
