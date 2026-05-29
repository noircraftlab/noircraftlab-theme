<?php
/**
 * footer.php — Noircraft Lab Theme
 *
 * Closes <main> opened in header.php, renders the full site footer:
 * brand column, nav columns (x3), social links, legal bottom bar.
 *
 * @package NoircraftLab
 * @version 1.0.0
 */
?>

</main><!-- #main-content -->

<!-- ═══════════════════════════════════════════════════════════════════
     SITE FOOTER
     ══════════════════════════════════════════════════════════════════ -->
<footer
  id="site-footer"
  class="site-footer"
  role="contentinfo"
  itemscope
  itemtype="https://schema.org/WPFooter"
>
  <div class="container">

    <!-- ── Footer Grid: Brand + 3 Nav Columns ─────────────────────── -->
    <div class="footer-grid">

      <!-- Column 1: Brand ────────────────────────────────────────── -->
      <div class="footer-brand">
        <?php
        /* SVG logo via noircraftlab_logo() — same helper as header.php */
        echo noircraftlab_logo( 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput
        ?>

        <p>
          <?php echo esc_html(
            get_bloginfo( 'description' )
              ?: __( 'Editorial WordPress builds. Multilingual, SEO-ready, performance-first.', 'noircraftlab' )
          ); ?>
        </p>

        <!-- Social Links -->
        <?php
        /*
         * Social URLs stored via Customizer or ACF Options Page.
         * Options: noircraftlab_social_telegram | _instagram | _twitter | _youtube
         * Link is hidden if option is empty or not set.
         */
        $socials = [
          'telegram'  => [
            'url'   => get_option( 'noircraftlab_social_telegram', '' ),
            'label' => __( 'Telegram', 'noircraftlab' ),
            'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>',
          ],
          'instagram' => [
            'url'   => get_option( 'noircraftlab_social_instagram', '' ),
            'label' => __( 'Instagram', 'noircraftlab' ),
            'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>',
          ],
          'twitter'   => [
            'url'   => get_option( 'noircraftlab_social_twitter', '' ),
            'label' => __( 'X (Twitter)', 'noircraftlab' ),
            'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
          ],
          'youtube'   => [
            'url'   => get_option( 'noircraftlab_social_youtube', '' ),
            'label' => __( 'YouTube', 'noircraftlab' ),
            'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
          ],
        ];

        $has_socials = array_filter( array_column( $socials, 'url' ) );
        if ( $has_socials ) : ?>
        <div class="footer-social" aria-label="<?php esc_attr_e( 'Social media links', 'noircraftlab' ); ?>">
          <?php foreach ( $socials as $s ) :
            if ( empty( $s['url'] ) ) continue; ?>
            <a
              href="<?php echo esc_url( $s['url'] ); ?>"
              class="footer-social-link"
              aria-label="<?php echo esc_attr( $s['label'] ); ?>"
              target="_blank"
              rel="noopener noreferrer"
            ><?php echo $s['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div><!-- /.footer-brand -->

      <!-- Column 2: Sections ─────────────────────────────────────── -->
      <div class="footer-col">
        <h3 class="footer-heading"><?php esc_html_e( 'Sections', 'noircraftlab' ); ?></h3>
        <nav aria-label="<?php esc_attr_e( 'Footer sections', 'noircraftlab' ); ?>">
          <?php if ( has_nav_menu( 'footer-sections' ) ) :
            wp_nav_menu( [
              'theme_location' => 'footer-sections',
              'container'      => false,
              'fallback_cb'    => false,
              'depth'          => 1,
              'items_wrap'     => '<ul class="footer-links">%3$s</ul>',
            ] );
          else : ?>
            <?php
            /* No menu assigned to "Footer — Sections" location. */
            ?>
          <?php endif; ?>
        </nav>
      </div><!-- /.footer-col -->

      <!-- Column 3: Company ──────────────────────────────────────── -->
      <div class="footer-col">
        <h3 class="footer-heading"><?php esc_html_e( 'Company', 'noircraftlab' ); ?></h3>
        <nav aria-label="<?php esc_attr_e( 'Footer company links', 'noircraftlab' ); ?>">
          <?php if ( has_nav_menu( 'footer-company' ) ) :
            wp_nav_menu( [
              'theme_location' => 'footer-company',
              'container'      => false,
              'fallback_cb'    => false,
              'depth'          => 1,
              'items_wrap'     => '<ul class="footer-links">%3$s</ul>',
            ] );
          else : ?>
            <?php
            /* No menu assigned to "Footer — Company" location. */
            ?>
          <?php endif; ?>
        </nav>
      </div><!-- /.footer-col -->

      <!-- Column 4: Legal ────────────────────────────────────────── -->
      <div class="footer-col">
        <h3 class="footer-heading"><?php esc_html_e( 'Legal', 'noircraftlab' ); ?></h3>
        <nav aria-label="<?php esc_attr_e( 'Footer legal links', 'noircraftlab' ); ?>">
          <?php if ( has_nav_menu( 'footer-legal' ) ) :
            wp_nav_menu( [
              'theme_location' => 'footer-legal',
              'container'      => false,
              'fallback_cb'    => false,
              'depth'          => 1,
              'items_wrap'     => '<ul class="footer-links">%3$s</ul>',
            ] );
          else : ?>
            <?php
            /* No menu assigned to "Footer — Legal" location. */
            ?>
          <?php endif; ?>
        </nav>
      </div><!-- /.footer-col -->

    </div><!-- /.footer-grid -->

    <!-- ── Bottom Bar ─────────────────────────────────────────────── -->
    <div class="footer-bottom">

      <p>
        &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
        <?php esc_html_e( 'All rights reserved.', 'noircraftlab' ); ?>
      </p>

      <nav class="lang-switcher" aria-label="<?php esc_attr_e( 'Language switcher', 'noircraftlab' ); ?>">
        <?php if ( function_exists( 'pll_the_languages' ) ) :
          $footer_langs = pll_the_languages( [ 'show_flags' => 0, 'display_names_as' => 'slug', 'hide_if_empty' => 0, 'raw' => 1 ] );
          foreach ( (array) $footer_langs as $lang ) : ?>
            <a
              href="<?php echo esc_url( $lang['url'] ); ?>"
              hreflang="<?php echo esc_attr( $lang['locale'] ); ?>"
              <?php echo $lang['current_lang'] ? 'class="active" aria-current="page"' : ''; ?>
            ><?php echo esc_html( strtoupper( $lang['slug'] ) ); ?></a>
          <?php endforeach;
        else : ?>
          <a href="/ru/" hreflang="ru">RU</a>
          <a href="/en/" hreflang="en">EN</a>
          <a href="/ua/" hreflang="uk">UA</a>
        <?php endif; ?>
      </nav>

      <p><?php printf( esc_html__( 'Built with %s', 'noircraftlab' ), '<a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a>' ); ?></p>

    </div><!-- /.footer-bottom -->

  </div><!-- /.container -->
</footer><!-- #site-footer -->

<?php wp_footer(); ?>

</body>
</html>
