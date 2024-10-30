<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<table class="form-table">
    <tr>
        <th scope="row"><?= $title; ?></th>
        <td><label for="disable_user"><input name="disable_user" id="disable_user" value="1" <?= $checked; ?> type="checkbox" /> <?= $label; ?></label></td>
    </tr>
</table>