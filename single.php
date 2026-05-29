<?php
/**
 * single.php — Noircraft Lab Theme
 *
 * Single post template.
 * Sections:
 *   1. Article Hero      — full-width featured image + overlay meta
 *   2. Article Body      — the_content(), Georgia 18px, max 740px
 *   3. Post Meta         — tags + categories
 *   4. Related Posts     — 3 cards from the same category
 *
 * Schema.org: NewsArticle wraps the entire <article> element.
 * Yoast / RankMath override with their own schema via wp_head() —
 * our output is suppressed if either plugin is active (see functions.php §11).
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<?php
/* ── Collect post data used across multiple sections ─────────────── */
$post_id      = get_the_ID();
$cats         = get_the_category();
$primary_cat  = $cats ? $cats[0] : null;
$tags         = get_the_tags();
$author_id    = get_the_author_meta( 'ID' );
$author_name  = get_the_author();
$author_url   = get_author_posts_url( $author_id );
$author_bio   = get_the_author_meta( 'description' );
$author_avatar = get_avatar_url( $author_id, [ 'size' => 80 ] );
$reading_time = noircraftlab_reading_time();
$has_thumb    = has_post_thumbnail();
?>

<!-- ═══════════════════════════════════════════════════════════════════
     ARTICLE — Schema.org NewsArticle wrapper
     ══════════════════════════════════════════════════════════════════ -->
<article
  id="post-<?php the_ID(); ?>"
  <?php post_class( 'single-post' ); ?>
  itemscope
  itemtype="https://schema.org/NewsArticle"
>
  <!-- Hidden schema fields -->
  <meta itemprop="url"           content="<?php the_permalink(); ?>">
  <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
  <meta itemprop="dateModified"  content="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
  <meta itemprop="inLanguage"    content="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">

  <?php if ( $has_thumb ) :
    $thumb_url = get_the_post_thumbnail_url( $post_id, 'noircraftlab-single' ); ?>
    <meta itemprop="image" content="<?php echo esc_url( $thumb_url ); ?>">
  <?php endif; ?>

  <!-- Publisher schema -->
  <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization" hidden>
    <meta itemprop="name" content="<?php bloginfo( 'name' ); ?>">
    <meta itemprop="url"  content="<?php echo esc_url( home_url( '/' ) ); ?>">
  </span>


  <!-- ═════════════════════════════════════════════════════════════════
       SECTION 1: ARTICLE HERO
       Full-viewport featured image with gradient overlay.
       Heading, category pill, and meta sit on top of the image.
       ═════════════════════════════════════════════════════════════════ -->

  <div class="article-hero<?php echo $has_thumb ? ' article-hero--has-image' : ' article-hero--no-image'; ?>">

    <?php if ( $has_thumb ) : ?>
    <!-- Background image -->
    <div class="article-hero__image" aria-hidden="true">
      <?php the_post_thumbnail( 'noircraftlab-single', [
        'alt'      => '',
        'loading'  => 'eager', // Hero = LCP — never lazy-load
        'itemprop' => 'image',
      ] ); ?>
    </div>
    <div class="article-hero__overlay" aria-hidden="true"></div>
    <?php endif; ?>

    <!-- Content positioned over the image -->
    <div class="article-hero__content">
      <div class="container">

        <!-- Category pill -->
        <?php if ( $primary_cat ) : ?>
        <a
          href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"
          class="card-category article-hero__category"
          itemprop="articleSection"
        ><?php echo esc_html( $primary_cat->name ); ?></a>
        <?php endif; ?>

        <!-- Post title -->
        <h1 class="article-hero__title" itemprop="headline">
          <?php the_title(); ?>
        </h1>

        <!-- Meta row: author · date · reading time -->
        <div class="article-hero__meta">

          <span class="article-hero__author" itemprop="author" itemscope itemtype="https://schema.org/Person">
            <a
              href="<?php echo esc_url( $author_url ); ?>"
              class="article-hero__author-link"
              itemprop="url"
            >
              <span itemprop="name"><?php echo esc_html( $author_name ); ?></span>
            </a>
          </span>

          <span class="article-hero__meta-dot" aria-hidden="true">·</span>

          <time
            class="article-hero__date"
            datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"
            itemprop="datePublished"
          ><?php echo esc_html( get_the_date() ); ?></time>

          <span class="article-hero__meta-dot" aria-hidden="true">·</span>

          <span class="article-hero__reading-time">
            <?php echo esc_html( $reading_time ); ?>
          </span>

        </div><!-- /.article-hero__meta -->

      </div><!-- /.container -->
    </div><!-- /.article-hero__content -->

  </div><!-- /.article-hero -->


  <!-- ═════════════════════════════════════════════════════════════════
       SECTION 2: ARTICLE BODY
       Narrow editorial column: Georgia, 18px, line-height 1.8.
       max-width 740px centred within .container.
       ═════════════════════════════════════════════════════════════════ -->

  <div class="container">
    <div class="article-wrap">

      <!-- Article content -->
      <div class="article-body entry-content" itemprop="articleBody">
        <?php
        the_content(
          sprintf(
            wp_kses(
              /* translators: %s: post title */
              __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'noircraftlab' ),
              [ 'span' => [ 'class' => [] ] ]
            ),
            get_the_title()
          )
        );

        /*
         * Paginated posts — numbered page links.
         * wp_link_pages() outputs previous/next page links for posts
         * split with the <!--nextpage--> block in the editor.
         */
        wp_link_pages( [
          'before'      => '<nav class="page-links" aria-label="' . esc_attr__( 'Post pages', 'noircraftlab' ) . '"><span class="page-links__label">' . esc_html__( 'Pages:', 'noircraftlab' ) . '</span>',
          'after'       => '</nav>',
          'link_before' => '<span class="page-links__item">',
          'link_after'  => '</span>',
        ] );
        ?>
      </div><!-- /.article-body -->


      <!-- ═══════════════════════════════════════════════════════════
           SECTION 3: POST META — Tags + Categories
           ═══════════════════════════════════════════════════════════ -->

      <?php if ( $tags || $cats ) : ?>
      <footer class="article-meta-footer">

        <?php if ( $cats ) : ?>
        <div class="article-meta-row">
          <span class="article-meta-label"><?php esc_html_e( 'Topics', 'noircraftlab' ); ?></span>
          <div class="article-meta-terms">
            <?php foreach ( $cats as $cat ) : ?>
              <a
                href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                class="term-pill term-pill--category"
                rel="category tag"
              ><?php echo esc_html( $cat->name ); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( $tags ) : ?>
        <div class="article-meta-row">
          <span class="article-meta-label"><?php esc_html_e( 'Tags', 'noircraftlab' ); ?></span>
          <div class="article-meta-terms">
            <?php foreach ( $tags as $tag ) : ?>
              <a
                href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                class="term-pill term-pill--tag"
                rel="tag"
              >#<?php echo esc_html( $tag->name ); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </footer><!-- /.article-meta-footer -->
      <?php endif; ?>


      <!-- ═══════════════════════════════════════════════════════════
           AUTHOR BOX
           ═══════════════════════════════════════════════════════════ -->

      <?php if ( $author_bio ) : ?>
      <div
        class="author-box"
        itemscope
        itemtype="https://schema.org/Person"
        itemprop="author"
      >
        <?php if ( $author_avatar ) : ?>
        <img
          src="<?php echo esc_url( $author_avatar ); ?>"
          alt="<?php echo esc_attr( $author_name ); ?>"
          class="author-box__avatar"
          width="80"
          height="80"
          loading="lazy"
          itemprop="image"
        >
        <?php endif; ?>

        <div class="author-box__content">
          <h3 class="author-box__name">
            <a
              href="<?php echo esc_url( $author_url ); ?>"
              itemprop="url"
            ><span itemprop="name"><?php echo esc_html( $author_name ); ?></span></a>
          </h3>
          <p class="author-box__bio" itemprop="description">
            <?php echo esc_html( $author_bio ); ?>
          </p>
        </div>
      </div><!-- /.author-box -->
      <?php endif; ?>


      <!-- ═══════════════════════════════════════════════════════════
           POST NAVIGATION — Prev / Next
           ═══════════════════════════════════════════════════════════ -->

      <?php
      $prev_post = get_previous_post();
      $next_post = get_next_post();

      if ( $prev_post || $next_post ) : ?>
      <nav class="post-navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'noircraftlab' ); ?>">

        <?php if ( $prev_post ) : ?>
        <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="post-nav-link post-nav-link--prev" rel="prev">
          <span class="post-nav-link__label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            <?php esc_html_e( 'Previous', 'noircraftlab' ); ?>
          </span>
          <span class="post-nav-link__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
        </a>
        <?php endif; ?>

        <?php if ( $next_post ) : ?>
        <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="post-nav-link post-nav-link--next" rel="next">
          <span class="post-nav-link__label">
            <?php esc_html_e( 'Next', 'noircraftlab' ); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </span>
          <span class="post-nav-link__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
        </a>
        <?php endif; ?>

      </nav><!-- /.post-navigation -->
      <?php endif; ?>

    </div><!-- /.article-wrap -->
  </div><!-- /.container -->


  <!-- ═════════════════════════════════════════════════════════════════
       SECTION 4: RELATED POSTS
       3 posts from the same primary category, excluding current post.
       Uses .article-grid and .card from style.css.
       ═════════════════════════════════════════════════════════════════ -->

  <?php
  if ( $primary_cat ) :

    $related_query = new WP_Query( [
      'posts_per_page'      => 3,
      'post_status'         => 'publish',
      'post__not_in'        => [ $post_id ],
      'ignore_sticky_posts' => 1,
      'no_found_rows'       => true,
      'category__in'        => [ $primary_cat->term_id ],
      'orderby'             => 'rand', // randomise to keep it fresh
    ] );

    if ( $related_query->have_posts() ) :
  ?>

  <section
    class="related-section"
    aria-label="<?php esc_attr_e( 'Related articles', 'noircraftlab' ); ?>"
  >
    <div class="container">

      <div class="section-header">
        <h2 class="section-title"><?php esc_html_e( 'Related', 'noircraftlab' ); ?></h2>
        <span class="section-line" aria-hidden="true"></span>
        <?php if ( $primary_cat ) : ?>
        <a
          href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"
          class="section-more"
        ><?php
          printf(
            /* translators: %s: category name */
            esc_html__( 'More in %s', 'noircraftlab' ),
            esc_html( $primary_cat->name )
          );
        ?> &rarr;</a>
        <?php endif; ?>
      </div><!-- /.section-header -->

      <div class="article-grid">

        <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>

        <article
          id="post-<?php the_ID(); ?>"
          <?php post_class( 'card' ); ?>
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
            $rel_cats = get_the_category();
            if ( $rel_cats ) : ?>
              <span class="card-category"><?php echo esc_html( $rel_cats[0]->name ); ?></span>
            <?php endif; ?>

            <h3 class="card-title" itemprop="headline">
              <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
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

        </article><!-- .card -->

        <?php endwhile; wp_reset_postdata(); ?>

      </div><!-- /.article-grid -->

    </div><!-- /.container -->
  </section><!-- .related-section -->

  <?php
    endif; // $related_query->have_posts()
  endif;  // $primary_cat
  ?>

</article><!-- .single-post -->

<?php endwhile; ?>

<?php
// Вывести комментарии и форму, ТОЛЬКО если открыты на этом посте
if ( comments_open() || get_comments_number() ) :
  ?>
  <section class="post-comments">
    <div class="container">
      <?php comments_template(); ?>
    </div>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
