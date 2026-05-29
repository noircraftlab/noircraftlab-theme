<?php
/**
 * page.php — Noircraft Lab Theme
 *
 * Default template for static WordPress pages (About, Contacts,
 * Privacy Policy, etc.). Pages with a custom Page Template
 * (Categories Hub, Partners Hub) are NOT affected — WP loads
 * their own template file first.
 *
 * CSS reused (no new rules needed):
 *   .archive-header, .archive-header__*, .article-wrap,
 *   .article-body, .page-links, .page-links__*, .container
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main page-template-default">

  <?php while ( have_posts() ) : the_post(); ?>

  <div class="container">

    <!-- ── Page Header ───────────────────────────────────────────── -->
    <header class="archive-header">

      <div class="archive-header__eyebrow">
        <span class="archive-header__label">
          <?php esc_html_e( 'Page', 'noircraftlab' ); ?>
        </span>
      </div>

      <h1 class="archive-header__title">
        <?php the_title(); ?>
      </h1>

    </header><!-- /.archive-header -->


    <!-- ── Page Content ──────────────────────────────────────────── -->
    <!--
      .article-wrap   → max-width 740px, margin: 0 auto (single.php pattern)
      .article-body   → Georgia 18px, line-height 1.8, full typography rules
    -->
    <div class="article-wrap">

      <div class="article-body entry-content">

        <?php
        the_content(
          sprintf(
            wp_kses(
              /* translators: %s: page title */
              __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'noircraftlab' ),
              [ 'span' => [ 'class' => [] ] ]
            ),
            get_the_title()
          )
        );

        /*
         * Multi-page support — respects <!--nextpage--> blocks
         * inserted via the block editor.
         */
        wp_link_pages( [
          'before'      => '<nav class="page-links" aria-label="' . esc_attr__( 'Page sections', 'noircraftlab' ) . '"><span class="page-links__label">' . esc_html__( 'Pages:', 'noircraftlab' ) . '</span>',
          'after'       => '</nav>',
          'link_before' => '<span class="page-links__item">',
          'link_after'  => '</span>',
        ] );
        ?>

      </div><!-- /.article-body -->

    </div><!-- /.article-wrap -->

  </div><!-- /.container -->

  <?php endwhile; ?>

</main><!-- .site-main -->

<?php get_footer();
