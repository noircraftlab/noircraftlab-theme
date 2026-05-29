<?php
/**
 * search.php — Noircraft Lab Theme
 *
 * Search results page. Triggered by /?s= query.
 *
 * Companion files:
 *   - header.php       → AJAX dropdown (live suggestions)
 *   - functions.php    → pre_get_posts filter (post + page)
 *   - style.css        → 11th critical block "SEARCH RESULTS PAGE"
 *
 * Behaviour:
 *   - Groups results by post type: post → "Articles", page → "Information"
 *   - Renders featured image OR placeholder (95% posts have images per
 *     Roman's measurement 2026-05-09)
 *   - Pagination: 12 per page (set in functions.php pre_get_posts filter)
 *   - Empty state: links to /categories/ hub
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Highlight search term occurrences inside a string with <mark>.
 * Multibyte- and case-insensitive. Escapes HTML in the input.
 *
 * @param string $text   Plain text to highlight (will be esc_html'd).
 * @param string $query  Search term.
 * @return string  HTML-safe string with <mark> wraps.
 */
function noircraftlab_highlight_search_term( string $text, string $query ): string {
  $text = (string) $text;
  if ( $text === '' ) return '';
  $safe = esc_html( $text );
  if ( $query === '' ) return $safe;

  /* Use preg_replace with /u (unicode) and /i (case-insensitive).
   * Escape regex metachars in query. */
  $pattern = '/(' . preg_quote( $query, '/' ) . ')/iu';
  $result  = preg_replace( $pattern, '<mark>$1</mark>', $safe );
  return $result === null ? $safe : $result;
}

/**
 * Build a contextual excerpt — finds first occurrence of $query in post content
 * and returns ~$words_around words before and after, with ellipsis on edges.
 * Falls back to standard excerpt if query not found.
 *
 * @param int    $post_id      Post ID.
 * @param string $query        Search query.
 * @param int    $words_around Words on each side of match (default 14 = ~28 total).
 * @return string  Plain text snippet (NOT yet HTML-highlighted).
 */
function noircraftlab_search_excerpt_with_context( int $post_id, string $query, int $words_around = 14 ): string {
  $content = (string) get_post_field( 'post_content', $post_id );
  $content = wp_strip_all_tags( strip_shortcodes( $content ) );
  $content = (string) preg_replace( '/\s+/u', ' ', $content );
  $content = trim( $content );

  if ( $content === '' ) return '';

  $fallback = wp_trim_words( $content, $words_around * 2, '…' );
  if ( $query === '' ) return $fallback;

  $pos = mb_stripos( $content, $query );
  if ( $pos === false ) return $fallback;

  $q_len = mb_strlen( $query );

  $before        = mb_substr( $content, 0, $pos );
  $before_words  = preg_split( '/\s+/u', trim( $before ) ) ?: [];
  $skipped_start = max( 0, count( $before_words ) - $words_around );
  $start_words   = array_slice( $before_words, $skipped_start );
  $start_text    = implode( ' ', $start_words );

  $after        = mb_substr( $content, $pos + $q_len );
  $after_words  = preg_split( '/\s+/u', trim( $after ) ) ?: [];
  $end_words    = array_slice( $after_words, 0, $words_around );
  $end_text     = implode( ' ', $end_words );

  $matched = mb_substr( $content, $pos, $q_len );

  $prefix = $skipped_start > 0 ? '… ' : '';
  $suffix = count( $after_words ) > $words_around ? ' …' : '';

  return $prefix . $start_text . ' ' . $matched . ' ' . $end_text . $suffix;
}

get_header();

$search_query = get_search_query();
$total_found  = (int) ( $GLOBALS['wp_query']->found_posts ?? 0 );
?>

<section class="search-results-page" aria-labelledby="search-results-title">
  <div class="container">

    <!-- ── Header ──────────────────────────────────────────── -->
    <header class="search-results-page__header">
      <div class="search-results-page__eyebrow">
        <?php esc_html_e( 'Search', 'noircraftlab' ); ?>
      </div>
      <h1 id="search-results-title" class="search-results-page__title">
        <?php
        printf(
          /* translators: %s: search query */
          esc_html__( 'Search results for: %s', 'noircraftlab' ),
          '<span class="search-results-page__query">&ldquo;' . esc_html( $search_query ) . '&rdquo;</span>'
        );
        ?>
      </h1>
      <?php if ( have_posts() ) : ?>
        <p class="search-results-page__count">
          <?php
          printf(
            esc_html( _n( '%d result', '%d result', $total_found, 'noircraftlab' ) ),
            (int) $total_found
          );
          ?>
        </p>
      <?php endif; ?>
    </header>

    <?php if ( have_posts() ) : ?>

      <?php
      /**
       * Pre-pass: bucket results by post_type.
       * We loop through wp_query, store each post in its bucket, then
       * render groups in fixed order: post → page.
       */
      $buckets = [ 'post' => [], 'page' => [] ];

      while ( have_posts() ) :
        the_post();
        $type = get_post_type();
        if ( isset( $buckets[ $type ] ) ) {
          $buckets[ $type ][] = get_the_ID();
        }
      endwhile;
      rewind_posts(); /* reset for safety, not strictly needed here */

      $group_labels = [
        'post'    => __( 'Articles',    'noircraftlab' ),
        'page'    => __( 'Information', 'noircraftlab' ),
      ];
      ?>

      <?php foreach ( $buckets as $type => $ids ) : ?>
        <?php if ( empty( $ids ) ) continue; ?>

        <section class="search-results-page__group search-results-page__group--<?php echo esc_attr( $type ); ?>">
          <h2 class="search-results-page__group-title">
            <?php echo esc_html( $group_labels[ $type ] ); ?>
            <span class="search-results-page__group-count">(<?php echo (int) count( $ids ); ?>)</span>
          </h2>

          <ul class="search-results-page__list">
            <?php foreach ( $ids as $post_id ) : ?>
              <?php
              $permalink = get_permalink( $post_id );
              $title     = get_the_title( $post_id );

              /* Smart excerpt with ~14 words around first match of $search_query */
              $excerpt   = noircraftlab_search_excerpt_with_context( $post_id, $search_query, 14 );

              $cats      = get_the_category( $post_id );
              $cat       = ! empty( $cats ) ? $cats[0] : null;
              $cat_slug  = $cat ? $cat->slug : 'default';
              $has_thumb = has_post_thumbnail( $post_id );
              $date_str  = get_the_date( '', $post_id );

              /* Deep-link via query parameter — survives WP canonical redirect.
               * (Hash #:~:text= was getting stripped by esc_url / wp_safe_redirect.)
               * On the single page, our wp_footer JS reads ?noircraftlab_q=,
               * scrolls to + highlights the term, then clears the param via
               * history.replaceState to keep the URL clean. */
              $deep_link = $permalink;
              if ( $search_query !== '' ) {
                $deep_link = add_query_arg(
                  'noircraftlab_q',
                  rawurlencode( $search_query ),
                  $permalink
                );
              }
              ?>

              <li class="search-results-page__item">

                <a class="search-results-page__thumb-wrap" href="<?php echo esc_url( $deep_link ); ?>" tabindex="-1" aria-hidden="true">
                  <?php if ( $has_thumb ) : ?>
                    <?php
                    echo get_the_post_thumbnail(
                      $post_id,
                      'medium',
                      [
                        'class'   => 'search-results-page__thumb',
                        'loading' => 'lazy',
                        'alt'     => '',
                      ]
                    );
                    ?>
                  <?php else : ?>
                    <span class="search-results-page__thumb search-results-page__thumb--placeholder search-results-page__thumb--cat-<?php echo esc_attr( $cat_slug ); ?>">
                      <span class="search-results-page__thumb-letter" aria-hidden="true">F</span>
                    </span>
                  <?php endif; ?>
                </a>

                <div class="search-results-page__content">

                  <?php if ( $cat ) : ?>
                    <a class="search-results-page__cat" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                      <?php echo esc_html( $cat->name ); ?>
                    </a>
                  <?php endif; ?>


                  <h3 class="search-results-page__item-title">
                    <a href="<?php echo esc_url( $deep_link ); ?>"><?php
                      /* Highlight matches inside the title */
                      echo noircraftlab_highlight_search_term( $title, $search_query );
                    ?></a>
                  </h3>

                  <?php if ( $excerpt ) : ?>
                    <p class="search-results-page__excerpt"><?php
                      /* Smart contextual excerpt + highlight */
                      echo noircraftlab_highlight_search_term( $excerpt, $search_query );
                    ?></p>
                  <?php endif; ?>

                  <?php if ( $type === 'post' && $date_str ) : ?>
                    <div class="search-results-page__meta">
                      <time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
                        <?php echo esc_html( $date_str ); ?>
                      </time>
                    </div>
                  <?php endif; ?>

                </div>

              </li>
            <?php endforeach; ?>
          </ul>
        </section>

      <?php endforeach; ?>

      <!-- ── Pagination ────────────────────────────────────── -->
      <?php
      the_posts_pagination( [
        'mid_size'  => 1,
        'prev_text' => esc_html__( 'Newer', 'noircraftlab' ),
        'next_text' => esc_html__( 'Older', 'noircraftlab' ),
        'class'     => 'search-results-page__pagination',
      ] );
      ?>

    <?php else : ?>

      <!-- ── Empty state ─────────────────────────────────────── -->
      <div class="search-results-page__empty">
        <h2 class="search-results-page__empty-title">
          <?php
          printf(
            /* translators: %s: search query */
            esc_html__( 'No results for &ldquo;%s&rdquo;.', 'noircraftlab' ),
            esc_html( $search_query )
          );
          ?>
        </h2>
        <p class="search-results-page__empty-text">
          <?php esc_html_e( 'Try different keywords or browse all categories.', 'noircraftlab' ); ?>
        </p>
        <a class="search-results-page__empty-cta"
           href="<?php
           /* Polylang-aware link to Categories Hub */
           $cats_hub_id = 0;
           if ( function_exists( 'pll_get_post' ) ) {
             $cats_hub_id = pll_get_post( get_page_by_path( 'categories' )->ID ?? 0 );
           }
           echo esc_url( $cats_hub_id ? get_permalink( $cats_hub_id ) : home_url( '/categories/' ) );
           ?>">
          <?php esc_html_e( 'Browse all categories', 'noircraftlab' ); ?> →
        </a>
      </div>

    <?php endif; ?>

  </div><!-- /.container -->
</section><!-- /.search-results-page -->

<?php get_footer(); ?>
