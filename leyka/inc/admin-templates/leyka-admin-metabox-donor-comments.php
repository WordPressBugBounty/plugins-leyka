<?php if( !defined('WPINC') ) die;
/** Admin Donor's info page template */

/** @var $this Leyka_Admin_Setup */

try {
    $donor = new Leyka_Donor(absint($_GET['donor']));
} catch(Exception $e) {
    wp_die(wp_kses_post($e->getMessage()));
}?>

<?php wp_nonce_field('leyka_save_editable_str', 'leyka_save_editable_str_nonce');?>
<?php wp_nonce_field('leyka_delete_donor_comment', 'leyka_delete_donor_comment_nonce');?>
<?php wp_nonce_field('leyka_add_donor_comment', 'leyka_add_donor_comment_nonce');?>

<div class="add-donor-comment">
    <a href="#" class="add-donor-comment-link"><?php esc_html_e('Add a comment', 'leyka');?></a>
    <form class="new-donor-comment-form" method="post">

        <label for="donor-comment-field"><?php esc_html_e('Comment text', 'leyka');?></label>
        <input type="text" id="donor-comment-field" name="donor-comment" value="">

        <input type="submit" value="<?php esc_html_e('Add the comment', 'leyka');?>">
        
        <div class="loading-indicator-wrap">
            <div class="loader-wrap"><span class="leyka-loader xxs"></span></div>
            <img class="ok-icon" src="<?php echo esc_url( LEYKA_PLUGIN_BASE_URL );?>img/dashboard/icon-check.svg" alt="">
        </div>
        
    </form>
<!--    --><?php //$donor->add_comment('Тест коммента к донору! '.rand(0, 100000));?>
</div>

<div class="no-comments" style="<?php if($donor->comments_exist()){?>display: none;<?php }?>"><?php esc_html_e('No comments about this donor yet', 'leyka');?></div>

<table class="donor-comments donor-info-table" style="<?php if(!$donor->comments_exist()){?>display: none;<?php }?>">
    <thead>
        <tr>
            <th><?php esc_html_e('Date', 'leyka');?></th>
            <th><?php esc_html_e('Comment', 'leyka');?></th>
            <th><?php esc_html_e('Author', 'leyka');?></th>
            <th><?php esc_html_e('Change', 'leyka');?></th>
            <th><?php esc_html_e('Delete', 'leyka');?></th>
        </tr>
    </thead>
    <tbody>

    <?php $comments = array_merge([0 => ['date' => '', 'text' => '', 'author_name' => '']], $donor->get_comments());

    foreach($comments as $comment_id => $comment) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo leyka_admin_get_donor_comment_table_row($comment_id, $comment);
    }?>
    </tbody>
</table>