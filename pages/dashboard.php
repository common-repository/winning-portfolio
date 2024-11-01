<?php
    /**
     * Custom about page
     */
    
    ?>
<div class="wrap about-wrap">

<h1><?php esc_html_e( 'Winning Portfolio', 'wpf'); ?></h1>

<div class="about-text">
<?php esc_html_e('Winning Portfolio is Very Flexibile and simple to use Portfolios plugin, compatible with King Composer and Visual Composer', 'wpf' ); ?>
</div>

<div class="about-links">
    <a href="http://pressfore.com/documentation/winning-portfolio/" target="_blank"><i class="dashicons dashicons-sos"></i> <?php esc_html_e('Support', 'wpf'); ?> </a>
    <a href="http://pressfore.com/documentation/winning-portfolio/" target="_blank"><i class="dashicons dashicons-book"></i> <?php esc_html_e('Documentation', 'wpf'); ?></a>
    <a href="edit.php?post_type=wpf-portfolio&page=addons" target="_blank"><i class="dashicons dashicons-cart"></i> <?php esc_html_e('Addons', 'wpf'); ?></a>
    <a href="http://pressfore.com/feature-proposal/" target="_blank"><i class="dashicons dashicons-welcome-add-page"></i> <?php esc_html_e(' Propose a feature', 'wpf'); ?></a>
</div>

<div class="changelog">
<h2><?php esc_html_e( 'What\'s New In 1.1', 'wpf' ); ?></h2>
<ul class="new">
    <li>
    <h3><?php  esc_html_e('New Hovers', 'wpf'); ?></h3>
        <p><?php esc_html_e('With this update we included 2 more hover styles and improve existing one', 'wpf'); ?></p>
    </li>
    <li>
        <h3><?php  esc_html_e('New Pages', 'wpf'); ?></h3>
        <p><?php esc_html_e('We have included 3 new pages - Licenses, About and Addons', 'wpf'); ?></p>
    </li>
</ul>

<div class="feature-section images-stagger-right">
    <h3 class="big"><?php esc_html_e( 'Features', 'wpf' ); ?></h3>
    <h4><?php _e( 'Easy To Use' ); ?></h4>
    <p><?php _e( 'Winning Portfolio is very easy to use, with it\'s native user interface you can manage to create beautiful portfolios in few minutes', 'wpf' ); ?></p>

    <h4><?php esc_html_e( 'Customization', 'wpf' ); ?></h4>
    <p><?php esc_html_e( 'You can modify settings easily with just a few clicks of the mouse.', 'wpf' ); ?></p>
</div>

<div>
    <h4 id="adn" style="color: green"><?php _e( 'Addons' ); ?></h4>
    <p><?php esc_html_e('If you want even more control and options, you can extend Winning Portfolio with available addons.', 'wpf') ?></p>
    <a href="edit.php?post_type=wpf-portfolio&page=addons" class="learn-more">Learn More</a>
</div>
</div>

</div>