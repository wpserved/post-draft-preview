<div class="pdp-dashboard wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <?php $this->dataReset->view(); ?>
    <?php $this->dataRemove->view(); ?>
    <?php $this->autogenerate->view(); ?>
</div>
