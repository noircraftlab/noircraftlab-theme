<?php
/**
 * template-cases.php — Noircraft Lab Theme
 *
 * Page Template: Cases Hub
 * Displays all WP categories as a visual grid — acts as a portfolio/cases directory.
 *
 * NOTE FOR PHASE 2.4 REWRITE:
 *   Currently this template inherits from Noircraft Lab's "Categories Hub" pattern —
 *   it lists WordPress categories. For Noircraft Lab portfolio, this should
 *   eventually be rewritten to query actual case-study posts (e.g. a custom
 *   query for category 'cases' or a custom post type 'case').
 *   For Phase 2.3 launch — kept as functional fallback with renamed identifiers.
 *
 * USAGE:
 *   1. Upload to /wp-content/themes/noircraftlab-theme/
 *   2. In WP Admin → Pages → Edit the /cases/ page
 *   3. Page Attributes → Template → select "Cases Hub"
 *   4. Save / Update
 *
 * Template Name: Cases Hub
 * Template Post Type: page
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

get_header();

// Polylang: get language-filtered categories if available
$lang_args = [];
if ( function_exists( 'pll_current_language' ) ) {
  // Polylang exposes categories per language via term_language taxonomy.
  // Passing 'lang' to get_categories() is supported by Polylang directly.
  $lang_args['lang'] = pll_current_language( 'slug' );
}

$all_categories = get_categories( array_merge( [
  'hide_empty' => false,
  'orderby'    => 'name',
  'order'      => 'ASC',
  'parent'     => 0,     // top-level only — no sub-categories
  'number'     => 0,     // no limit
], $lang_args ) );

// Accent colours for category cards — cycling fallback palette
// Override by adding a category custom field or ACF option.
$accent_palette = [
  '#D4AF37', // gold
  '#2B6FD4', // blue
  '#C0392B', // red
  '#27AE60', // green
  '#8E44AD', // purple
  '#E67E22', // orange
  '#16A085', // teal
  '#2C3E50', // navy
  '#D35400', // burnt orange
  '#1ABC9C', // emerald
];

// Get page title/content from current page (Polylang serves the right
// language version automatically: "Все категории" / "Browse" / "Категорії")
$page_title    = '';
$page_subtitle = '';
if ( have_posts() ) :
  while ( have_posts() ) : the_post();
    $page_title    = get_the_title();
    $page_subtitle = wp_strip_all_tags( get_the_content() );
  endwhile;
  rewind_posts();
endif;
?>

<style>
/* ── Categories Hub — inline styles ─────────────────────────────────
   Add to style.css if you prefer external management.
   ─────────────────────────────────────────────────────────────────── */

.categories-hub-header {
  padding-block: var(--space-12) var(--space-10);
  text-align: center;
  border-bottom: 1px solid var(--color-border);
  margin-bottom: var(--space-10);
}

.categories-hub-header__eyebrow {
  font-size: var(--text-xs);
  font-weight: var(--weight-bold);
  text-transform: uppercase;
  letter-spacing: 0.14em;
  color: var(--color-gold);
  margin-bottom: var(--space-3);
}

.categories-hub-header__title {
  font-family: var(--font-serif);
  font-size: clamp(var(--text-3xl), 5vw, var(--text-5xl));
  font-weight: var(--weight-bold);
  line-height: 1.1;
  margin-bottom: var(--space-4);
  color: var(--color-text);
}

.categories-hub-header__subtitle {
  font-size: var(--text-lg);
  color: var(--color-text-muted);
  max-width: 560px;
  margin-inline: auto;
  line-height: var(--leading-normal);
}

/* ── Category Grid ────────────────────────────────────────────────── */

.categories-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--space-6);
  margin-bottom: var(--space-16);
}

@media (min-width: 640px) {
  .categories-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .categories-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* ── Category Card ────────────────────────────────────────────────── */

.cat-card {
  display: flex;
  flex-direction: column;
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  text-decoration: none;
  transition: transform var(--transition-base),
              box-shadow var(--transition-base),
              border-color var(--transition-base);
}

.cat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(212, 175, 55, 0.22);
  border-color: var(--color-gold);
}

/* Gradient banner — placeholder for featured image */
.cat-card__banner {
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.cat-card__banner-icon {
  font-size: 2.5rem;
  filter: drop-shadow(0 2px 8px rgba(0,0,0,0.4));
  position: relative;
  z-index: 1;
}

/* Subtle noise overlay on banner — gradient fallback only */
.cat-card__banner::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.12);
}

/* ── Banner variant: category has a real image (Categories Images plugin) ── */

.cat-card__banner--has-image {
  /* background-image injected via inline style="" on the element */
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/*
 * Dark overlay for photo banners — sits between photo and card body.
 * Top→bottom gradient: opaque at top (35%) fading out toward accent stripe.
 * Uses ::before so ::after (gradient fallback overlay) doesn't interfere.
 */
.cat-card__banner--has-image::before {
  content: '';
  position: absolute;
  inset: 0;
  z-index: 1;
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0.45) 0%,
    rgba(0, 0, 0, 0.10) 60%,
    rgba(0, 0, 0, 0.00) 100%
  );
}

/* Suppress the generic ::after noise-overlay on photo banners
   — the ::before already handles contrast */
.cat-card__banner--has-image::after {
  display: none;
}

/* Card body */
.cat-card__body {
  padding: var(--space-5);
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  border-top: 3px solid; /* color set inline via --cat-accent */
  border-top-color: var(--cat-accent, var(--color-gold));
}

.cat-card__name {
  font-family: var(--font-serif);
  font-size: var(--text-xl);
  font-weight: var(--weight-bold);
  color: var(--color-text);
  line-height: var(--leading-tight);
  transition: color var(--transition-fast);
}

.cat-card:hover .cat-card__name {
  color: var(--cat-accent, var(--color-gold));
}

.cat-card__count {
  font-size: var(--text-xs);
  font-weight: var(--weight-semibold);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--cat-accent, var(--color-gold));
}

.cat-card__desc {
  font-size: var(--text-sm);
  color: var(--color-text-muted);
  line-height: var(--leading-normal);
  flex: 1;
  margin: 0;
}

.cat-card__cta {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  font-size: var(--text-xs);
  font-weight: var(--weight-bold);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--cat-accent, var(--color-gold));
  margin-top: var(--space-2);
  transition: gap var(--transition-fast);
}

.cat-card:hover .cat-card__cta {
  gap: var(--space-3);
}

/* Empty state */
.categories-hub-empty {
  text-align: center;
  padding-block: var(--space-20);
  color: var(--color-text-muted);
}
</style>

<div class="container">

  <!-- ═══════════════════════════════════════════════════════════════
       HUB HEADER
       ═══════════════════════════════════════════════════════════════ -->

  <header class="categories-hub-header">

    <div class="categories-hub-header__eyebrow">
      <?php bloginfo( 'name' ); ?>
    </div>

    <h1 class="categories-hub-header__title">
      <?php echo esc_html( $page_title ); ?>
    </h1>

    <?php if ( ! empty( $page_subtitle ) ) : ?>
    <p class="categories-hub-header__subtitle">
      <?php echo esc_html( $page_subtitle ); ?>
    </p>
    <?php endif; ?>

  </header><!-- /.categories-hub-header -->


  <!-- ═══════════════════════════════════════════════════════════════
       CATEGORIES GRID
       ═══════════════════════════════════════════════════════════════ -->

  <?php if ( ! empty( $all_categories ) ) : ?>

  <div class="categories-grid">

    <?php
    // Map category slugs to emoji icons — customise as needed
    $cat_icons = [
      'online-shopping'    => '🛍️',
      'tehnika'            => '📱',
      'finansy'            => '💰',
      'moda'               => '👗',
      'puteshestviya'      => '✈️',
      'krasota-zdorovie'   => '💆',
      'obuchenie'          => '📚',
      'populyarnoe'        => '🔥',
      'internet-magaziny'  => '🏪',
      'avto'               => '🚗',
    ];

    foreach ( $all_categories as $index => $category ) :
      $accent    = $accent_palette[ $index % count( $accent_palette ) ];
      $icon      = isset( $cat_icons[ $category->slug ] ) ? $cat_icons[ $category->slug ] : '📄';
      $cat_url   = get_category_link( $category->term_id );
      $cat_count = (int) $category->count;
      $cat_desc  = category_description( $category->term_id );

      // Build gradient from accent colour — used when no image is set
      $r  = hexdec( substr( $accent, 1, 2 ) );
      $g  = hexdec( substr( $accent, 3, 2 ) );
      $b  = hexdec( substr( $accent, 5, 2 ) );
      $bg_gradient = "linear-gradient(135deg, rgba({$r},{$g},{$b},0.25) 0%, rgba({$r},{$g},{$b},0.08) 100%)";

      // Categories Images plugin (Muhammad Said El Zahlan)
      // z_taxonomy_image_url( $term_id ) → URL string or empty string
      $cat_image_url = ( function_exists( 'z_taxonomy_image_url' ) )
        ? z_taxonomy_image_url( $category->term_id )
        : '';

      $has_cat_image   = ! empty( $cat_image_url );
      $banner_class    = 'cat-card__banner' . ( $has_cat_image ? ' cat-card__banner--has-image' : '' );
      $banner_style    = $has_cat_image
        ? 'background-image: url(' . esc_url( $cat_image_url ) . ');'
        : 'background: ' . esc_attr( $bg_gradient ) . ';';
    ?>

    <a
      href="<?php echo esc_url( $cat_url ); ?>"
      class="cat-card"
      style="--cat-accent: <?php echo esc_attr( $accent ); ?>;"
      aria-label="<?php echo esc_attr( $category->name ); ?>"
    >
      <!-- Banner: real photo (plugin) OR gradient + emoji fallback -->
      <div
        class="<?php echo esc_attr( $banner_class ); ?>"
        style="<?php echo $banner_style; // phpcs:ignore WordPress.Security.EscapeOutput — built from esc_url / esc_attr above ?>"
        aria-hidden="true"
      >
        <?php if ( ! $has_cat_image ) : ?>
        <span class="cat-card__banner-icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
        <?php endif; ?>
      </div>

      <!-- Card body -->
      <div class="cat-card__body">

        <div class="cat-card__count">
          <?php
          printf(
            /* translators: %d: number of posts */
            esc_html( _n( '%d post', '%d posts', $cat_count, 'noircraftlab' ) ),
            $cat_count
          );
          ?>
        </div>

        <h2 class="cat-card__name">
          <?php echo esc_html( $category->name ); ?>
        </h2>

        <?php if ( $cat_desc ) : ?>
        <p class="cat-card__desc">
          <?php echo esc_html( wp_strip_all_tags( $cat_desc ) ); ?>
        </p>
        <?php endif; ?>

        <span class="cat-card__cta">
          <?php esc_html_e( 'Читать', 'noircraftlab' ); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>

      </div><!-- /.cat-card__body -->

    </a><!-- /.cat-card -->

    <?php endforeach; ?>

  </div><!-- /.categories-grid -->

  <?php else : ?>

  <div class="categories-hub-empty">
    <p><?php esc_html_e( 'No categories found.', 'noircraftlab' ); ?></p>
  </div>

  <?php endif; ?>

</div><!-- /.container -->

<?php get_footer(); ?>
