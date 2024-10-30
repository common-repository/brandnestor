import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';

registerBlockType('brandnestor/register', {
  title: __('BrandNestor Register', 'brandnestor'),
  description: __('A custom Register widget for WordPress', 'brandnestor'),
  icon: 'businessperson',
  category: 'widgets',
  attributes: {
    button_align: {
      type: 'string',
      default: 'left',
    },
    label_username: {
      type: 'string',
      default: '',
    },
    label_email: {
      type: 'string',
      default: '',
    },
    label_firstname: {
      type: 'string',
      default: '',
    },
    label_lastname: {
      type: 'string',
      default: '',
    },
    label_button: {
      type: 'string',
      default: '',
    },
    show_firstname: {
      type: 'boolean',
      default: false,
    },
    show_lastname: {
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
