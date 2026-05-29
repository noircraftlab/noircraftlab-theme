<?php
/**
 * archive.php — Noircraft Lab Theme
 *
 * Universal archive template: tags, dates, authors, custom taxonomies.
 * Falls back here when no specific template (category.php, tag.php, etc.) exists.
 *
 * Uses the_archive_title() / the_archive_description() — works across all
 * archive types automatically.
 * Polylang: language filtering handled automatically by Polylang when active.
 *
 * Template hierarchy (last resort before index.php):
 *   category.php → tag.php → taxonomy.php → author.php → date.php → archive.php
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

get_header();
?>

<div class="container">

  <!-- ═══════════════════════════════════════════════════════════════
       ARCHIVE HEADER
       the_archive_title() prepends "Category:", "Tag:", "Author:",
       "Month:" etc. automatically. strip_prefix removes that for
       clean display — we show the type via .archive-header__label.
       ═══════════════════════════════════════════════════════════════ -->

  <header class="archive-header">

    <!-- Archive type label (Category / Tag / Author / Date…) -->
    <div class="archive-header__eyebrow">
      <?php
      // Build a plain type label without the colon WordPress adds
      if ( is_category() ) :
        $type_label = __( 'Category', 'noircraftlab' );
      elseif ( is_tag() ) :
        $type_label = __( 'Tag', 'noircraftlab' );
      elseif ( is_author() ) :
        $type_label = __( 'Author', 'noircraftlab' );
      elseif ( is_day() ) :
        $type_label = __( 'Daily archive', 'noircraftlab' );
      elseif ( is_month() ) :
        $type_label = __( 'Monthly archive', 'noircraftlab' );
      elseif ( is_year() ) :
        $type_label = __( 'Yearly archive', 'noircraftlab' );
      elseif ( is_tax() ) :
        $type_label = __( 'Archive', 'noircraftlab' );
      else :
        $type_label = __( 'Archive', 'noircraftlab' );
      endif;
      ?>
      <span class="archive-header__label"><?php echo esc_html( $type_label ); ?></span>
    </div>

    <!-- Title — using the_archive_title() with stripped prefix -->
    <h1 class="archive-header__title">
      <?php
      /*
       * the_archive_title( '', '' ) strips the "Category: " / "Tag: " prefix
       * by passing empty strings for before/after. This lets us show the
       * type separately in the eyebrow element above.
       */
      the_archive_title( '', '' );
      ?>
    </h1>

    <!-- Description — category_description / tag_description / author bio -->
    <?php
    $archive_desc = get_the_archive_description();
    if ( $archive_desc ) : ?>
    <div class="archive-header__desc">
      <?php echo wp_kses_post( $archive_desc ); ?>
    </div>
    <?php endif; ?>

    <!-- Author avatar for author archives -->
    <?php if ( is_author() ) :
      $author_id     = get_queried_object_id();
      $author_avatar = get_avatar_url( $author_id, [ 'size' => 64 ] );
      $author_posts  = count_user_posts( $author_id );
      if ( $author_avatar ) : ?>
      <div class="archive-header__author">
        <img
          src="<?php echo esc_url( $author_avatar ); ?>"
          alt="<?php echo esc_attr( get_the_author_meta( 'display_name', $author_id ) ); ?>"
          class="archive-header__avatar"
          width="64"
          height="64"
          loading="lazy"
        >
        <div class="archive-header__meta">
          <span class="archive-header__count">
            <?php
            printf(
              esc_html( _n( '%d post', '%d posts', $author_posts, 'noircraftlab' ) ),
              (int) $author_posts
            );
            ?>
          </span>
        </div>
      </div>
      <?php endif;
    else :
      // Post count for non-author archives
      global $wp_query;
      $total = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
      if ( $total ) : ?>
      <div class="archive-header__meta">
        <span class="archive-header__count">
          <?php
          printf(
            esc_html( _n( '%d post', '%d posts', $total, 'noircraftlab' ) ),
            $total
          );
          ?>
        </span>
      </div>
      <?php endif;
    endif; ?>

  </header><!-- /.archive-header -->


  <!-- ═══════════════════════════════════════════════════════════════
       POSTS GRID
       Main WP query — 3 col desktop / 2 tablet / 1 mobile
       ═══════════════════════════════════════════════════════════════ -->

  <?php if ( have_posts() ) : ?>

  <div class="article-grid">

    <?php while ( have_posts() ) : the_post(); ?>

    <?php
    // Category label for card — use first category of the post
    $post_cats    = get_the_category();
    $cat_label    = $post_cats ? $post_cats[0]->name : '';
    $cat_link_url = $post_cats ? get_category_link( $post_cats[0]->term_id ) : '';
    ?>

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

        <?php if ( $cat_label ) : ?>
        <a href="<?php echo esc_url( $cat_link_url ); ?>" class="card-category">
          <?php echo esc_html( $cat_label ); ?>
        </a>
        <?php endif; ?>

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
    <p><?php esc_html_e( 'No posts found for this archive. Check back soon.', 'noircraftlab' ); ?></p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-gold" style="display:inline-flex;margin-top:1.5rem;">
      <?php esc_html_e( 'Back to home', 'noircraftlab' ); ?>
    </a>
  </div>

  <?php endif; ?>

</div><!-- /.container -->

<?php get_footer(); ?>
