const makeRequest = (url, nonce) => {
  const loadingIndicator = jQuery('#loading_indicator');

  return async (action, data = {}) => {
    // 'data' is a string, put it in an object
    if (typeof data === 'string') {
      data = { data: data };
    }

    loadingIndicator.fadeIn(100);
    const response = await fetch(url, {
      method: 'POST',
      body: new URLSearchParams({ _ajax_nonce: nonce, action: 'brandnestor_' + action, ...data }),
    });
    loadingIndicator.fadeOut(100);

    if (!response.ok) {
      showErrorMessage(BRANDNESTOR_I18N.request_error_message);
      throw response.status;
    }

    const responseData = await response.json();

    if (responseData.success === false) {
      showErrorMessage(responseData.data);
      throw responseData.data;
    }

    return responseData;
  };
};

const showMessage = (text, isError = false) => {
  const notifications = document.querySelector('.notifications');

  const notification = document.createElement('p');
  notification.innerText = text;
  if (isError) notification.classList.add('error');

  notifications.prepend(notification);

  notification.animate([
    { opacity: 0, transform: 'translateY(-100%)' },
    { opacity: 1, transform: 'translateY(0%)', offset: 0.08, easing: 'ease-in' },
    { opacity: 1, offset: 0.9 },
    { opacity: 0 },
  ], { duration: 5000 }).finished.then(() => notifications.removeChild(notification));
};

const showErrorMessage = (text) => {
  showMessage(text, true);
};

class Modal {
  constructor(title) {
    this.modal = document.querySelector('.brandnestor-modal');
    this.modalContainer = this.modal.querySelector('.modal-container');
    this.modalContent = this.modalContainer.querySelector('.modal-content');

    this.modalContainer.querySelector('.modal-title').innerText = title;
  }

  open() {
    this.modal.classList.add('modal-visible');
    this.modalContainer.animate([
      { transform: 'translateY(-50px)', opacity: 0 },
      { transform: 'translateY(0px)', opacity: 1 },
    ], { duration: 500, easing: 'ease' });

    return new Promise(resolve => {
      this.modalContainer.querySelector('.modal-close').addEventListener('click', () => {
        this.close();
        resolve(false);
      });

      this.modalContainer.querySelector('.modal-ok').addEventListener('click', () => {
        this.close();
        resolve(true);
      });
    });
  }

  close() {
    this.modal.classList.remove('modal-visible');
    this.modalContent.innerHTML = '';
  }

  async confirm(message) {
    const okBtn = this.modalContainer.querySelector('.modal-ok');
    okBtn.disabled = true;

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';

    const label = document.createElement('label');
    label.append(checkbox);
    label.append(message);
    label.addEventListener('change', e => okBtn.disabled = !e.target.checked);

    this.modalContent.append(label);

    return await this.open();
  }
}

export { makeRequest, showMessage, showErrorMessage, Modal };
