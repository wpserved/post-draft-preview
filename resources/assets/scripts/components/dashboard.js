export default class Dashboard {
  constructor() {
    if ('undefined' === typeof window.pdp.dashboard) {
      return;
    }

    if (this.issetButton('data-remove')) {
      this.initDataAction('data-remove');
    }

    if (this.issetButton('data-reset')) {
      this.initDataAction('data-reset');
    }

    if (this.issetButton('data-autogenerate')) {
      this.initDataAction('data-autogenerate');
    }
  }

  issetButton(button) {
    if ('undefined' === typeof button) {
      return false;
    }

    return $(`.pdp-dashboard .button--${button}`).length > 0;
  }

  initDataAction(button) {
    const buttonObj = $('.pdp-dashboard .button--' + button);
    buttonObj.on('click', (e) => {
      e.preventDefault();

      if (! confirm('Are you sure?')) {
        return;
      }

      let data = this.getForm(buttonObj);

      data.append('action', "pdp-" + button);
      data.append('confirmation', 1),
      data.append('nonce', this.getNonceByButton(button));

      $.ajax({
        url: pdp.ajaxurl,
        type: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        data: data ?? {},
      })
      .success(response => {
        this.showMessage(response, buttonObj);
      })
      .fail((jqXHR, textStatus) => {
        console.error(textStatus );
      });
    });
  }

  getNonceByButton(button) {
    if('undefined' === typeof button) {
      return '';
    }

    let splitted = button.split('-');

    if(splitted.length < 2) {
      return '';
    }

    let nonceField = splitted[1] + 'Nonce';

    if('undefined' === typeof pdp.dashboard.data[nonceField]) {
      return '';
    }

    return pdp.dashboard.data[nonceField];
  }

  getForm(buttonObject) {
    const form = buttonObject.parent('form');

    if (0 >= form.length) {
      return new FormData();
    }

    return new FormData(form[0]);
  }

  showMessage(response, buttonObj) {
    const notice = $('<div class="notice notice-' + (response.success ? 'success' : 'warning') + ' is-dismissible"><p>' + response.data.message + '</p></div>').hide();
    buttonObj.after(notice);
    notice.fadeIn('fast');
    setTimeout(function() {
      notice.fadeOut('fast');
    }, 5000);
  }
}
