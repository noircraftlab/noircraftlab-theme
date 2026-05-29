<?php
/**
 * category.php — Noircraft Lab Theme
 *
 * Archive template for post categories.
 * Uses the main WordPress query (pre-populated by WP for category archives).
 * Polylang: language filtering handled automatically by Polylang when active.
 *
 * Hero banner: shown when the category has an image set via the
 * "Categories Images" plugin (z_taxonomy_image_url). When active the
 * standard .archive-header is suppressed — its content moves into the hero.
 * When no image exists the .archive-header renders exactly as before.
 *
 * Template hierarchy: category-{slug}.php → category-{id}.php → category.php
 *
 * @package NoircraftLab
 * @version 1.0.1
 */

get_header();

// ── Category data ─────────────────────────────────────────────────────────────
$current_cat = get_queried_object();
$cat_name    = isset( $current_cat->name )        ? $current_cat->name        : '';
$cat_desc    = isset( $current_cat->description ) ? $current_cat->description : '';
$cat_count   = isset( $current_cat->count )       ? (int) $current_cat->count : 0;
$term_id     = isset( $current_cat->term_id )     ? $current_cat->term_id     : 0;

// Polylang: current language slug
$lang_slug = function_exists( 'pll_current_language' )
  ? pll_current_language( 'slug' )
  : '';

// Categories Images plugin — safe check; returns '' when plugin inactive
$cat_image_url = ( function_exists( 'z_taxonomy_image_url' ) && $term_id )
  ? (string) z_taxonomy_image_url( $term_id )
  : '';

$has_hero = ! empty( $cat_image_url );
?>

<div class="container">

  <?php if ( $has_hero ) : ?>

  <!-- ═════════════════════════════════════════════════════════════════
       CATEGORY HERO BANNER
       Shown only when a category image is set (Categories Images plugin).
       Replaces .archive-header — all the same data, richer presentation.
       ═════════════════════════════════════════════════════════════════ -->

  <section
    class="category-hero"
    aria-label="<?php echo esc_attr( $cat_name ); ?>"
  >
    <!-- Background photo -->
    <div
      class="category-hero__bg"
      style="background-image: url(<?php echo esc_url( $cat_image_url ); ?>);"
      role="img"
      aria-hidden="true"
    ></div>

    <!-- Dark gradient overlay (transparent → near-black) -->
    <div class="category-hero__overlay" aria-hidden="true"></div>

    <!-- Text content anchored to the bottom-left -->
    <div class="category-hero__content">

      <div class="category-hero__eyebrow">
        <?php esc_html_e( 'Category', 'noircraftlab' ); ?>
      </div>

      <h1 class="category-hero__title">
        <?php echo esc_html( $cat_name ); ?>
      </h1>

      <?php if ( $cat_desc ) : ?>
      <p class="category-hero__desc">
        <?php echo esc_html( wp_strip_all_tags( $cat_desc ) ); ?>
      </p>
      <?php endif; ?>

      <div class="category-hero__meta">
        <span class="category-hero__count">
          <?php
          printf(
            /* translators: %d: number of posts */
            esc_html( _n( '%d post', '%d posts', $cat_count, 'noircraftlab' ) ),
            $cat_count
          );
          ?>
        </span>
        <?php if ( $lang_slug ) : ?>
        <span class="category-hero__dot" aria-hidden="true">·</span>
        <span class="category-hero__lang"><?php echo esc_html( strtoupper( $lang_slug ) ); ?></span>
        <?php endif; ?>
      </div>

    </div><!-- /.category-hero__content -->
  </section><!-- /.category-hero -->

  <?php else : ?>

  <!-- ═════════════════════════════════════════════════════════════════
       STANDARD ARCHIVE HEADER
       Shown when no category image is set — full backward compatibility.
       ═════════════════════════════════════════════════════════════════ -->

  <header class="archive-header">

    <div class="archive-header__eyebrow">
      <span class="archive-header__label"><?php esc_html_e( 'Category', 'noircraftlab' ); ?></span>
    </div>

    <h1 class="archive-header__title">
      <?php echo esc_html( $cat_name ); ?>
    </h1>

    <?php if ( $cat_desc ) : ?>
    <p class="archive-header__desc">
      <?php echo wp_kses_post( $cat_desc ); ?>
    </p>
    <?php endif; ?>

    <div class="archive-header__meta">
      <span class="archive-header__count">
        <?php
        printf(
          /* translators: %d: number of posts */
          esc_html( _n( '%d post', '%d posts', $cat_count, 'noircraftlab' ) ),
          $cat_count
        );
        ?>
      </span>
      <?php if ( $lang_slug ) : ?>
      <span class="archive-header__meta-dot" aria-hidden="true">·</span>
      <span class="archive-header__lang"><?php echo esc_html( strtoupper( $lang_slug ) ); ?></span>
      <?php endif; ?>
    </div>

  </header><!-- /.archive-header -->

  <?php endif; ?>


  <!-- ═════════════════════════════════════════════════════════════════
       POSTS GRID
       Main WP query — 3 col desktop / 2 tablet / 1 mobile
       ═════════════════════════════════════════════════════════════════ -->

  <?php if ( have_posts() ) : ?>

  <div class="article-grid">

    <?php while ( have_posts() ) : the_post(); ?>

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
      <?php else : ?>
      <div class="card-image-placeholder"></div>
      <?php endif; ?>

      <div class="card-body">

        <span class="card-category">
          <?php echo esc_html( $cat_name ); ?>
        </span>

        <h2 class="card-title" itemprop="headline">
          <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>

        <?php
        $excerpt = get_the_excerpt();
        if ( $excerpt ) : ?>
        <p class="card-excerpt" itemprop="description">
          <?php echo esc_html( wp_trim_words( $excerpt, 18, '&hellip;' ) ); ?>
        </p>
        <?php endif; ?>

        <div class="card-meta">
          <span itemprop="author" itemscope itemtype="https://schema.org/Person">
            <span itemprop="name"><?php the_author(); ?></span>
          </span>
          <span class="card-meta-dot" aria-hidden="true">·</span>
          <time
            datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"
            itemprop="datePublished"
          ><?php echo esc_html( get_the_date() ); ?></time>
          <?php if ( function_exists( 'noircraftlab_reading_time' ) ) : ?>
          <span class="card-meta-dot" aria-hidden="true">·</span>
          <span><?php echo esc_html( noircraftlab_reading_time() ); ?></span>
          <?php endif; ?>
        </div>

        <meta itemprop="url" content="<?php the_permalink(); ?>">

      </div><!-- /.card-body -->

    </article><!-- .card -->

    <?php endwhile; ?>

  </div><!-- /.article-grid -->


  <!-- ── Pagination ──────────────────────────────────────────────── -->

  <?php
  global $wp_query;
  if ( $wp_query->max_num_pages > 1 ) : ?>

  <nav
    class="pagination"
    aria-label="<?php esc_attr_e( 'Posts pagination', 'noircraftlab' ); ?>"
  >
    <?php
    echo paginate_links( [ // phpcs:ignore WordPress.Security.EscapeOutput
      'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
      'format'    => '?paged=%#%',
      'current'   => max( 1, get_query_var( 'paged' ) ),
      'total'     => $wp_query->max_num_pages,
      'prev_text' => '&larr; ' . esc_html__( 'Newer', 'noircraftlab' ),
      'next_text' => esc_html__( 'Older', 'noircraftlab' ) . ' &rarr;',
      'type'      => 'list',
    ] );
    ?>
  </nav>

  <?php endif; ?>

  <?php else : ?>

  <!-- No posts fallback -->
  <div class="no-posts">
    <p>
      <?php
      printf(
        /* translators: %s: category name */
        esc_html__( 'No posts found in &ldquo;%s&rdquo; yet. Check back soon.', 'noircraftlab' ),
        esc_html( $cat_name )
      );
      ?>
    </p>
    <a
      href="<?php echo esc_url( home_url( '/' ) ); ?>"
      class="btn-gold"
      style="display:inline-flex;margin-top:1.5rem;"
    ><?php esc_html_e( 'Back to home', 'noircraftlab' ); ?></a>
  </div>

  <?php endif; ?>

</div><!-- /.container -->

<?php get_footer(); ?>
