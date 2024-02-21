export default class Post {
  constructor() {

    if ('undefined' === typeof window.pdp.post) {
      return;
    }

    this.previewUrl = window.pdp.post.previewUrl || '#';
    this.status = window.pdp.post.status || 0;
    this.changeStatusNonce = window.pdp.post.changeStatusNonce || '';
    this.getStatusNonce = window.pdp.post.getStatusNonce || '';
    this.id = window.pdp.post.id || 0;

    this.label = this.getLabel(this.status);

    $(document).on('pdp_gutenberg_loaded', () => {
      this.addPreviewButtonForGutenberg();
    });

    $(document).on('pdp_editor_loaded', () => {
      this.addButtonForClassicEditor();
    });

    this.initGutenbergLoadEvent();
    this.initEditorLoadEvent();

    this.initClickAction();
  }

  initClickAction() {
    $('body').on('click', '.button--pdp-change-status', (e) => {
      e.preventDefault();

      $.ajax({
        url: pdp.ajaxurl,
        type: "post",
        dataType: "json",
        data: {
            action: "pdp-change-status",
            id: this.id,
            confirmation: 1,
            nonce: this.changeStatusNonce
        }
      })
      .success(res => {
        const previewUrl = $('.pdp-preview-url');

        $('.button--pdp-change-status').html(this.getLabel(res.data.status));

        previewUrl.attr('href', res.data.previewUrl);
        previewUrl.find('input').val(res.data.previewUrl);

        if(1 === res.data.status) {
          previewUrl.show();
        } else {
          previewUrl.hide();
        }
      });
    });
  }

  getLabel(status) {
    return (status ? '&#10007; Disable' : '&#10003; Enable') + ' PDP';
  }

  addPreviewButtonForGutenberg() {
    const slot = $('button.block-editor-post-preview__button-toggle');
    const previewUrl = $(`<a class="pdp-preview-url" href="${this.previewUrl}" target="_blank"><input type="text" value="${this.previewUrl}" readonly></a>`);

    if (slot.parent()) {
      $(`<a class="components-button is-secondary button--pdp-change-status" href="#">${this.label}</a>`).insertBefore(slot.parent());
    }

    if (1 !== this.status) {
      previewUrl.hide();
    }

    previewUrl.insertBefore(slot.parent());
  }

  addButtonForClassicEditor() {
    const slot = $('.button#post-preview');
    const container = $('<div>');
    const  previewUrl = $(`<a class="pdp-preview-url" href="${this.previewUrl}" target="_blank"><input type="text" value="${this.previewUrl}" readonly></a>`);

    if (slot) {
      $(`<a class="preview button is-pdp-preview button--pdp-change-status" href="#">${this.label}</a>`).appendTo(container);
    }

    if (1 !== this.status) {
      previewUrl.hide();
    }

    previewUrl.appendTo(container);

    container.insertAfter(slot);
  }

  initGutenbergLoadEvent(timeout = 4000) {
    if ($('#editor').length > 0) {
      const interval = setInterval(() => {
        timeout -= 250;
        if (
          $('.wp-block-post-title').length > 0 || $('#post-title-0').length > 0 ||
          $('iframe[name=editor-canvas]').contents().find('.wp-block-post-title')
        ) {
          $(document).trigger('pdp_gutenberg_loaded');
          clearInterval(interval);
        }

        if (timeout <= 0) {
          $(document).trigger('pdp_gutenberg_notloaded');
          clearInterval(interval);
        }
      }, 250);
    }
  }

  initEditorLoadEvent() {
    if ($('#postdivrich').length > 0) {
      $(document).trigger('pdp_editor_loaded');
    }
  }
}
