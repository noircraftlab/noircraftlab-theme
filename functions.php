<?php
/**
 * functions.php — Noircraft Lab Theme
 *
 * Bootstraps the theme: supports, menus, image sizes, enqueue,
 * reading time helper, and SEO/Schema hooks.
 *
 * @package NoircraftLab
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/* =============================================================================
   1. THEME CONSTANTS
   ============================================================================= */

define( 'NOIRCRAFTLAB_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'NOIRCRAFTLAB_DIR',     get_template_directory() );
define( 'NOIRCRAFTLAB_URI',     get_template_directory_uri() );


/* =============================================================================
   2. THEME SETUP
   ============================================================================= */

add_action( 'after_setup_theme', 'noircraftlab_setup' );

/**
 * Direct gettext/ngettext filter — bypasses WP's textdomain machinery.
 *
 * Translations are stored as PHP arrays in code (not .mo files) because
 * the WordPress PHP MO parser failed to parse our self-compiled .mo files
 * (status: mo-import-failed). Inline arrays guarantee translations work
 * regardless of MO parsing or textdomain state.
 *
 * To add new translations: edit noircraftlab_get_translations() below.
 * Plural forms: array indexes match Russian/Ukrainian plural rule
 * [singular, few, many] — e.g. ['1 пост', '2 поста', '5 постов'].
 */

$GLOBALS['noircraftlab_i18n_status'] = 'inline-arrays';

function noircraftlab_get_translations(): array {
  static $cache = null;
  if ( $cache !== null ) return $cache;

  $locale = determine_locale();

  $all = [
    'uk' => [
      'Читать'                                                                                              => [ 'Читати' ],
      '%d post'                                                                                             => [ '%d допис', '%d дописи', '%d дописів' ],
      'No categories found.'                                                                                => [ 'Категорій не знайдено.' ],
      'Sections'                                                                                            => [ 'Розділи' ],
      'Company'                                                                                             => [ 'Про компанію' ],
      'Legal'                                                                                               => [ 'Документи' ],
      'Footer sections'                                                                                     => [ 'Розділи сайту' ],
      'Footer company links'                                                                                => [ 'Інформація про компанію' ],
      'Footer legal links'                                                                                  => [ 'Правова інформація' ],
      'Social media links'                                                                                  => [ 'Соціальні мережі' ],
      'Language switcher'                                                                                   => [ 'Перемикач мов' ],
      'All rights reserved.'                                                                                => [ 'Всі права захищено.' ],
      'Built with %s'                                                                                       => [ 'Створено на %s' ],
      'Premium editorial media covering Tech, Finance, Lifestyle, and Travel across RU · EN · UA.'          => [ 'Преміум-медіа про розумні покупки: техніка, фінанси, мода, подорожі. Російською, англійською та українською.' ],
      'Page'                                                                                                => [ 'Сторінка' ],
      'Pages:'                                                                                              => [ 'Сторінки:' ],
      'Page sections'                                                                                       => [ 'Розділи сторінки' ],

      // ── Index (homepage) + Category archive — added 2026-05-05 ────────
      'Featured stories'                                                                                    => [ 'Головні статті' ],
      'News'                                                                                                => [ 'Новини' ],
      'Browse by category'                                                                                  => [ 'Категорії' ],
      'Browse by section'                                                                                   => [ 'Розділи' ],
      'All'                                                                                                 => [ 'Усі' ],
      'Categories'                                                                                          => [ 'Категорії' ],
      'Latest articles'                                                                                     => [ 'Останні статті' ],
      'Latest'                                                                                              => [ 'Нове' ],
      'Posts pagination'                                                                                    => [ 'Навігація по статтях' ],
      'Newer'                                                                                               => [ 'Новіші' ],
      'Older'                                                                                               => [ 'Старіші' ],
      'No posts found. Check back soon.'                                                                    => [ 'Поки немає публікацій. Завітайте пізніше.' ],
      'Newsletter subscription'                                                                             => [ 'Підписка на розсилку' ],
      'Stay ahead of the curve'                                                                             => [ 'Будьте на крок попереду' ],
      'Get the best stories delivered to your inbox. No spam, unsubscribe anytime.'                         => [ 'Найкращі матеріали — просто на вашу пошту. Без спаму, відписатися можна будь-коли.' ],
      'Email newsletter signup'                                                                             => [ 'Форма підписки на розсилку' ],
      'Your email address'                                                                                  => [ 'Ваша електронна пошта' ],
      'Subscribe'                                                                                           => [ 'Підписатися' ],
      'By subscribing you agree to our %s.'                                                                 => [ 'Підписуючись, ви погоджуєтеся з нашою %s.' ],
      'Privacy Policy'                                                                                      => [ 'Політика конфіденційності' ],
      'Category'                                                                                            => [ 'Категорія' ],
      'No posts found in &ldquo;%s&rdquo; yet. Check back soon.'                                            => [ 'У категорії «%s» поки немає публікацій. Завітайте пізніше.' ],
      'Back to home'                                                                                        => [ 'На головну' ],

      // ── Header search modal — added 2026-05-09 ───────────────────────
      'Search'                                                                                              => [ 'Пошук' ],
      'What are you looking for?'                                                                           => [ 'Що ви шукаєте?' ],
      'Close search'                                                                                        => [ 'Закрити пошук' ],

      // ── Search suggestions + results page — added 2026-05-09 ─────────
      'Articles'                                                                                            => [ 'Статті' ],
      'Information'                                                                                         => [ 'Інформація' ],
      'View all results for &ldquo;%s&rdquo;'                                                               => [ 'Усі результати для «%s»' ],
      'Search results for: %s'                                                                              => [ 'Результати пошуку: %s' ],
      'No results for &ldquo;%s&rdquo;.'                                                                    => [ 'Нічого не знайдено за запитом «%s».' ],
      'Try different keywords or browse all categories.'                                                    => [ 'Спробуйте інші ключові слова або перегляньте всі категорії.' ],
      'Browse all categories'                                                                               => [ 'Усі категорії' ],
      'Sponsored'                                                                                           => [ 'Партнер' ],
      'Searching…'                                                                                          => [ 'Пошук…' ],
      '%d result'                                                                                           => [ '%d результат', '%d результати', '%d результатів' ],

      // ── In-page find (FAB) — added 2026-05-09 ────────────────────────
      'Find on page'                                                                                        => [ 'Пошук на сторінці' ],
      'No matches'                                                                                          => [ 'Немає збігів' ],
      '%1$s of %2$s'                                                                                        => [ '%1$s з %2$s' ],
      'Previous match'                                                                                      => [ 'Попереднє' ],
      'Next match'                                                                                          => [ 'Наступне' ],

      // ── Single post template — added 2026-05-10 ─────────────────────
      'Post pages'                                                                                          => [ 'Сторінки допису' ],
      'Topics'                                                                                              => [ 'Категорії' ],
      'Tags'                                                                                                => [ 'Мітки' ],
      'Post navigation'                                                                                     => [ 'Навігація по дописах' ],
      'Previous'                                                                                            => [ 'Попередня' ],
      'Next'                                                                                                => [ 'Наступна' ],
      'Related articles'                                                                                    => [ 'Схожі статті' ],
      'Related'                                                                                             => [ 'Схожі' ],
      'More in %s'                                                                                          => [ 'Більше в %s' ],
      '1 min read'                                                                                          => [ '1 хв читання' ],
      '%d min read'                                                                                         => [ '%d хв читання', '%d хв читання', '%d хв читання' ],

      // ── Audit sweep — added 2026-05-12 ──────────────────────────────
      // Header a11y + structure
      'Skip to content'                                                                                     => [ 'Перейти до контенту' ],
      'Primary navigation'                                                                                  => [ 'Основна навігація' ],
      'Open search'                                                                                         => [ 'Відкрити пошук' ],
      'Toggle navigation menu'                                                                              => [ 'Перемкнути меню навігації' ],
      'Search suggestions'                                                                                  => [ 'Пошукові підказки' ],
      'Mobile navigation'                                                                                   => [ 'Мобільна навігація' ],
      // Archive page labels
      'Tag'                                                                                                 => [ 'Тег' ],
      'Author'                                                                                              => [ 'Автор' ],
      'Daily archive'                                                                                       => [ 'Архів за день' ],
      'Monthly archive'                                                                                     => [ 'Архів за місяць' ],
      'Yearly archive'                                                                                      => [ 'Архів за рік' ],
      'Archive'                                                                                             => [ 'Архів' ],
      'No posts found for this archive. Check back soon.'                                                   => [ 'У цьому архіві поки немає публікацій. Завітайте пізніше.' ],
      // Static pages
      'Continue reading<span class="screen-reader-text"> "%s"</span>'                                       => [ 'Читати далі<span class="screen-reader-text"> «%s»</span>' ],
    ],
    'ru_RU' => [
      'Читать'                                                                                              => [ 'Читать' ],
      '%d post'                                                                                             => [ '%d пост', '%d поста', '%d постов' ],
      'No categories found.'                                                                                => [ 'Категории не найдены.' ],
      'Sections'                                                                                            => [ 'Разделы' ],
      'Company'                                                                                             => [ 'О компании' ],
      'Legal'                                                                                               => [ 'Документы' ],
      'Footer sections'                                                                                     => [ 'Разделы сайта' ],
      'Footer company links'                                                                                => [ 'Информация о компании' ],
      'Footer legal links'                                                                                  => [ 'Правовая информация' ],
      'Social media links'                                                                                  => [ 'Социальные сети' ],
      'Language switcher'                                                                                   => [ 'Переключатель языков' ],
      'All rights reserved.'                                                                                => [ 'Все права защищены.' ],
      'Built with %s'                                                                                       => [ 'Сделано на %s' ],
      'Premium editorial media covering Tech, Finance, Lifestyle, and Travel across RU · EN · UA.'          => [ 'Премиум-медиа об умных покупках: техника, финансы, мода, путешествия. На русском, английском и украинском.' ],
      'Page'                                                                                                => [ 'Страница' ],
      'Pages:'                                                                                              => [ 'Страницы:' ],
      'Page sections'                                                                                       => [ 'Разделы страницы' ],

      // ── Index (homepage) + Category archive — added 2026-05-05 ────────
      'Featured stories'                                                                                    => [ 'Главные статьи' ],
      'News'                                                                                                => [ 'Новости' ],
      'Browse by category'                                                                                  => [ 'Категории' ],
      'Browse by section'                                                                                   => [ 'Разделы' ],
      'All'                                                                                                 => [ 'Все' ],
      'Categories'                                                                                          => [ 'Категории' ],
      'Latest articles'                                                                                     => [ 'Последние статьи' ],
      'Latest'                                                                                              => [ 'Новое' ],
      'Posts pagination'                                                                                    => [ 'Навигация по статьям' ],
      'Newer'                                                                                               => [ 'Новее' ],
      'Older'                                                                                               => [ 'Старше' ],
      'No posts found. Check back soon.'                                                                    => [ 'Пока нет постов. Загляните позже.' ],
      'Newsletter subscription'                                                                             => [ 'Подписка на рассылку' ],
      'Stay ahead of the curve'                                                                             => [ 'Будьте на шаг впереди' ],
      'Get the best stories delivered to your inbox. No spam, unsubscribe anytime.'                         => [ 'Лучшие материалы — прямо вам на почту. Без спама, отписка в любой момент.' ],
      'Email newsletter signup'                                                                             => [ 'Форма подписки на рассылку' ],
      'Your email address'                                                                                  => [ 'Ваш email' ],
      'Subscribe'                                                                                           => [ 'Подписаться' ],
      'By subscribing you agree to our %s.'                                                                 => [ 'Подписываясь, вы соглашаетесь с нашей %s.' ],
      'Privacy Policy'                                                                                      => [ 'Политика конфиденциальности' ],
      'Category'                                                                                            => [ 'Категория' ],
      'No posts found in &ldquo;%s&rdquo; yet. Check back soon.'                                            => [ 'В категории «%s» пока нет постов. Загляните позже.' ],
      'Back to home'                                                                                        => [ 'На главную' ],

      // ── Header search modal — added 2026-05-09 ───────────────────────
      'Search'                                                                                              => [ 'Поиск' ],
      'What are you looking for?'                                                                           => [ 'Что вы ищете?' ],
      'Close search'                                                                                        => [ 'Закрыть поиск' ],

      // ── Search suggestions + results page — added 2026-05-09 ─────────
      'Articles'                                                                                            => [ 'Статьи' ],
      'Information'                                                                                         => [ 'Информация' ],
      'View all results for &ldquo;%s&rdquo;'                                                               => [ 'Все результаты для «%s»' ],
      'Search results for: %s'                                                                              => [ 'Результаты поиска: %s' ],
      'No results for &ldquo;%s&rdquo;.'                                                                    => [ 'Ничего не найдено по запросу «%s».' ],
      'Try different keywords or browse all categories.'                                                    => [ 'Попробуйте другие ключевые слова или перейдите ко всем категориям.' ],
      'Browse all categories'                                                                               => [ 'Все категории' ],
      'Sponsored'                                                                                           => [ 'Партнёр' ],
      'Searching…'                                                                                          => [ 'Поиск…' ],
      '%d result'                                                                                           => [ '%d результат', '%d результата', '%d результатов' ],

      // ── In-page find (FAB) — added 2026-05-09 ────────────────────────
      'Find on page'                                                                                        => [ 'Поиск на странице' ],
      'No matches'                                                                                          => [ 'Нет совпадений' ],
      '%1$s of %2$s'                                                                                        => [ '%1$s из %2$s' ],
      'Previous match'                                                                                      => [ 'Предыдущее' ],
      'Next match'                                                                                          => [ 'Следующее' ],

      // ── Single post template — added 2026-05-10 ─────────────────────
      'Post pages'                                                                                          => [ 'Страницы поста' ],
      'Topics'                                                                                              => [ 'Категории' ],
      'Tags'                                                                                                => [ 'Метки' ],
      'Post navigation'                                                                                     => [ 'Навигация по постам' ],
      'Previous'                                                                                            => [ 'Предыдущая' ],
      'Next'                                                                                                => [ 'Следующая' ],
      'Related articles'                                                                                    => [ 'Похожие статьи' ],
      'Related'                                                                                             => [ 'Похожие' ],
      'More in %s'                                                                                          => [ 'Больше в %s' ],
      '1 min read'                                                                                          => [ '1 мин чтения' ],
      '%d min read'                                                                                         => [ '%d мин чтения', '%d мин чтения', '%d мин чтения' ],

      // ── Audit sweep — added 2026-05-12 ──────────────────────────────
      // Header a11y + structure
      'Skip to content'                                                                                     => [ 'Перейти к контенту' ],
      'Primary navigation'                                                                                  => [ 'Основная навигация' ],
      'Open search'                                                                                         => [ 'Открыть поиск' ],
      'Toggle navigation menu'                                                                              => [ 'Переключить меню навигации' ],
      'Search suggestions'                                                                                  => [ 'Поисковые подсказки' ],
      'Mobile navigation'                                                                                   => [ 'Мобильная навигация' ],
      // Archive page labels
      'Tag'                                                                                                 => [ 'Тег' ],
      'Author'                                                                                              => [ 'Автор' ],
      'Daily archive'                                                                                       => [ 'Архив за день' ],
      'Monthly archive'                                                                                     => [ 'Архив за месяц' ],
      'Yearly archive'                                                                                      => [ 'Архив за год' ],
      'Archive'                                                                                             => [ 'Архив' ],
      'No posts found for this archive. Check back soon.'                                                   => [ 'В этом архиве пока нет публикаций. Загляните позже.' ],
      // Static pages
      'Continue reading<span class="screen-reader-text"> "%s"</span>'                                       => [ 'Читать далее<span class="screen-reader-text"> «%s»</span>' ],
    ],
    'en_US' => [
      // Override Russian default 'Читать' from template-categories.php
      'Читать' => [ 'Read more' ],
    ],
  ];

  $cache = $all[ $locale ] ?? [];
  $GLOBALS['noircraftlab_i18n_status'] = 'inline:' . $locale . ':' . count( $cache );
  return $cache;
}

add_filter( 'gettext', 'noircraftlab_filter_gettext', 10, 3 );
function noircraftlab_filter_gettext( $translation, $text, $domain ) {
  if ( $domain !== 'noircraftlab' ) return $translation;
  $t = noircraftlab_get_translations();
  return isset( $t[ $text ][0] ) && $t[ $text ][0] !== '' ? $t[ $text ][0] : $translation;
}

add_filter( 'ngettext', 'noircraftlab_filter_ngettext', 10, 5 );
function noircraftlab_filter_ngettext( $translation, $single, $plural, $number, $domain ) {
  if ( $domain !== 'noircraftlab' ) return $translation;
  $t = noircraftlab_get_translations();
  if ( ! isset( $t[ $single ] ) ) return $translation;

  // Russian/Ukrainian plural rule: 3 forms
  $n = (int) $number;
  $idx = ( $n % 10 === 1 && $n % 100 !== 11 ) ? 0
       : ( $n % 10 >= 2 && $n % 10 <= 4 && ( $n % 100 < 10 || $n % 100 >= 20 ) ? 1 : 2 );

  return $t[ $single ][ $idx ] ?? $translation;
}

function noircraftlab_setup(): void {

  /* ── i18n ─────────────────────────────────────────────────────── */
  // load_theme_textdomain moved to 'init' hook — see noircraftlab_load_i18n() below.
  // Reason: 'after_setup_theme' fires BEFORE Polylang switches the locale
  // based on URL prefix, so .mo for default locale was loaded too early.

  /* ── Core supports ────────────────────────────────────────────── */
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'html5', [
    'search-form', 'comment-form', 'comment-list',
    'gallery', 'caption', 'style', 'script',
  ] );
  add_theme_support( 'customize-selective-refresh-widgets' );
  add_theme_support( 'wp-block-styles' );
  add_theme_support( 'editor-styles' );
  add_theme_support( 'responsive-embeds' );

  /* ── Custom logo ──────────────────────────────────────────────── */
  add_theme_support( 'custom-logo', [
    'height'      => 60,
    'width'       => 180,
    'flex-height' => true,
    'flex-width'  => true,
  ] );

  /* ── Nav menus ────────────────────────────────────────────────── */
  register_nav_menus( [
    'primary'         => __( 'Primary Navigation', 'noircraftlab' ),
    'mobile'          => __( 'Mobile Navigation', 'noircraftlab' ),
    'footer-sections' => __( 'Footer — Sections', 'noircraftlab' ),
    'footer-company'  => __( 'Footer — Company', 'noircraftlab' ),
    'footer-legal'    => __( 'Footer — Legal', 'noircraftlab' ),
  ] );

  /* ── Image sizes ──────────────────────────────────────────────── */
  add_image_size( 'noircraftlab-hero',   1200, 675, true );
  add_image_size( 'noircraftlab-card',   800,  450, true );
  add_image_size( 'noircraftlab-single', 1440, 810, true );
  add_image_size( 'noircraftlab-thumb',  400,  400, true );
}


/* =============================================================================
   3. ENQUEUE STYLES & SCRIPTS
   ============================================================================= */

add_action( 'wp_enqueue_scripts', 'noircraftlab_enqueue' );

function noircraftlab_enqueue(): void {

  wp_enqueue_style(
    'noircraftlab-style',
    get_stylesheet_uri(),
    [],
    NOIRCRAFTLAB_VERSION
  );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

}



/* =============================================================================
   4. READING TIME
   ============================================================================= */

/**
 * noircraftlab_reading_time()
 *
 * Calculates estimated reading time for the current post in The Loop,
 * or for a given post ID / WP_Post object.
 *
 * Algorithm:
 *   1. Strip HTML tags from post content.
 *   2. Count words — Unicode-aware via preg fallback for Cyrillic/UA text.
 *   3. Divide by average adult reading speed (200 wpm).
 *   4. Floor to minimum 1 minute.
 *
 * @param  int|WP_Post|null $post  Post ID, WP_Post object, or null for current post.
 * @param  int              $wpm   Words per minute (default: 200).
 * @return string                  e.g. "5 min read" | "1 min read"
 */
function noircraftlab_reading_time( int|WP_Post|null $post = null, int $wpm = 200 ): string {

  $post_obj = get_post( $post );

  if ( ! $post_obj instanceof WP_Post ) {
    return '';
  }

  $content = apply_filters( 'the_content', $post_obj->post_content );
  $plain   = wp_strip_all_tags( html_entity_decode( $content, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
  $plain   = preg_replace( '/\s+/', ' ', trim( $plain ) );

  if ( '' === $plain ) {
    return esc_html__( '1 min read', 'noircraftlab' );
  }

  if ( preg_match( '/[^\x00-\x7F]/', $plain ) ) {
    $count = (int) preg_match_all( '/[\pL\pN]+(?:[\'\-][\pL\pN]+)*/u', $plain );
  } else {
    $count = str_word_count( $plain );
  }

  $minutes = max( 1, (int) ceil( $count / max( 1, $wpm ) ) );

  return sprintf(
    /* translators: %d: number of minutes */
    _n( '%d min read', '%d min read', $minutes, 'noircraftlab' ),
    $minutes
  );
}


/* =============================================================================
   5. EXCERPT
   ============================================================================= */

add_filter( 'excerpt_length', fn() => 30, 999 );
add_filter( 'excerpt_more',   fn() => '&hellip;' );


/* =============================================================================
   6. SIDEBARS / WIDGET AREAS
   ============================================================================= */

add_action( 'widgets_init', 'noircraftlab_register_sidebars' );

function noircraftlab_register_sidebars(): void {

  register_sidebar( [
    'name'          => __( 'Primary Sidebar', 'noircraftlab' ),
    'id'            => 'sidebar-primary',
    'description'   => __( 'Widgets in this area appear on blog and single post pages.', 'noircraftlab' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ] );

  register_sidebar( [
    'name'          => __( 'Footer Widgets', 'noircraftlab' ),
    'id'            => 'sidebar-footer',
    'description'   => __( 'Widgets in this area appear in the site footer.', 'noircraftlab' ),
    'before_widget' => '<div id="%1$s" class="widget widget--footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title widget-title--footer">',
    'after_title'   => '</h3>',
  ] );
}


/* =============================================================================
   7. BODY CLASSES
   ============================================================================= */

add_filter( 'body_class', 'noircraftlab_body_classes' );

function noircraftlab_body_classes( array $classes ): array {

  if ( function_exists( 'pll_current_language' ) ) {
    $classes[] = 'lang-' . sanitize_html_class( pll_current_language( 'slug' ) );
  }

  if ( is_singular() && has_post_thumbnail() ) {
    $classes[] = 'has-thumbnail';
  }

  return array_unique( $classes );
}


/* =============================================================================
   8. TITLE TAG SEPARATOR
   ============================================================================= */

add_filter( 'document_title_separator', fn() => '·' );


/* =============================================================================
   9. HREFLANG (fallback when Polylang/Yoast not active)
   ============================================================================= */

add_action( 'wp_head', 'noircraftlab_hreflang_fallback', 1 );

function noircraftlab_hreflang_fallback(): void {

  if ( function_exists( 'pll_the_languages' ) ) {
    return;
  }
  if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
    return;
  }

  $hreflangs = [
    'ru'        => home_url( '/ru/' ),
    'en'        => home_url( '/en/' ),
    'uk'        => home_url( '/ua/' ),
    'x-default' => home_url( '/en/' ),
  ];

  foreach ( $hreflangs as $lang => $url ) {
    printf(
      '<link rel="alternate" hreflang="%s" href="%s">' . "\n",
      esc_attr( $lang ),
      esc_url( $url )
    );
  }
}


/* =============================================================================
   10. SCHEMA — WebSite + SearchAction (homepage only)
   ============================================================================= */

add_action( 'wp_head', 'noircraftlab_schema_website', 2 );

function noircraftlab_schema_website(): void {

  if ( ! is_front_page() ) {
    return;
  }

  $schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'WebSite',
    'name'            => get_bloginfo( 'name' ),
    'url'             => home_url( '/' ),
    'description'     => get_bloginfo( 'description' ),
    'inLanguage'      => get_bloginfo( 'language' ),
    'potentialAction' => [
      '@type'       => 'SearchAction',
      'target'      => [
        '@type'       => 'EntryPoint',
        'urlTemplate' => home_url( '/?s={search_term_string}' ),
      ],
      'query-input' => 'required name=search_term_string',
    ],
  ];

  printf(
    '<script type="application/ld+json">%s</script>' . "\n",
    wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
  );
}


/* =============================================================================
   11. SCHEMA — NewsArticle (single posts)
       Note: Yoast / RankMath output their own Article schema.
       This fires only when neither plugin is active.
   ============================================================================= */

add_action( 'wp_head', 'noircraftlab_schema_article', 3 );

function noircraftlab_schema_article(): void {

  if ( ! is_single() ) {
    return;
  }
  if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
    return;
  }

  $post      = get_post();
  $cats      = get_the_category( $post->ID );
  $thumbnail = get_the_post_thumbnail_url( $post->ID, 'noircraftlab-single' );
  $author_id = $post->post_author;

  $schema = [
    '@context'       => 'https://schema.org',
    '@type'          => 'NewsArticle',
    'headline'       => get_the_title( $post->ID ),
    'description'    => get_the_excerpt( $post->ID ),
    'url'            => get_permalink( $post->ID ),
    'datePublished'  => get_the_date( 'c', $post->ID ),
    'dateModified'   => get_the_modified_date( 'c', $post->ID ),
    'inLanguage'     => get_bloginfo( 'language' ),
    'author'         => [
      '@type' => 'Person',
      'name'  => get_the_author_meta( 'display_name', $author_id ),
      'url'   => get_author_posts_url( $author_id ),
    ],
    'publisher'      => [
      '@type' => 'Organization',
      'name'  => get_bloginfo( 'name' ),
      'url'   => home_url( '/' ),
    ],
    'articleSection' => $cats ? esc_html( $cats[0]->name ) : '',
    'wordCount'      => (int) str_word_count( wp_strip_all_tags( $post->post_content ) ),
  ];

  if ( $thumbnail ) {
    $schema['image'] = [
      '@type' => 'ImageObject',
      'url'   => $thumbnail,
    ];
  }

  printf(
    '<script type="application/ld+json">%s</script>' . "\n",
    wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
  );
}


/* =============================================================================
   12. CUSTOM IMAGE SIZES — Media Library labels
   ============================================================================= */

add_filter( 'image_size_names_choose', 'noircraftlab_image_size_names' );

function noircraftlab_image_size_names( array $sizes ): array {
  return array_merge( $sizes, [
    'noircraftlab-hero'   => __( 'Noircraft Lab — Hero (1200×675)', 'noircraftlab' ),
    'noircraftlab-card'   => __( 'Noircraft Lab — Card (800×450)', 'noircraftlab' ),
    'noircraftlab-single' => __( 'Noircraft Lab — Single (1440×810)', 'noircraftlab' ),
    'noircraftlab-thumb'  => __( 'Noircraft Lab — Thumb (400×400)', 'noircraftlab' ),
  ] );
}


/* =============================================================================
   13. LOGO HELPER — Custom Logo support + SVG fallback
   ============================================================================= */

/**
 * Render the site logo.
 *
 * Order of preference:
 *   1) Custom Logo uploaded via Customizer → Site Identity (any image)
 *   2) Inline SVG fallback (Noircraft Lab — Variant A, two-tone wordmark)
 *
 * To replace the built-in logo:
 *   • Easiest: WP-admin → Appearance → Customize → Site Identity → upload Logo.
 *     No code edit needed. Works with PNG, JPG, SVG (via Safe SVG plugin).
 *   • Code-level: edit the SVG string in noircraftlab_logo_svg() below.
 *
 * @param string $variant 'full' (wordmark + mark) or 'mark' (icon only, for footer/mobile).
 * @return string HTML.
 */
function noircraftlab_logo( string $variant = 'full' ): string {

  $home_url = esc_url( home_url( '/' ) );
  $blog_name = esc_attr( get_bloginfo( 'name' ) );

  /* 1. Custom Logo uploaded via Customizer (overrides built-in SVG) */
  if ( has_custom_logo() ) {
    $logo_id  = (int) get_theme_mod( 'custom_logo' );
    $logo_src = wp_get_attachment_image_src( $logo_id, 'full' );
    if ( $logo_src ) {
      return sprintf(
        '<a href="%1$s" class="site-logo site-logo--custom" rel="home" aria-label="%2$s" itemprop="url">'
          . '<img src="%3$s" alt="%2$s" class="site-logo__img">'
        . '</a>',
        $home_url,
        $blog_name,
        esc_url( $logo_src[0] )
      );
    }
  }

  /* 2. Inline SVG fallback (Variant A — two-tone wordmark) */
  return sprintf(
    '<a href="%1$s" class="site-logo site-logo--svg" rel="home" aria-label="%2$s" itemprop="url">%3$s'
      . '<span class="visually-hidden">%2$s</span>'
    . '</a>',
    $home_url,
    $blog_name,
    noircraftlab_logo_svg( $variant )
  );
}

/**
 * Return the inline SVG-mark for Noircraft Lab.
 *
 * Variant A: "Noir" white + "craft" gold + " Lab" white (Georgia serif).
 * Mark-only: stylized "N" with gold dot accent.
 *
 * Colors are baked-in (matches CSS tokens): #F0EDE8 text, #D4AF37 gold.
 * For theme color changes — sync with style.css :root tokens.
 *
 * @param string $variant 'full' or 'mark'.
 * @return string Raw SVG string (output safely with echo).
 */
function noircraftlab_logo_svg( string $variant = 'full' ): string {

  if ( $variant === 'mark' ) {
    return '<svg class="site-logo__svg site-logo__svg--mark" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">'
      . '<text x="20" y="29" font-family="Georgia, serif" font-size="28" font-weight="700" fill="#F0EDE8" text-anchor="middle">N</text>'
      . '<circle cx="32" cy="12" r="2.5" fill="#D4AF37"/>'
    . '</svg>';
  }

  /* Full wordmark (header + footer default) */
  return '<svg class="site-logo__svg site-logo__svg--full" width="200" height="32" viewBox="0 0 200 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">'
    . '<text x="0" y="23" font-family="Georgia, serif" font-size="22" font-weight="700" letter-spacing="-0.4">'
      . '<tspan fill="#F0EDE8">Noir</tspan><tspan fill="#D4AF37">craft</tspan><tspan fill="#F0EDE8"> Lab</tspan>'
    . '</text>'
  . '</svg>';
}


/* ============================================================
 * RESPONSIVE TABLES — wrap <table> in scrollable div
 * Added 2026-05-07 — fix MOB-001
 * Wraps every <table> inside post content in <div class="table-scroll">
 * Works for classic editor, Gutenberg core/table, and pasted HTML.
 * ============================================================ */
add_filter( 'the_content', 'noircraftlab_wrap_tables', 20 );
function noircraftlab_wrap_tables( $content ) {
	// Skip empty or table-less content for performance
	if ( empty( $content ) || stripos( $content, '<table' ) === false ) {
		return $content;
	}

	// Wrap every <table>...</table> in .table-scroll
	// 's' flag = dotall (matches newlines); non-greedy match prevents merging adjacent tables
	$content = preg_replace(
		'#(<table\b[^>]*>.*?</table>)#is',
		'<div class="table-scroll">$1</div>',
		$content
	);

	return $content;
}

/* =============================================================================
   17. SEARCH — pre_get_posts filter for /?s= query
   ============================================================================= */

/**
 * Expand main search query to include posts and pages.
 * Default behaviour kept: post + page only.
 *
 * Also enforces 12 posts per page on search results.
 *
 * Note: REST API endpoints (/wp-json/wp/v2/posts, .../pages) are
 * called separately by the live AJAX dropdown — this filter affects only the
 * full results page (search.php).
 *
 * Added 2026-05-09 — search modal + live AJAX + search.php trio.
 */
add_action( 'pre_get_posts', 'noircraftlab_search_filter' );

function noircraftlab_search_filter( $query ) {
  if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
    return;
  }

  $query->set( 'post_type', [ 'post', 'page' ] );
  $query->set( 'posts_per_page', 12 );
  $query->set( 'orderby', 'date' );
  $query->set( 'order', 'DESC' );
}



/* =============================================================================
   18. SEARCH — Custom REST endpoint /wp-json/noircraftlab/v1/search
   ============================================================================= */

/**
 * Why custom endpoint:
 * Polylang Free does NOT honor the &lang= parameter on standard /wp/v2/* REST
 * endpoints — that is a Polylang Pro feature. As a result, /en/ and /uk/
 * dropdowns returned empty even when the WP search (/?s=) found posts.
 *
 * This endpoint runs WP_Query with the 'lang' arg, which Polylang Free DOES
 * intercept via its query filters. Same behaviour as the search.php page.
 *
 * Returns flat array of items — frontend groups by .type.
 *
 * Limits per type: 4 posts + 2 pages = max 6.
 *
 * Added 2026-05-09 (v3 fix for bug #4: empty EN/UK live dropdown).
 */
add_action( 'rest_api_init', 'noircraftlab_register_search_endpoint' );

function noircraftlab_register_search_endpoint(): void {
  register_rest_route( 'noircraftlab/v1', '/search', [
    'methods'             => 'GET',
    'callback'            => 'noircraftlab_rest_search',
    'permission_callback' => '__return_true',
    'args'                => [
      'q' => [
        'required'          => true,
        'sanitize_callback' => 'sanitize_text_field',
      ],
      'lang' => [
        'required'          => false,
        'default'           => 'ru',
        'sanitize_callback' => 'sanitize_key',
      ],
    ],
  ] );
}

function noircraftlab_rest_search( WP_REST_Request $request ) {
  $query = trim( (string) $request->get_param( 'q' ) );
  $lang  = (string) $request->get_param( 'lang' );

  if ( $query === '' || mb_strlen( $query ) < 3 ) {
    return rest_ensure_response( [] );
  }

  $limits  = [ 'post' => 4, 'page' => 2 ];
  $results = [];

  foreach ( $limits as $type => $limit ) {
    $args = [
      'post_type'           => $type,
      'post_status'         => 'publish',
      'posts_per_page'      => $limit,
      's'                   => $query,
      'orderby'             => 'relevance',
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
    ];

    /* Polylang language filter — ru/en/uk */
    if ( $lang ) {
      $args['lang'] = $lang;
    }

    $q = new WP_Query( $args );

    foreach ( $q->posts as $post_obj ) {
      $thumb_id  = get_post_thumbnail_id( $post_obj->ID );
      $thumb_url = null;
      if ( $thumb_id ) {
        $thumb_data = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
        if ( ! empty( $thumb_data[0] ) ) {
          $thumb_url = $thumb_data[0];
        }
      }

      $cats     = get_the_category( $post_obj->ID );
      $cat_slug = ! empty( $cats ) ? $cats[0]->slug : 'default';

      $results[] = [
        'id'       => (int) $post_obj->ID,
        'title'    => html_entity_decode( get_the_title( $post_obj->ID ), ENT_QUOTES, 'UTF-8' ),
        'link'     => get_permalink( $post_obj->ID ),
        'type'     => $post_obj->post_type,
        'thumb'    => $thumb_url,
        'cat_slug' => $cat_slug,
      ];
    }

    wp_reset_postdata();
  }

  return rest_ensure_response( $results );
}


/* =============================================================================
   19. SEARCH — Deep-link JS (scroll-to-highlight on single posts)
   ============================================================================= */

/**
 * Register 'noircraftlab_q' as a known public query var.
 *
 * Without this, WordPress's redirect_canonical() strips the parameter on
 * single-post URL normalization (server-side 301 redirect happens BEFORE the
 * browser gets the HTML, so the JS never sees ?noircraftlab_q=).
 *
 * Added 2026-05-09 (v5 fix for bug #3: canonical redirect was killing the param).
 */
add_filter( 'query_vars', 'noircraftlab_register_search_query_var' );

function noircraftlab_register_search_query_var( array $vars ): array {
  $vars[] = 'noircraftlab_q';
  return $vars;
}

/**
 * Belt-and-braces: also bypass canonical redirect entirely when ?noircraftlab_q=
 * is present. Prevents any edge-case where WP still tries to strip it.
 */
add_filter( 'redirect_canonical', 'noircraftlab_bypass_canonical_for_search', 10, 2 );

function noircraftlab_bypass_canonical_for_search( $redirect_url, $requested_url ) {
  if ( isset( $_GET['noircraftlab_q'] ) ) {
    return false;
  }
  return $redirect_url;
}

/**
 * When user clicks a search result, the link contains ?noircraftlab_q=QUERY.
 * (Initially we used Text Fragments #:~:text= but WordPress canonical
 * redirects strip URL hashes — query params survive cleanly.)
 *
 * This JS parses the query param, finds the first match in the post content
 * via TreeWalker, scrolls into view, applies a temporary gold underline,
 * and removes the param from the URL so it stays clean.
 *
 * Only outputs on singular pages to keep payload small everywhere else.
 *
 * Added 2026-05-09 (v4 fix for bug #3: hash fragment was stripped).
 */
add_action( 'wp_footer', 'noircraftlab_search_deep_link_js' );

function noircraftlab_search_deep_link_js(): void {
  if ( ! is_singular() ) {
    return;
  }
  ?>
<script>
(function () {
  'use strict';
  /* Parse ?noircraftlab_q=QUERY from URL search string */
  var params = new URLSearchParams(window.location.search);
  var raw = params.get('noircraftlab_q');
  if (!raw) return;

  var query = '';
  try { query = decodeURIComponent(raw); } catch (e) { return; }
  if (!query || query.length < 2) return;

  /* Wait for content to be rendered + lazy-loaded images settled */
  var run = function () {
    var content = document.querySelector('.entry-content, .single-post__content, article .post-content, main');
    if (!content) {
      cleanUrl();
      return;
    }

    /* TreeWalker — find first text node containing query (case-insensitive) */
    var walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, {
      acceptNode: function (node) {
        if (!node.nodeValue) return NodeFilter.FILTER_REJECT;
        var p = node.parentNode;
        if (!p) return NodeFilter.FILTER_REJECT;
        var tag = (p.tagName || '').toLowerCase();
        if (tag === 'script' || tag === 'style' || tag === 'noscript') {
          return NodeFilter.FILTER_REJECT;
        }
        return NodeFilter.FILTER_ACCEPT;
      }
    });

    var qLower = query.toLowerCase();
    var node;
    while ((node = walker.nextNode())) {
      var idx = node.nodeValue.toLowerCase().indexOf(qLower);
      if (idx === -1) continue;

      /* Wrap match in <mark> */
      try {
        var range = document.createRange();
        range.setStart(node, idx);
        range.setEnd(node, idx + query.length);

        var mark = document.createElement('mark');
        mark.className = 'noircraftlab-deep-highlight';
        range.surroundContents(mark);

        /* Scroll into view with offset for sticky header */
        setTimeout(function () {
          var rect = mark.getBoundingClientRect();
          var top  = rect.top + window.scrollY - 120; /* sticky header offset */
          window.scrollTo({ top: top, behavior: 'smooth' });
        }, 50);
      } catch (e) { /* range can fail if text spans nodes — ignore */ }

      cleanUrl();
      return; /* first match only */
    }

    cleanUrl(); /* no match found — still clean the URL */
  };

  /* Remove ?noircraftlab_q= from URL without page reload */
  function cleanUrl() {
    try {
      var url = new URL(window.location.href);
      url.searchParams.delete('noircraftlab_q');
      var clean = url.pathname + (url.search ? url.search : '') + url.hash;
      window.history.replaceState(null, '', clean);
    } catch (e) { /* no-op */ }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { setTimeout(run, 200); });
  } else {
    setTimeout(run, 200);
  }
})();
</script>
  <?php
}


/* =============================================================================
   20. SEARCH — In-page Find (FAB)
   ============================================================================= */

/**
 * Inject floating "Find on page" widget into single posts.
 *
 * Behaviour:
 *   - FAB appears bottom-right after user scrolls 30% of the article
 *   - Click FAB → expands into search bar with input, counter, ↑↓ nav, ✕
 *   - Type ≥2 chars → highlights all matches in .entry-content
 *     (debounce 200ms; max 200 matches to keep large posts performant)
 *   - ↑↓ scroll to prev/next match (smooth, viewport-centered)
 *   - Esc / ✕ close + clean up <mark>s
 *
 * Container scope: searches inside .entry-content / article main, NOT the
 * whole page (avoids matching menu, footer, etc).
 *
 * Added 2026-05-09 — separate from header global search modal.
 */
add_action( 'wp_footer', 'noircraftlab_render_page_find', 20 );

function noircraftlab_render_page_find(): void {
  if ( ! is_singular() ) {
    return;
  }
  ?>
<!-- ═══════════════════════════════════════════════════════════════════
     IN-PAGE FIND  (FAB + bar)
     ═══════════════════════════════════════════════════════════════ -->
<div id="noircraftlab-page-find" class="page-find" role="region" aria-label="<?php esc_attr_e( 'Find on page', 'noircraftlab' ); ?>">

  <!-- FAB (collapsed state) -->
  <button type="button"
          class="page-find__fab"
          aria-label="<?php esc_attr_e( 'Find on page', 'noircraftlab' ); ?>"
          data-page-find-open>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         aria-hidden="true" focusable="false">
      <circle cx="11" cy="11" r="8"/>
      <path d="m21 21-4.35-4.35"/>
    </svg>
  </button>

  <!-- Bar (expanded state) -->
  <div class="page-find__bar" role="search">
    <span class="page-find__bar-icon" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false">
        <circle cx="11" cy="11" r="8"/>
        <path d="m21 21-4.35-4.35"/>
      </svg>
    </span>

    <input type="search"
           class="page-find__input"
           placeholder="<?php esc_attr_e( 'Find on page', 'noircraftlab' ); ?>"
           aria-label="<?php esc_attr_e( 'Find on page', 'noircraftlab' ); ?>"
           autocomplete="off"
           spellcheck="false">

    <span class="page-find__counter" aria-live="polite" aria-atomic="true"></span>

    <button type="button"
            class="page-find__nav page-find__nav--prev"
            aria-label="<?php esc_attr_e( 'Previous match', 'noircraftlab' ); ?>"
            disabled>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" focusable="false">
        <polyline points="18 15 12 9 6 15"/>
      </svg>
    </button>

    <button type="button"
            class="page-find__nav page-find__nav--next"
            aria-label="<?php esc_attr_e( 'Next match', 'noircraftlab' ); ?>"
            disabled>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" focusable="false">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </button>

    <button type="button"
            class="page-find__close"
            aria-label="<?php esc_attr_e( 'Close search', 'noircraftlab' ); ?>"
            data-page-find-close>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" focusable="false">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>
</div>

<script>
window.noircraftlabPageFind = {
  i18n: {
    counter:    <?php echo wp_json_encode( __( '%1$s of %2$s', 'noircraftlab' ) ); ?>,
    noMatches:  <?php echo wp_json_encode( __( 'No matches',    'noircraftlab' ) ); ?>
  }
};
</script>
<script>
(function () {
  'use strict';

  /* ── Elements ───────────────────────────────────────── */
  var root      = document.getElementById('noircraftlab-page-find');
  if (!root) return;
  var fab       = root.querySelector('.page-find__fab');
  var bar       = root.querySelector('.page-find__bar');
  var input     = root.querySelector('.page-find__input');
  var counter   = root.querySelector('.page-find__counter');
  var btnPrev   = root.querySelector('.page-find__nav--prev');
  var btnNext   = root.querySelector('.page-find__nav--next');
  var btnClose  = root.querySelector('.page-find__close');

  /* Scope: search only inside main content */
  var content   = document.querySelector('.entry-content, .single-post__content, article .post-content, main');
  if (!content) return;

  var CFG       = window.noircraftlabPageFind || {};
  var i18n      = CFG.i18n || {};
  var MIN_CHARS = 2;
  var DEBOUNCE  = 200;
  var MAX_HITS  = 200;
  var SCROLL_TRIGGER_PCT = 0.30;

  var state = {
    visible: false,
    open:    false,
    hits:    [],   /* array of <mark> elements */
    current: -1
  };
  var debounceId;

  /* ── Visibility on scroll (30% trigger) ─────────────── */
  function checkScroll() {
    var rect = content.getBoundingClientRect();
    var contentTop    = window.scrollY + rect.top;
    var contentHeight = content.offsetHeight;
    var scrolled      = window.scrollY - contentTop;
    var pct           = scrolled / contentHeight;

    if (pct >= SCROLL_TRIGGER_PCT) {
      if (!state.visible) {
        state.visible = true;
        root.classList.add('page-find--visible');
      }
    } else {
      if (state.visible && !state.open) {
        state.visible = false;
        root.classList.remove('page-find--visible');
      }
    }
  }
  window.addEventListener('scroll', checkScroll, { passive: true });
  checkScroll();

  /* ── Open / Close ───────────────────────────────────── */
  function open() {
    state.open = true;
    state.visible = true;
    root.classList.add('page-find--visible', 'page-find--open');
    setTimeout(function () { input && input.focus(); }, 50);
  }

  function close() {
    state.open = false;
    root.classList.remove('page-find--open');
    if (input) input.value = '';
    clearHighlights();
    updateCounter(0, 0);
    /* Re-evaluate visibility based on scroll position */
    checkScroll();
  }

  fab.addEventListener('click', open);
  btnClose.addEventListener('click', close);

  /* ── Highlight management ───────────────────────────── */
  function clearHighlights() {
    state.hits.forEach(function (m) {
      if (!m.parentNode) return;
      var parent = m.parentNode;
      var text = document.createTextNode(m.textContent || '');
      parent.replaceChild(text, m);
      parent.normalize();
    });
    state.hits = [];
    state.current = -1;
  }

  function highlightAll(query) {
    clearHighlights();
    if (!query || query.length < MIN_CHARS) return;

    var qLower = query.toLowerCase();
    var qLen   = query.length;

    /* Collect text nodes first to avoid mutating during iteration */
    var nodes = [];
    var walker = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, {
      acceptNode: function (node) {
        if (!node.nodeValue) return NodeFilter.FILTER_REJECT;
        var p = node.parentNode;
        if (!p) return NodeFilter.FILTER_REJECT;
        var tag = (p.tagName || '').toLowerCase();
        if (tag === 'script' || tag === 'style' || tag === 'noscript') {
          return NodeFilter.FILTER_REJECT;
        }
        /* Skip nodes already inside our own marks (prevent re-highlight) */
        if (p.classList && p.classList.contains('noircraftlab-page-match')) {
          return NodeFilter.FILTER_REJECT;
        }
        return NodeFilter.FILTER_ACCEPT;
      }
    });
    var n;
    while ((n = walker.nextNode())) nodes.push(n);

    /* Wrap occurrences */
    var totalHits = 0;
    for (var i = 0; i < nodes.length && totalHits < MAX_HITS; i++) {
      var node = nodes[i];
      var text = node.nodeValue;
      var lower = text.toLowerCase();
      var idx = lower.indexOf(qLower);
      if (idx === -1) continue;

      var fragment = document.createDocumentFragment();
      var lastIdx = 0;
      while (idx !== -1 && totalHits < MAX_HITS) {
        if (idx > lastIdx) {
          fragment.appendChild(document.createTextNode(text.slice(lastIdx, idx)));
        }
        var mark = document.createElement('mark');
        mark.className = 'noircraftlab-page-match';
        mark.textContent = text.slice(idx, idx + qLen);
        fragment.appendChild(mark);
        state.hits.push(mark);
        totalHits++;
        lastIdx = idx + qLen;
        idx = lower.indexOf(qLower, lastIdx);
      }
      if (lastIdx < text.length) {
        fragment.appendChild(document.createTextNode(text.slice(lastIdx)));
      }
      node.parentNode.replaceChild(fragment, node);
    }
  }

  function setCurrent(idx) {
    if (state.hits.length === 0) {
      state.current = -1;
      return;
    }
    /* Wrap around */
    if (idx < 0) idx = state.hits.length - 1;
    if (idx >= state.hits.length) idx = 0;

    /* Update class */
    state.hits.forEach(function (m, i) {
      m.classList.toggle('noircraftlab-page-match--current', i === idx);
    });
    state.current = idx;

    /* Smooth scroll into view */
    var rect = state.hits[idx].getBoundingClientRect();
    var top = rect.top + window.scrollY - (window.innerHeight / 2);
    window.scrollTo({ top: top, behavior: 'smooth' });
  }

  function updateCounter(current, total) {
    if (!counter) return;
    if (total === 0) {
      var query = input ? input.value.trim() : '';
      counter.textContent = query.length >= MIN_CHARS ? (i18n.noMatches || 'No matches') : '';
    } else {
      var tpl = i18n.counter || '%1$s of %2$s';
      counter.textContent = tpl.replace('%1$s', String(current + 1)).replace('%2$s', String(total));
    }
    btnPrev.disabled = total === 0;
    btnNext.disabled = total === 0;
  }

  /* ── Input handling ─────────────────────────────────── */
  input.addEventListener('input', function () {
    clearTimeout(debounceId);
    var query = input.value.trim();
    if (query.length < MIN_CHARS) {
      clearHighlights();
      updateCounter(0, 0);
      return;
    }
    debounceId = setTimeout(function () {
      highlightAll(query);
      if (state.hits.length > 0) {
        setCurrent(0);
        updateCounter(0, state.hits.length);
      } else {
        updateCounter(0, 0);
      }
    }, DEBOUNCE);
  });

  /* Enter in input → next match */
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      if (state.hits.length > 0) {
        setCurrent(state.current + (e.shiftKey ? -1 : 1));
        updateCounter(state.current, state.hits.length);
      }
    }
    if (e.key === 'Escape') {
      e.preventDefault();
      close();
    }
  });

  btnPrev.addEventListener('click', function () {
    if (state.hits.length === 0) return;
    setCurrent(state.current - 1);
    updateCounter(state.current, state.hits.length);
    input && input.focus();
  });

  btnNext.addEventListener('click', function () {
    if (state.hits.length === 0) return;
    setCurrent(state.current + 1);
    updateCounter(state.current, state.hits.length);
    input && input.focus();
  });

  /* Global Esc closes (when bar is open) */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && state.open) {
      close();
    }
  });

  /* BFCache restore — clean up any stale state */
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) return;
    if (state.open) close();
  });
})();
</script>
  <?php
}


/* =============================================================================
   21. CASE STUDIES — CPT + Taxonomy
   =============================================================================
   Custom Post Type for portfolio case studies.
     - Slug: cases  →  URLs: /cases/{slug}/, /uk/cases/{slug}/, /ru/cases/{slug}/
     - Archive: /cases/  (rendered via archive-case_study.php)
     - Polylang: enable in Languages → Settings → CPT & Taxonomies module

   Custom Taxonomy `case_tag` — non-hierarchical tags-like labels:
     WordPress, Multilingual, Editorial, SEO, E-commerce, etc.
   ============================================================================= */

/**
 * Register the case_study CPT.
 */
function noircraftlab_register_case_study_cpt() {

	$labels = array(
		'name'                  => _x( 'Case Studies', 'Post type general name', 'noircraftlab' ),
		'singular_name'         => _x( 'Case Study', 'Post type singular name', 'noircraftlab' ),
		'menu_name'             => _x( 'Case Studies', 'Admin Menu text', 'noircraftlab' ),
		'name_admin_bar'        => _x( 'Case Study', 'Add New on Toolbar', 'noircraftlab' ),
		'add_new'               => __( 'Add New', 'noircraftlab' ),
		'add_new_item'          => __( 'Add New Case Study', 'noircraftlab' ),
		'new_item'              => __( 'New Case Study', 'noircraftlab' ),
		'edit_item'             => __( 'Edit Case Study', 'noircraftlab' ),
		'view_item'             => __( 'View Case Study', 'noircraftlab' ),
		'view_items'            => __( 'View Case Studies', 'noircraftlab' ),
		'all_items'             => __( 'All Case Studies', 'noircraftlab' ),
		'search_items'          => __( 'Search Case Studies', 'noircraftlab' ),
		'not_found'             => __( 'No case studies found.', 'noircraftlab' ),
		'not_found_in_trash'    => __( 'No case studies found in Trash.', 'noircraftlab' ),
		'featured_image'        => _x( 'Case study cover image', 'Overrides Featured Image label', 'noircraftlab' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides Set featured image label', 'noircraftlab' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides Remove featured image label', 'noircraftlab' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides Use as featured image label', 'noircraftlab' ),
		'archives'              => _x( 'Case Studies archive', 'Post type archive label', 'noircraftlab' ),
		'insert_into_item'      => _x( 'Insert into case study', 'Overrides Insert into post', 'noircraftlab' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this case study', 'Overrides Uploaded to this post', 'noircraftlab' ),
		'filter_items_list'     => _x( 'Filter case studies list', 'Screen reader text', 'noircraftlab' ),
		'items_list_navigation' => _x( 'Case studies list navigation', 'Screen reader text', 'noircraftlab' ),
		'items_list'            => _x( 'Case studies list', 'Screen reader text', 'noircraftlab' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Portfolio case studies showcasing Noircraft Lab project work.', 'noircraftlab' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,                      // Gutenberg + REST API
		'menu_position'      => 5,                         // below Posts
		'menu_icon'          => 'dashicons-portfolio',
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'has_archive'        => 'cases',                   // /cases/
		'rewrite'            => array(
			'slug'       => 'cases',
			'with_front' => false,
		),
		'query_var'          => true,
		'supports'           => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'revisions',
			'custom-fields',
			'page-attributes',
		),
		'taxonomies'         => array( 'case_tag' ),
	);

	register_post_type( 'case_study', $args );
}
add_action( 'init', 'noircraftlab_register_case_study_cpt', 0 );


/**
 * Register the case_tag taxonomy (non-hierarchical, tags-like).
 */
function noircraftlab_register_case_tag_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Case Tags', 'taxonomy general name', 'noircraftlab' ),
		'singular_name'              => _x( 'Case Tag', 'taxonomy singular name', 'noircraftlab' ),
		'search_items'               => __( 'Search Case Tags', 'noircraftlab' ),
		'popular_items'              => __( 'Popular Case Tags', 'noircraftlab' ),
		'all_items'                  => __( 'All Case Tags', 'noircraftlab' ),
		'edit_item'                  => __( 'Edit Case Tag', 'noircraftlab' ),
		'update_item'                => __( 'Update Case Tag', 'noircraftlab' ),
		'add_new_item'               => __( 'Add New Case Tag', 'noircraftlab' ),
		'new_item_name'              => __( 'New Case Tag', 'noircraftlab' ),
		'separate_items_with_commas' => __( 'Separate case tags with commas', 'noircraftlab' ),
		'add_or_remove_items'        => __( 'Add or remove case tags', 'noircraftlab' ),
		'choose_from_most_used'      => __( 'Choose from the most used case tags', 'noircraftlab' ),
		'not_found'                  => __( 'No case tags found.', 'noircraftlab' ),
		'menu_name'                  => __( 'Case Tags', 'noircraftlab' ),
	);

	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'case-tag' ),
	);

	register_taxonomy( 'case_tag', array( 'case_study' ), $args );
}
add_action( 'init', 'noircraftlab_register_case_tag_taxonomy', 0 );


/**
 * Flush rewrite rules on theme (re)activation — safety net.
 * Primary flush is manual via Settings → Permalinks → Save Changes
 * the first time after this code is deployed.
 */
function noircraftlab_flush_rewrites_on_theme_switch() {
	noircraftlab_register_case_study_cpt();
	noircraftlab_register_case_tag_taxonomy();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'noircraftlab_flush_rewrites_on_theme_switch' );


/* =============================================================================
   HOMEPAGE (front-page.php) — STRING TRANSLATIONS
   Added 2026-05-22 (Sub-session B1). Supplements noircraftlab_get_translations()
   for homepage-only copy, kept separate so homepage text can grow
   (B1.1 Hero → B1.7 Contact) without touching the core translation array.
   Source language: English — keys MUST match __()/_e() calls in front-page.php.
   ============================================================================= */

function noircraftlab_home_translations(): array {
	static $cache = null;
	if ( $cache !== null ) {
		return $cache;
	}

	$locale = determine_locale();

	$all = array(
		'uk' => array(
			// ── B1.1 Hero ──────────────────────────────────────────────
			'WORDPRESS · MULTILINGUAL · SEO'
				=> array( 'WORDPRESS · БАГАТОМОВНІСТЬ · SEO' ),
			"Editorial WordPress sites that rank, convert, and don't break."
				=> array( 'WordPress-сайти редакторського рівня, що ранжуються, конвертують і не ламаються.' ),
			'Custom themes, multilingual builds, and technical SEO — for brands that take content seriously. Designed, built, and shipped by one developer.'
				=> array( 'Індивідуальні теми, багатомовні збірки й технічний SEO — для брендів, які серйозно ставляться до контенту. Дизайн, розробка та запуск — один розробник.' ),
			'Start a project'
				=> array( 'Почати проєкт' ),
			'See the work'
				=> array( 'Дивитися роботи' ),

			// ── B1.2 Services ──────────────────────────────────────────
			'Services'
				=> array( 'Послуги' ),
			'WordPress development'
				=> array( 'Розробка на WordPress' ),
			'Custom themes built from scratch — no page builders. Performance-first, accessible, easy to maintain. From editorial blogs to multilingual business sites.'
				=> array( 'Індивідуальні теми з нуля — без конструкторів. Швидкі, доступні та зручні в підтримці. Від редакторських блогів до багатомовних бізнес-сайтів.' ),
			'Content & SEO'
				=> array( 'Контент і SEO' ),
			'Long-form articles in Russian and Ukrainian — AI-accelerated, human-edited. Rank Math setup, clean structure, and internal linking that search engines reward.'
				=> array( 'Лонгріди російською та українською — з прискоренням ШІ та ручним редагуванням. Налаштування Rank Math, чиста структура й перелінкування, яке цінують пошукові системи.' ),
			'Multilingual setup'
				=> array( 'Багатомовність' ),
			'Polylang and WPML integration with correct hreflang, language routing, and content localization for EN, UK, and RU audiences.'
				=> array( 'Інтеграція Polylang та WPML з коректним hreflang, маршрутизацією мов і локалізацією контенту для аудиторій EN, UK і RU.' ),

			// ── B1.3 Approach ──────────────────────────────────────────
			'Approach'
				=> array( 'Підхід' ),
			'Discovery'
				=> array( 'Аналіз' ),
			'Audit the existing site, clarify goals, and define a clear scope before any code is written.'
				=> array( 'Аудит наявного сайту, уточнення цілей і чітке визначення обсягу робіт ще до першого рядка коду.' ),
			'Design'
				=> array( 'Дизайн' ),
			'Wireframes, design tokens, and content structure — approved before development starts.'
				=> array( 'Вайрфрейми, дизайн-токени та структура контенту — із затвердженням до старту розробки.' ),
			'Build'
				=> array( 'Розробка' ),
			'A custom WordPress theme with multilingual support and technical SEO built in from day one.'
				=> array( 'Кастомна тема WordPress із багатомовністю та технічним SEO, закладеними з першого дня.' ),
			'Launch & Support'
				=> array( 'Запуск і підтримка' ),
			'QA, a performance audit, and deployment — with 30 days of post-launch fixes included.'
				=> array( 'QA, аудит продуктивності та деплой — із 30 днями правок після запуску включно.' ),

			// ── B1.4 Cases ─────────────────────────────────────────────
			'Selected work'
				=> array( 'Вибрані роботи' ),
			'Case studies are on the way.'
				=> array( 'Кейси вже в роботі.' ),
			'Detailed write-ups of recent projects are being prepared.'
				=> array( 'Детальні розбори нещодавніх проєктів зараз готуються.' ),
			'Read case study'
				=> array( 'Відкрити кейс' ),
			'View all cases'
				=> array( 'Усі кейси' ),

			// ── B1.5 About ─────────────────────────────────────────────
			'About'
				=> array( 'Про мене' ),
			"Hi, I'm Roman."
				=> array( 'Привіт, я Роман.' ),
			"I'm the developer behind Noircraft Lab — a one-person practice building editorial WordPress sites for content-first brands. Design, development, content, and SEO under a single point of accountability."
				=> array( 'Я — розробник Noircraft Lab. Це практика однієї людини: редакторські сайти на WordPress для брендів, де контент на першому місці. Дизайн, розробка, контент і SEO — під єдиною відповідальністю.' ),
			'I work AI-augmented and finish everything by hand — clean code, no page builders, and multilingual done as a standard, not an afterthought.'
				=> array( 'Працюю з підтримкою ШІ й доводжу все вручну — чистий код, без конструкторів сторінок, а багатомовність закладена як стандарт, а не додана в останній момент.' ),
			'Solo developer — full accountability'
				=> array( 'Solo-розробник — повна відповідальність' ),
			'Custom WordPress — no page builders'
				=> array( 'Кастомний WordPress — без конструкторів' ),
			'AI-augmented, human-finished'
				=> array( 'З підтримкою ШІ, доведено вручну' ),
			'More about me'
				=> array( 'Детальніше про мене' ),

			// ── B1.7 Contact ───────────────────────────────────────────
			'Contact'
				=> array( 'Контакти' ),
			"Let's build something."
				=> array( 'Створімо щось разом.' ),
			"Tell me about your project and what you're aiming for — I usually reply within a day."
				=> array( 'Розкажіть про проєкт і чого хочете досягти — зазвичай відповідаю протягом дня.' ),
			'Get in touch'
				=> array( 'Зв’язатися' ),
		),
		'ru_RU' => array(
			// ── B1.1 Hero ──────────────────────────────────────────────
			'WORDPRESS · MULTILINGUAL · SEO'
				=> array( 'WORDPRESS · МУЛЬТИЯЗЫЧНОСТЬ · SEO' ),
			"Editorial WordPress sites that rank, convert, and don't break."
				=> array( 'WordPress-сайты редакторского уровня, которые ранжируются, конвертируют и не ломаются.' ),
			'Custom themes, multilingual builds, and technical SEO — for brands that take content seriously. Designed, built, and shipped by one developer.'
				=> array( 'Индивидуальные темы, мультиязычные сборки и технический SEO — для брендов, которые серьёзно относятся к контенту. Дизайн, разработка и запуск — один разработчик.' ),
			'Start a project'
				=> array( 'Начать проект' ),
			'See the work'
				=> array( 'Смотреть работы' ),

			// ── B1.2 Services ──────────────────────────────────────────
			'Services'
				=> array( 'Услуги' ),
			'WordPress development'
				=> array( 'Разработка на WordPress' ),
			'Custom themes built from scratch — no page builders. Performance-first, accessible, easy to maintain. From editorial blogs to multilingual business sites.'
				=> array( 'Индивидуальные темы с нуля — без конструкторов. Быстрые, доступные и удобные в поддержке. От редакторских блогов до мультиязычных бизнес-сайтов.' ),
			'Content & SEO'
				=> array( 'Контент и SEO' ),
			'Long-form articles in Russian and Ukrainian — AI-accelerated, human-edited. Rank Math setup, clean structure, and internal linking that search engines reward.'
				=> array( 'Лонгриды на русском и украинском — с ускорением ИИ и ручной редактурой. Настройка Rank Math, чистая структура и перелинковка, которую ценят поисковики.' ),
			'Multilingual setup'
				=> array( 'Мультиязычность' ),
			'Polylang and WPML integration with correct hreflang, language routing, and content localization for EN, UK, and RU audiences.'
				=> array( 'Интеграция Polylang и WPML с корректным hreflang, маршрутизацией языков и локализацией контента для аудиторий EN, UK и RU.' ),

			// ── B1.3 Approach ──────────────────────────────────────────
			'Approach'
				=> array( 'Подход' ),
			'Discovery'
				=> array( 'Анализ' ),
			'Audit the existing site, clarify goals, and define a clear scope before any code is written.'
				=> array( 'Аудит текущего сайта, уточнение целей и чёткое определение объёма работ ещё до первой строки кода.' ),
			'Design'
				=> array( 'Дизайн' ),
			'Wireframes, design tokens, and content structure — approved before development starts.'
				=> array( 'Вайрфреймы, дизайн-токены и структура контента — с утверждением до старта разработки.' ),
			'Build'
				=> array( 'Разработка' ),
			'A custom WordPress theme with multilingual support and technical SEO built in from day one.'
				=> array( 'Кастомная тема WordPress с мультиязычностью и техническим SEO, заложенными с первого дня.' ),
			'Launch & Support'
				=> array( 'Запуск и поддержка' ),
			'QA, a performance audit, and deployment — with 30 days of post-launch fixes included.'
				=> array( 'QA, аудит производительности и деплой — с 30 днями правок после запуска включительно.' ),

			// ── B1.4 Cases ─────────────────────────────────────────────
			'Selected work'
				=> array( 'Избранные работы' ),
			'Case studies are on the way.'
				=> array( 'Кейсы уже в работе.' ),
			'Detailed write-ups of recent projects are being prepared.'
				=> array( 'Подробные разборы недавних проектов сейчас готовятся.' ),
			'Read case study'
				=> array( 'Открыть кейс' ),
			'View all cases'
				=> array( 'Все кейсы' ),

			// ── B1.5 About ─────────────────────────────────────────────
			'About'
				=> array( 'Обо мне' ),
			"Hi, I'm Roman."
				=> array( 'Привет, я Роман.' ),
			"I'm the developer behind Noircraft Lab — a one-person practice building editorial WordPress sites for content-first brands. Design, development, content, and SEO under a single point of accountability."
				=> array( 'Я — разработчик Noircraft Lab. Это практика одного человека: редакторские сайты на WordPress для брендов, где контент на первом месте. Дизайн, разработка, контент и SEO — под единой ответственностью.' ),
			'I work AI-augmented and finish everything by hand — clean code, no page builders, and multilingual done as a standard, not an afterthought.'
				=> array( 'Работаю с поддержкой ИИ и довожу всё вручную — чистый код, без конструкторов страниц, а мультиязычность заложена как стандарт, а не добавлена в последний момент.' ),
			'Solo developer — full accountability'
				=> array( 'Solo-разработчик — полная ответственность' ),
			'Custom WordPress — no page builders'
				=> array( 'Кастомный WordPress — без конструкторов' ),
			'AI-augmented, human-finished'
				=> array( 'С поддержкой ИИ, доводка вручную' ),
			'More about me'
				=> array( 'Подробнее обо мне' ),

			// ── B1.7 Contact ───────────────────────────────────────────
			'Contact'
				=> array( 'Контакты' ),
			"Let's build something."
				=> array( 'Давайте что-нибудь создадим.' ),
			"Tell me about your project and what you're aiming for — I usually reply within a day."
				=> array( 'Расскажите о проекте и целях — обычно отвечаю в течение дня.' ),
			'Get in touch'
				=> array( 'Связаться' ),
		),
	);

	$cache = $all[ $locale ] ?? array();
	return $cache;
}

add_filter( 'gettext', 'noircraftlab_home_gettext', 11, 3 );
function noircraftlab_home_gettext( $translation, $text, $domain ) {
	if ( 'noircraftlab' !== $domain ) {
		return $translation;
	}
	$t = noircraftlab_home_translations();
	return ( isset( $t[ $text ][0] ) && '' !== $t[ $text ][0] ) ? $t[ $text ][0] : $translation;
}
