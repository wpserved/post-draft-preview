<div class="card">
    <h2 class="title">
        <?php echo esc_html(__('Remove all plugin data', 'pdp')); ?>
    </h2>

    <p>
        <?php echo esc_html(__('You can delete completely all data of the PDP plugin from database. Then all public previews data will be irretrievably lost.', 'pdp')); ?>
    </p>

    <form method="post">
        <input type="hidden" name="setting" value="reset" />
        <button href="#" class="button button--data-remove button-secondary" type="submit"><?php echo esc_html(__('Remove all plugin data', 'pdp')); ?></button>
    </form>
</div>