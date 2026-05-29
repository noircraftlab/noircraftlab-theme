<?php
/**
 * index.php — Noircraft Lab Theme
 *
 * Homepage / blog index template.
 * Sections (in order):
 *   1. Hero Block     — 1 featured post (60%) + 2 side posts (40%)
 *   2. Category Strip — horizontal scrollable category pills
 *   3. Latest Posts   — 3-column article grid
 *
 * Template hierarchy: front-page.php → home.php → index.php
 * For a dedicated static front page, duplicate as front-page.php.
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

get_header();
?>

<div class="container">

  <!-- ═══════════════════════════════════════════════════════════════
       SECTION 1: HERO BLOCK
       Layout: .hero-layout → .hero-main (60%) + 2× .hero-side (40%)
       Query: 3 most recent posts. Sticky post bubbles to top.
       ═══════════════════════════════════════════════════════════════ -->

  <?php
  /*
   * Hero query — 3 posts, sticky first.
   * Slice: post[0] → hero-main | posts[1-2] → hero-side.
   * no_found_rows = true: skip COUNT(*) SQL — we don't paginate heroes.
   */
  $hero_query = new WP_Query( [
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 0,
    'no_found_rows'       => true,
  ] );

  if ( $hero_query->have_posts() ) :
    $hero_posts = $hero_query->posts; // array of WP_Post
  ?>

  <section
    class="hero-section"
    aria-label="<?php esc_attr_e( 'Featured stories', 'noircraftlab' ); ?>"
  >
    <div class="hero-layout">

      <?php
      /* ── Hero Main Card (60%) ─────────────────────────────────── */
      $hero_main = $hero_posts[0];
      setup_postdata( $GLOBALS['post'] = $hero_main ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
      ?>

      <article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'hero-main card card--hero' ); ?>
        itemscope
        itemtype="https://schema.org/NewsArticle"
      >
        <!-- Thumbnail -->
        <a
          href="<?php the_permalink(); ?>"
          class="card-image-wrap card-image-wrap--hero"
          tabindex="-1"
          aria-hidden="true"
        >
          <?php if ( has_post_thumbnail() ) :
            the_post_thumbnail( 'noircraftlab-hero', [
              'alt'      => '',
              'loading'  => 'eager', // Hero = LCP image — never lazy-load
              'itemprop' => 'image',
            ] );
          else : ?>
            <div class="card-image-placeholder" aria-hidden="true"></div>
          <?php endif; ?>

          <span class="card-category">
            <?php
            $cats = get_the_category();
            echo $cats ? esc_html( $cats[0]->name ) : esc_html__( 'News', 'noircraftlab' );
            ?>
          </span>
        </a><!-- /.card-image-wrap -->

        <div class="card-body card-body--hero">
          <h2 class="card-title card-title--hero" itemprop="headline">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>

          <p class="card-excerpt" itemprop="description">
            <?php echo wp_trim_words( get_the_excerpt(), 28, '&hellip;' ); ?>
          </p>

          <div class="card-meta">
            <span itemprop="author" itemscope itemtype="https://schema.org/Person">
              <span itemprop="name"><?php the_author(); ?></span>
            </span>
            <span class="card-meta-dot" aria-hidden="true">·</span>
            <time
              datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"
              itemprop="datePublished"
            ><?php echo esc_html( get_the_date() ); ?></time>
            <span class="card-meta-dot" aria-hidden="true">·</span>
            <span><?php echo esc_html( noircraftlab_reading_time() ); ?></span>
          </div>

          <meta itemprop="url" content="<?php the_permalink(); ?>">
        </div><!-- /.card-body -->

      </article><!-- .hero-main -->

      <?php
      /* ── Hero Side Cards (40%) — posts 1 & 2 ─────────────────── */
      $hero_sides = array_slice( $hero_posts, 1, 2 );

      foreach ( $hero_sides as $side_post ) :
        setup_postdata( $GLOBALS['post'] = $side_post ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
      ?>

      <article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'hero-side card card--side' ); ?>
        itemscope
        itemtype="https://schema.org/NewsArticle"
      >
        <a
          href="<?php the_permalink(); ?>"
          class="card-image-wrap"
          tabindex="-1"
          aria-hidden="true"
        >
          <?php if ( has_post_thumbnail() ) :
            the_post_thumbnail( 'noircraftlab-card', [
              'alt'      => '',
              'loading'  => 'eager',
              'itemprop' => 'image',
            ] );
          else : ?>
            <div class="card-image-placeholder" aria-hidden="true"></div>
          <?php endif; ?>

          <span class="card-category">
            <?php
            $cats = get_the_category();
            echo $cats ? esc_html( $cats[0]->name ) : esc_html__( 'News', 'noircraftlab' );
            ?>
          </span>
        </a>

        <div class="card-body">
          <h3 class="card-title" itemprop="headline">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>

          <div class="card-meta">
            <time
              datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"
              itemprop="datePublished"
            ><?php echo esc_html( get_the_date() ); ?></time>
            <span class="card-meta-dot" aria-hidden="true">·</span>
            <span><?php echo esc_html( noircraftlab_reading_time() ); ?></span>
          </div>

          <meta itemprop="url" content="<?php the_permalink(); ?>">
        </div><!-- /.card-body -->

      </article><!-- .hero-side -->

      <?php endforeach; ?>

    </div><!-- /.hero-layout -->
  </section><!-- .hero-section -->

  <?php
  wp_reset_postdata();
  endif; // end hero query
  ?>


  <!-- ═══════════════════════════════════════════════════════════════
       SECTION 2: CATEGORY STRIP
       Horizontal scrollable pill row of top-level categories.
       Active pill = current archive category.
       ═══════════════════════════════════════════════════════════════ -->

  <?php
  $strip_cats = get_categories( [
    'orderby'    => 'count',
    'order'      => 'DESC',
    'hide_empty' => true,
    'parent'     => 0,
    'number'     => 12,
  ] );

  if ( ! empty( $strip_cats ) ) :
    $current_cat_id = is_category() ? get_queried_object_id() : 0;

    /* ── Categories hub link — language-aware via Polylang ─────────
       RU: page ID 2598 (/categories/), EN: 2912 (/en/browse/),
       UK: 2922 (/uk/kategorii/). pll_get_post() returns the matching
       translation ID for the current language; fallback to RU. */
    $hub_id_ru = 2598;
    $hub_id    = function_exists( 'pll_get_post' )
      ? ( pll_get_post( $hub_id_ru ) ?: $hub_id_ru )
      : $hub_id_ru;
    $hub_url   = get_permalink( $hub_id );
    $is_on_hub = is_page( $hub_id );
  ?>

  <nav
    class="category-strip"
    aria-label="<?php esc_attr_e( 'Browse by section', 'noircraftlab' ); ?>"
  >
    <div class="category-strip__inner">

      <a
        href="<?php echo esc_url( $hub_url ); ?>"
        class="category-pill<?php echo $is_on_hub ? ' category-pill--active' : ''; ?>"
        <?php echo $is_on_hub ? 'aria-current="page"' : ''; ?>
      ><?php esc_html_e( 'Sections', 'noircraftlab' ); ?></a>

      <?php foreach ( $strip_cats as $cat ) : ?>
        <a
          href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
          class="category-pill<?php echo ( (int) $current_cat_id === (int) $cat->term_id ) ? ' category-pill--active' : ''; ?>"
          <?php echo ( (int) $current_cat_id === (int) $cat->term_id ) ? 'aria-current="page"' : ''; ?>
        ><?php echo esc_html( $cat->name ); ?></a>
      <?php endforeach; ?>

    </div><!-- /.category-strip__inner -->
  </nav><!-- .category-strip -->

  <?php endif; ?>


  <!-- ═══════════════════════════════════════════════════════════════
       SECTION 3: LATEST ARTICLES — 3-Column Grid
       Excludes the 3 hero posts. Supports pagination.
       ═══════════════════════════════════════════════════════════════ -->

  <?php
  $exclude_ids = isset( $hero_posts ) ? wp_list_pluck( $hero_posts, 'ID' ) : [];

  $grid_query = new WP_Query( [
    'posts_per_page'      => 9,
    'post_status'         => 'publish',
    'post__not_in'        => $exclude_ids,
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => false, // need for pagination
    'paged'               => max( 1, get_query_var( 'paged' ) ),
  ] );

  if ( $grid_query->have_posts() ) : ?>

  <section
    id="latest"
    class="latest-section"
    aria-label="<?php esc_attr_e( 'Latest articles', 'noircraftlab' ); ?>"
  >

    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e( 'Latest', 'noircraftlab' ); ?></h2>
      <span class="section-line" aria-hidden="true"></span>
    </div>

    <div class="article-grid">

      <?php while ( $grid_query->have_posts() ) : $grid_query->the_post(); ?>

      <article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'card animate-fade-up' ); ?>
        itemscope
        itemtype="https://schema.org/NewsArticle"
      >
        <?php if ( has_post_thumbnail() ) : ?>
        <a
          href="<?php the_permalink(); ?>"
          class="card-image-wrap"
          tabindex="-1"
          aria-hidden="true"
        >
          <?php the_post_thumbnail( 'noircraftlab-card', [
            'alt'      => '',
            'loading'  => 'lazy',
            'itemprop' => 'image',
          ] ); ?>
        </a>
        <?php endif; ?>

        <div class="card-body">
          <?php
          $cats = get_the_category();
          if ( $cats ) : ?>
            <span class="card-category"><?php echo esc_html( $cats[0]->name ); ?></span>
          <?php endif; ?>

          <h3 class="card-title" itemprop="headline">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
          </h3>

          <p class="card-excerpt" itemprop="description">
            <?php echo wp_trim_words( get_the_excerpt(), 18, '&hellip;' ); ?>
          </p>

          <div class="card-meta">
            <span itemprop="author" itemscope itemtype="https://schema.org/Person">
              <span itemprop="name"><?php the_author(); ?></span>
            </span>
            <span class="card-meta-dot" aria-hidden="true">·</span>
            <time
              datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"
              itemprop="datePublished"
            ><?php echo esc_html( get_the_date() ); ?></time>
            <span class="card-meta-dot" aria-hidden="true">·</span>
            <span><?php echo esc_html( noircraftlab_reading_time() ); ?></span>
          </div>

          <meta itemprop="url" content="<?php the_permalink(); ?>">
          <meta itemprop="dateModified" content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
        </div><!-- /.card-body -->

      </article><!-- .card -->

      <?php endwhile; ?>

    </div><!-- /.article-grid -->

    <!-- Pagination -->
    <?php if ( $grid_query->max_num_pages > 1 ) : ?>
    <nav
      class="pagination"
      aria-label="<?php esc_attr_e( 'Posts pagination', 'noircraftlab' ); ?>"
    >
      <?php
      echo paginate_links( [ // phpcs:ignore WordPress.Security.EscapeOutput
        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $grid_query->max_num_pages,
        'prev_text'    => '&larr; ' . esc_html__( 'Newer', 'noircraftlab' ),
        'next_text'    => esc_html__( 'Older', 'noircraftlab' ) . ' &rarr;',
        'type'         => 'list',
        'add_fragment' => '#latest',
      ] );
      ?>
    </nav>
    <?php endif; ?>

  </section><!-- .latest-section -->

  <?php
  wp_reset_postdata();
  else : ?>

  <p class="no-posts"><?php esc_html_e( 'No posts found. Check back soon.', 'noircraftlab' ); ?></p>

  <?php endif; ?>





</div><!-- /.container -->


<?php /* =============================================================================
   CATEGORY STRIP — DYNAMIC SCROLL UX (added 2026-05-05)
   Manages: dual-side fade visibility + drag-to-scroll + vertical-wheel→horizontal
   See style.css "CATEGORY STRIP — DYNAMIC SCROLL UX" block for the visual side.
   ============================================================================= */ ?>
<script>
(function () {
  'use strict';

  var strip = document.querySelector('.category-strip');
  if (!strip) return;
  var inner = strip.querySelector('.category-strip__inner');
  if (!inner) return;

  /* ── Fade classes — toggle based on scroll position ───────────── */
  function updateFades() {
    var atStart = inner.scrollLeft <= 1;
    var atEnd   = inner.scrollLeft + inner.clientWidth >= inner.scrollWidth - 1;
    strip.classList.toggle('is-scroll-start', atStart);
    strip.classList.toggle('is-scroll-end',   atEnd);
  }

  inner.addEventListener('scroll', updateFades, { passive: true });
  window.addEventListener('resize', updateFades);
  updateFades();

  /* ── Drag-to-scroll with left mouse button ────────────────────── */
  var isDown = false, startX = 0, scrollStart = 0, hasDragged = false;
  var DRAG_THRESHOLD = 3; // px before "drag" is recognized

  inner.addEventListener('mousedown', function (e) {
    if (e.button !== 0) return; // left button only
    isDown = true;
    hasDragged = false;
    startX = e.pageX;
    scrollStart = inner.scrollLeft;
    /* NOTE: is-dragging is NOT added here — that would block clicks via
       pointer-events: none. Class is added in mousemove only after the
       cursor moves past DRAG_THRESHOLD, signalling intentional drag. */
  });

  function endDrag() {
    if (!isDown) return;
    isDown = false;
    inner.classList.remove('is-dragging');
  }

  document.addEventListener('mouseup',    endDrag);
  document.addEventListener('mouseleave', endDrag);

  inner.addEventListener('mousemove', function (e) {
    if (!isDown) return;
    var walk = e.pageX - startX;
    if (Math.abs(walk) > DRAG_THRESHOLD) {
      if (!hasDragged) {
        hasDragged = true;
        inner.classList.add('is-dragging'); // add ONLY on real drag
      }
      e.preventDefault();
      inner.scrollLeft = scrollStart - walk;
    }
  });

  /* ── Suppress click that immediately follows a drag ───────────── */
  inner.addEventListener('click', function (e) {
    if (hasDragged) {
      e.preventDefault();
      e.stopPropagation();
      hasDragged = false;
    }
  }, true);

  /* ── Vertical wheel → horizontal scroll (when over the strip) ─── */
  inner.addEventListener('wheel', function (e) {
    if (e.deltaY === 0) return; // pure horizontal wheel — let browser handle
    var canScrollLeft  = inner.scrollLeft > 0;
    var canScrollRight = inner.scrollLeft + inner.clientWidth < inner.scrollWidth;
    if ((e.deltaY < 0 && canScrollLeft) || (e.deltaY > 0 && canScrollRight)) {
      e.preventDefault();
      inner.scrollLeft += e.deltaY;
    }
  }, { passive: false });

})();
</script>


<?php get_footer(); ?>
