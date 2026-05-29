<?php
/**
 * 404.php — Noircraft Lab Theme
 *
 * Custom 404 (Not Found) template.
 * Editorial Noir tone — minimal, polite, with a quick-recovery CTA back to home.
 *
 * Template hierarchy: 404.php → index.php (fallback).
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main page-404">

  <div class="container">

    <article class="page-404__wrap" role="region" aria-labelledby="page-404-title">

      <header class="page-404__header">

        <div class="page-404__eyebrow">
          <?php esc_html_e( 'Error', 'noircraftlab' ); ?>
        </div>

        <h1 id="page-404-title" class="page-404__code">404</h1>

        <p class="page-404__title">
          <?php esc_html_e( 'This page is off the editorial calendar.', 'noircraftlab' ); ?>
        </p>

        <p class="page-404__desc">
          <?php esc_html_e( 'The page you requested could not be found. It may have moved, been renamed, or never existed at this address.', 'noircraftlab' ); ?>
        </p>

      </header><!-- /.page-404__header -->


      <!-- ── Quick recovery options ─────────────────────────────────── -->
      <div class="page-404__actions">

        <a
          href="<?php echo esc_url( home_url( '/' ) ); ?>"
          class="page-404__cta page-404__cta--primary"
        >
          <?php esc_html_e( 'Back to home', 'noircraftlab' ); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>

        <!-- Inline search form — uses theme's standard /?s=  route -->
        <form
          role="search"
          method="get"
          class="page-404__search"
          action="<?php echo esc_url( home_url( '/' ) ); ?>"
        >
          <label for="page-404-search" class="visually-hidden">
            <?php esc_html_e( 'What are you looking for?', 'noircraftlab' ); ?>
          </label>
          <input
            type="search"
            id="page-404-search"
            name="s"
            placeholder="<?php esc_attr_e( 'What are you looking for?', 'noircraftlab' ); ?>"
            autocomplete="off"
          >
          <button type="submit" aria-label="<?php esc_attr_e( 'Search', 'noircraftlab' ); ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
              <circle cx="11" cy="11" r="7"/>
              <path d="M21 21l-4.35-4.35"/>
            </svg>
          </button>
        </form>

      </div><!-- /.page-404__actions -->


      <!-- ── Latest articles (3) — gives the visitor something to do ── -->
      <?php
      $latest = new WP_Query( [
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'no_found_rows'       => true,
      ] );

      if ( $latest->have_posts() ) : ?>

        <section class="page-404__latest" aria-labelledby="page-404-latest-title">

          <h2 id="page-404-latest-title" class="page-404__latest-title">
            <?php esc_html_e( 'Recent articles', 'noircraftlab' ); ?>
          </h2>

          <ul class="page-404__latest-list">
            <?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
              <li class="page-404__latest-item">
                <a href="<?php the_permalink(); ?>" class="page-404__latest-link">
                  <span class="page-404__latest-date">
                    <?php echo esc_html( get_the_date() ); ?>
                  </span>
                  <span class="page-404__latest-headline">
                    <?php the_title(); ?>
                  </span>
                </a>
              </li>
            <?php endwhile; ?>
          </ul>

        </section>

      <?php
      wp_reset_postdata();
      endif;
      ?>

    </article><!-- /.page-404__wrap -->

  </div><!-- /.container -->

</main><!-- .site-main -->

<style>
/* ──────────────────────────────────────────────────────────────────
   404 page — inline scoped styles
   Uses theme CSS variables; mirrors Noir Editorial conventions.
   ────────────────────────────────────────────────────────────────── */

.page-404 {
  padding-block: var(--space-12) var(--space-16);
}

.page-404__wrap {
  max-width: 640px;
  margin-inline: auto;
  text-align: center;
}

.page-404__header {
  margin-bottom: var(--space-10);
}

.page-404__eyebrow {
  font-size: var(--text-xs);
  font-weight: var(--weight-bold);
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--color-gold);
  margin-bottom: var(--space-4);
}

.page-404__code {
  font-family: var(--font-serif);
  font-size: clamp(96px, 18vw, 180px);
  font-weight: var(--weight-bold);
  line-height: 0.9;
  color: var(--color-text);
  margin: 0 0 var(--space-4);
  letter-spacing: -0.04em;
}

.page-404__title {
  font-family: var(--font-serif);
  font-size: clamp(var(--text-xl), 3vw, var(--text-3xl));
  font-weight: var(--weight-bold);
  color: var(--color-text);
  line-height: var(--leading-tight);
  margin: 0 0 var(--space-4);
}

.page-404__desc {
  font-size: var(--text-base);
  color: var(--color-text-muted);
  line-height: var(--leading-normal);
  margin: 0;
}

.page-404__actions {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
  margin-bottom: var(--space-12);
}

.page-404__cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  font-size: var(--text-sm);
  font-weight: var(--weight-bold);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  text-decoration: none;
  padding: var(--space-3) var(--space-6);
  border: 1px solid var(--color-gold);
  color: var(--color-gold);
  transition: all var(--transition-fast);
}

.page-404__cta:hover,
.page-404__cta:focus-visible {
  background: var(--color-gold);
  color: var(--color-bg);
}

.page-404__search {
  display: flex;
  max-width: 420px;
  margin-inline: auto;
  width: 100%;
}

.page-404__search input[type="search"] {
  flex: 1;
  padding: var(--space-3) var(--space-4);
  background: transparent;
  border: 1px solid var(--color-border);
  border-right: none;
  color: var(--color-text);
  font-family: inherit;
  font-size: var(--text-sm);
}

.page-404__search input[type="search"]:focus {
  outline: none;
  border-color: var(--color-gold);
}

.page-404__search button {
  padding: 0 var(--space-4);
  background: transparent;
  border: 1px solid var(--color-border);
  color: var(--color-text-muted);
  cursor: pointer;
  transition: color var(--transition-fast), border-color var(--transition-fast);
}

.page-404__search button:hover,
.page-404__search button:focus-visible {
  color: var(--color-gold);
  border-color: var(--color-gold);
}

.page-404__latest {
  border-top: 1px solid var(--color-border);
  padding-top: var(--space-8);
  text-align: left;
}

.page-404__latest-title {
  font-family: var(--font-serif);
  font-size: var(--text-lg);
  font-weight: var(--weight-bold);
  text-align: center;
  margin-bottom: var(--space-5);
  color: var(--color-text);
}

.page-404__latest-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.page-404__latest-item + .page-404__latest-item {
  border-top: 1px solid var(--color-border);
}

.page-404__latest-link {
  display: flex;
  align-items: baseline;
  gap: var(--space-4);
  padding: var(--space-4) 0;
  text-decoration: none;
  transition: color var(--transition-fast);
}

.page-404__latest-link:hover .page-404__latest-headline,
.page-404__latest-link:focus-visible .page-404__latest-headline {
  color: var(--color-gold);
}

.page-404__latest-date {
  flex-shrink: 0;
  font-size: var(--text-xs);
  font-weight: var(--weight-semibold);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--color-text-muted);
  min-width: 80px;
}

.page-404__latest-headline {
  font-family: var(--font-serif);
  font-size: var(--text-base);
  font-weight: var(--weight-semibold);
  color: var(--color-text);
  line-height: var(--leading-tight);
  transition: color var(--transition-fast);
}

@media (min-width: 640px) {
  .page-404__actions {
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }
  .page-404__search {
    margin-inline: 0;
  }
}
</style>

<?php get_footer(); ?>
