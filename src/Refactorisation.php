<!--header -->
<?php if (!empty($_SESSION['flash'])) : ?>
    <?php foreach ($_SESSION['flash'] as $key => $value) : ?>
        <div class="message <?= $value['status'] ?>">
            <span><?= $value['message'] ?></<span>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- création d'une fonction -->
<?php
/**
 * Display a global message
 * 
 * @param string $content
 */

function flash()
{
    if (isset($_SESSION['flash'])) :
        foreach ($_SESSION['flash'] as $key => $value) :
            $content = '<div class="message ' .  $value['status'] . '">';
            $content .= $value['message'];
            $content .= '</div>';
        endforeach;
        unset($_SESSION['flash']);
        return $content;
    endif;
}
?>