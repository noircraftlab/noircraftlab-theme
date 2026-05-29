<?php
/**
 * header.php — Noircraft Lab Theme
 *
 * Template: Site header with sticky nav, logo, multilingual switcher (Polylang),
 *           primary navigation, search trigger, and mobile drawer.
 *
 * @package NoircraftLab
 * @version 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <?php /*
  ── SEO: hreflang alternate links (Polylang auto-generates these,
     but we add a manual fallback block for themes that need it).
     Polylang / RankMath / Yoast hook their output into wp_head().
  */ ?>

  <link rel="profile" href="https://gmpg.org/xfn/11">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<!-- ── Accessibility: Skip Link ─────────────────────────────────── -->
<a class="skip-link" href="#main-content">
  <?php esc_html_e( 'Skip to content', 'noircraftlab' ); ?>
</a>

<?php
/**
 * ── Top Bar / Breaking News Ticker
 *    Optional — controlled by theme option / ACF field 'noircraftlab_topbar_text'.
 *    Remove this block if not using a ticker.
 */
$topbar_text = get_option( 'noircraftlab_topbar_text', '' );
if ( $topbar_text ) : ?>

<div class="site-topbar" role="complementary" aria-label="<?php esc_attr_e( 'Breaking news', 'noircraftlab' ); ?>">
  <div class="container">
    <span class="topbar-label"><?php esc_html_e( 'Breaking', 'noircraftlab' ); ?></span>
    <div class="topbar-ticker">
      <?php echo esc_html( $topbar_text ); ?>
    </div>
  </div>
</div>

<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════════════
     MAIN SITE HEADER
     ══════════════════════════════════════════════════════════════════ -->
<header
  id="site-header"
  class="site-header"
  role="banner"
  itemscope
  itemtype="https://schema.org/WPHeader"
>
  <div class="container">

    <!-- ── Logo (SVG mark via noircraftlab_logo() helper) ─────────── -->
    <?php
    /*
     * Logo rendered via noircraftlab_logo() helper (functions.php § 13):
     *   - If user uploaded a Custom Logo via Customizer → use that image
     *   - Otherwise → inline SVG fallback (Variant A — two-tone wordmark)
     * To swap the built-in logo: edit noircraftlab_logo_svg() OR upload via
     * Appearance → Customize → Site Identity → Logo.
     */
    echo noircraftlab_logo( 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput — SVG output sanitized inside helper
    ?>

    <!-- ── Primary Navigation ─────────────────────────────────────── -->
    <nav
      id="site-navigation"
      class="site-nav"
      role="navigation"
      aria-label="<?php esc_attr_e( 'Primary navigation', 'noircraftlab' ); ?>"
      itemscope
      itemtype="https://schema.org/SiteNavigationElement"
    >
      <?php
      /*
       * Registered menu location: 'primary'.
       * Fallback: render the 7 hardcoded category links if no menu is assigned.
       * All menu items are translatable via Polylang / WPML string translation.
       */
      if ( has_nav_menu( 'primary' ) ) :
        wp_nav_menu( [
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => '',
          'fallback_cb'    => false,
          'depth'          => 2,
          'walker'         => null, // Set to a custom walker class if needed in the future.
          'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        ] );
      else : ?>
        <?php
        /*
         * No menu assigned. Renders nothing — clean visual placeholder.
         * To populate: WP-admin → Appearance → Menus → create menu → assign
         * to "Primary Navigation" location.
         */
        ?>
      <?php endif; ?>
    </nav><!-- #site-navigation -->

    <!-- ── Header Controls: Search + Language + Burger ────────────── -->
    <div class="header-controls">

      <!-- Search Trigger -->
      <button
        class="header-search-btn"
        aria-label="<?php esc_attr_e( 'Open search', 'noircraftlab' ); ?>"
        aria-expanded="false"
        aria-controls="header-search-modal"
        id="search-toggle"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true" focusable="false">
          <circle cx="11" cy="11" r="8"/>
          <path d="m21 21-4.35-4.35"/>
        </svg>
      </button>

      <!-- ── Language Switcher (Polylang) ─────────────────────────── -->
      <?php
      /*
       * Polylang: pll_the_languages() outputs <li> items.
       * We wrap in a custom container and re-style via .lang-switcher.
       *
       * Requires Polylang plugin active.
       * Fallback: manual links shown if function doesn't exist.
       */
      ?>
      <nav
        class="lang-switcher"
        aria-label="<?php esc_attr_e( 'Language switcher', 'noircraftlab' ); ?>"
        role="navigation"
      >
        <?php if ( function_exists( 'pll_the_languages' ) ) : ?>

          <?php
          /*
           * Polylang — render language links.
           * Options:
           *   show_flags  = 0   — no flag images, text-only
           *   show_names  = 1   — display language name abbreviation
           *   dropdown    = 0   — flat list (we style it ourselves)
           *   hide_if_empty = 0 — always show all languages
           */
          $pll_languages = pll_the_languages( [
            'show_flags'    => 0,
            'show_names'    => 1,
            'display_names_as' => 'slug', // Shows RU / EN / UA
            'hide_if_empty' => 0,
            'raw'           => 1,         // Returns array instead of echoing
          ] );

          if ( ! empty( $pll_languages ) ) :
            foreach ( $pll_languages as $lang ) :
              $is_current = $lang['current_lang'] ? ' active' : '';
              $is_no_trans = $lang['no_translation'] ? ' lang-no-translation' : '';
            ?>
            <a
              href="<?php echo esc_url( $lang['url'] ); ?>"
              class="<?php echo esc_attr( ltrim( $is_current . $is_no_trans ) ); ?>"
              hreflang="<?php echo esc_attr( $lang['locale'] ); ?>"
              lang="<?php echo esc_attr( $lang['locale'] ); ?>"
              <?php echo $lang['current_lang'] ? 'aria-current="page"' : ''; ?>
            >
              <?php echo esc_html( strtoupper( $lang['slug'] ) ); ?>
            </a>
            <?php
            endforeach;
          endif;

        else :
          /*
           * Fallback if Polylang is not active.
           * Static links — update hrefs to match your site structure.
           */
          $current_lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : 'ru';
          ?>
          <a href="/ru/" hreflang="ru" lang="ru" <?php echo $current_lang === 'ru' ? 'class="active" aria-current="page"' : ''; ?>>RU</a>
          <a href="/en/" hreflang="en" lang="en" <?php echo $current_lang === 'en' ? 'class="active" aria-current="page"' : ''; ?>>EN</a>
          <a href="/ua/" hreflang="uk" lang="uk" <?php echo $current_lang === 'ua' ? 'class="active" aria-current="page"' : ''; ?>>UA</a>
        <?php endif; ?>
      </nav><!-- /.lang-switcher -->

      <!-- Mobile Menu Toggle ──────────────────────────────────────── -->
      <button
        class="menu-toggle"
        id="menu-toggle"
        aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'noircraftlab' ); ?>"
        aria-expanded="false"
        aria-controls="mobile-nav"
      >
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </button>

    </div><!-- /.header-controls -->

  </div><!-- /.container -->
</header><!-- #site-header -->

<!-- ─────────────────────────────────────────────────────────────────
     HEADER SEARCH MODAL
     Triggered by #search-toggle button. Submits to home_url() — Polylang
     auto-routes to the correct language search results page.
     ───────────────────────────────────────────────────────────────── -->
<div
  id="header-search-modal"
  class="search-modal"
  role="dialog"
  aria-modal="true"
  aria-hidden="true"
  aria-labelledby="search-modal-title"
>
  <div class="search-modal__overlay" data-search-close></div>

  <div class="search-modal__inner" role="document">

    <button
      type="button"
      class="search-modal__close"
      aria-label="<?php esc_attr_e( 'Close search', 'noircraftlab' ); ?>"
      data-search-close
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" focusable="false">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>

    <div class="search-modal__eyebrow"><?php esc_html_e( 'Search', 'noircraftlab' ); ?></div>
    <h2 id="search-modal-title" class="search-modal__title">
      <?php esc_html_e( 'What are you looking for?', 'noircraftlab' ); ?>
    </h2>

    <form
      role="search"
      method="get"
      action="<?php echo esc_url( home_url( '/' ) ); ?>"
      class="search-modal__form"
    >
      <label for="search-modal-input" class="visually-hidden">
        <?php esc_html_e( 'Search', 'noircraftlab' ); ?>
      </label>
      <input
        id="search-modal-input"
        type="search"
        name="s"
        class="search-modal__input"
        autocomplete="off"
        spellcheck="false"
        aria-controls="search-modal-suggestions"
        aria-autocomplete="list"
      >
      <button
        type="submit"
        class="search-modal__submit"
        aria-label="<?php esc_attr_e( 'Search', 'noircraftlab' ); ?>"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true" focusable="false">
          <line x1="5" y1="12" x2="19" y2="12"/>
          <polyline points="12 5 19 12 12 19"/>
        </svg>
      </button>
    </form>

    <!-- Live AJAX suggestions container (populated by JS) -->
    <div
      id="search-modal-suggestions"
      class="search-suggestions"
      role="listbox"
      aria-label="<?php esc_attr_e( 'Search suggestions', 'noircraftlab' ); ?>"
      hidden
    ></div>

  </div><!-- /.search-modal__inner -->
</div><!-- #header-search-modal -->

<!-- ─────────────────────────────────────────────────────────────────
     MOBILE NAVIGATION DRAWER
     ───────────────────────────────────────────────────────────────── -->
<div
  id="mobile-nav"
  class="mobile-nav"
  role="dialog"
  aria-modal="false"
  aria-label="<?php esc_attr_e( 'Mobile navigation', 'noircraftlab' ); ?>"
  aria-hidden="true"
>
  <?php
  if ( has_nav_menu( 'mobile' ) ) :
    wp_nav_menu( [
      'theme_location' => 'mobile',
      'container'      => false,
      'menu_class'     => '',
      'fallback_cb'    => false,
      'depth'          => 1,
      'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    ] );
  else :
    /*
     * No menu assigned to "Mobile Navigation". Empty placeholder.
     * To populate: WP-admin → Appearance → Menus → assign to Mobile Navigation.
     */
  endif; ?>

  <!-- Mobile: Language switcher repeat -->
  <nav class="lang-switcher" aria-label="<?php esc_attr_e( 'Language switcher', 'noircraftlab' ); ?>">
    <?php if ( function_exists( 'pll_the_languages' ) ) :
      $pll_mobile = pll_the_languages( [
        'show_flags'       => 0,
        'show_names'       => 1,
        'display_names_as' => 'slug',
        'hide_if_empty'    => 0,
        'raw'              => 1,
      ] );
      if ( ! empty( $pll_mobile ) ) :
        foreach ( $pll_mobile as $lang ) : ?>
          <a
            href="<?php echo esc_url( $lang['url'] ); ?>"
            hreflang="<?php echo esc_attr( $lang['locale'] ); ?>"
            <?php echo $lang['current_lang'] ? 'class="active" aria-current="page"' : ''; ?>
          ><?php echo esc_html( strtoupper( $lang['slug'] ) ); ?></a>
        <?php endforeach;
      endif;
    else : ?>
      <a href="/ru/" hreflang="ru">RU</a>
      <a href="/en/" hreflang="en">EN</a>
      <a href="/ua/" hreflang="uk">UA</a>
    <?php endif; ?>
  </nav>

</div><!-- #mobile-nav -->

<!-- ─────────────────────────────────────────────────────────────────
     MOBILE NAV OVERLAY (tap to close)
     ───────────────────────────────────────────────────────────────── -->
<div
  class="mobile-nav-overlay"
  id="mobile-nav-overlay"
  aria-hidden="true"
  style="
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 190;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
  "
></div>

<!-- ─────────────────────────────────────────────────────────────────
     HEADER JS — Mobile Menu + Sticky scroll class + Search modal + AJAX
     (Inline minimal JS — no jQuery dependency)
     ───────────────────────────────────────────────────────────────── -->
<?php
/**
 * Detect Polylang language prefix for REST API + "view all results" link.
 * Empty for default RU; '/en' for English; '/uk' for Ukrainian.
 */
$noircraftlab_lang_prefix = '';
if ( function_exists( 'pll_current_language' ) ) {
  $noircraftlab_lang_slug = pll_current_language( 'slug' );
  if ( $noircraftlab_lang_slug && $noircraftlab_lang_slug !== 'ru' ) {
    $noircraftlab_lang_prefix = '/' . $noircraftlab_lang_slug;
  }
}
?>
<script>
window.noircraftlabSearch = {
  langPrefix: <?php echo wp_json_encode( $noircraftlab_lang_prefix ); ?>,
  homeUrl:    <?php echo wp_json_encode( esc_url_raw( home_url( '/' ) ) ); ?>,
  i18n: {
    articles:    <?php echo wp_json_encode( __( 'Articles',    'noircraftlab' ) ); ?>,
    information: <?php echo wp_json_encode( __( 'Information', 'noircraftlab' ) ); ?>,
    searching:   <?php echo wp_json_encode( __( 'Searching…',  'noircraftlab' ) ); ?>,
    noResults:   <?php echo wp_json_encode( __( 'No results for &ldquo;%s&rdquo;.', 'noircraftlab' ) ); ?>,
    viewAll:     <?php echo wp_json_encode( __( 'View all results for &ldquo;%s&rdquo;', 'noircraftlab' ) ); ?>
  }
};
</script>
<script>
(function () {
  'use strict';

  /* ── Elements ────────────────────────────────────────── */
  var header        = document.getElementById('site-header');
  var toggle        = document.getElementById('menu-toggle');
  var mobileNav     = document.getElementById('mobile-nav');
  var overlay       = document.getElementById('mobile-nav-overlay');
  var searchToggle  = document.getElementById('search-toggle');
  var searchModal   = document.getElementById('header-search-modal');
  var searchInput   = document.getElementById('search-modal-input');
  var suggestions   = document.getElementById('search-modal-suggestions');

  /* ── Sticky header: scroll behavior (hide on down / show on up) ─────
   * - .scrolled class: visual treatment after small scroll (existing)
   * - .is-hidden class: header slides up when scrolling DOWN past TOP_OFFSET
   * - Always visible within first TOP_OFFSET px from top of page
   * - Does NOT hide while mobile menu or search modal is open
   * - Uses requestAnimationFrame to throttle to 60fps (smooth on mobile)
   */
  if (header) {
    var lastScrollY      = window.scrollY || 0;
    var SCROLL_THRESHOLD = 10;   /* min delta in px to trigger hide/show */
    var TOP_OFFSET       = 100;  /* always show within first N pixels */
    var ticking          = false;
    var initialRun       = true; /* skip hide-logic on first call after load */

    function updateHeader() {
      var currentY = window.scrollY || 0;

      /* .scrolled — kept for backdrop-blur intensity / shadow change */
      header.classList.toggle('scrolled', currentY > 20);

      /* Skip hide-logic on initial run (page may load mid-scroll
         via #anchor URL — false "scrolled down" delta would hide header). */
      if (initialRun) {
        initialRun = false;
        lastScrollY = currentY;
        ticking = false;
        return;
      }

      /* Always show near top of page */
      if (currentY < TOP_OFFSET) {
        header.classList.remove('is-hidden');
        lastScrollY = currentY;
        ticking = false;
        return;
      }

      /* Don't hide while burger menu or search modal is open */
      var menuOpen   = mobileNav   && mobileNav.classList.contains('open');
      var searchOpen = searchModal && searchModal.classList.contains('is-open');
      if (menuOpen || searchOpen) {
        header.classList.remove('is-hidden');
        lastScrollY = currentY;
        ticking = false;
        return;
      }

      var delta = currentY - lastScrollY;

      if (delta > SCROLL_THRESHOLD) {
        /* Scrolled DOWN beyond threshold — hide */
        header.classList.add('is-hidden');
      } else if (delta < 0) {
        /* Any scroll UP — show immediately */
        header.classList.remove('is-hidden');
      }

      lastScrollY = currentY;
      ticking = false;
    }

    function onScroll() {
      if (!ticking) {
        window.requestAnimationFrame(updateHeader);
        ticking = true;
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    updateHeader(); /* run on load */
  }

  /* ── Mobile drawer toggle ────────────────────────────── */
  function openMenu() {
    mobileNav.classList.add('open');
    mobileNav.setAttribute('aria-hidden', 'false');
    mobileNav.setAttribute('aria-modal', 'true');
    toggle.setAttribute('aria-expanded', 'true');
    overlay.style.opacity = '1';
    overlay.style.pointerEvents = 'all';
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    mobileNav.classList.remove('open');
    mobileNav.setAttribute('aria-hidden', 'true');
    mobileNav.setAttribute('aria-modal', 'false');
    toggle.setAttribute('aria-expanded', 'false');
    overlay.style.opacity = '0';
    overlay.style.pointerEvents = 'none';
    document.body.style.overflow = '';
  }

  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () {
      var isOpen = mobileNav.classList.contains('open');
      isOpen ? closeMenu() : openMenu();
    });
  }

  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }

  /* ── Search modal toggle ─────────────────────────────── */
  function openSearch() {
    if (!searchModal) return;
    searchModal.classList.add('is-open');
    searchModal.setAttribute('aria-hidden', 'false');
    if (searchToggle) searchToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    /* Focus input on next tick so transition kicks in first */
    setTimeout(function () {
      if (searchInput) searchInput.focus();
    }, 50);
  }

  function closeSearch() {
    if (!searchModal) return;
    searchModal.classList.remove('is-open', 'has-suggestions');
    searchModal.setAttribute('aria-hidden', 'true');
    if (searchToggle) searchToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    if (searchInput) searchInput.value = '';
    /* Reset live-search state directly (replaces MutationObserver) */
    if (NoircraftLabLiveSearch && NoircraftLabLiveSearch.reset) {
      NoircraftLabLiveSearch.reset();
    }
    if (searchToggle) searchToggle.focus();
  }

  if (searchToggle && searchModal) {
    searchToggle.addEventListener('click', openSearch);

    /* Close on overlay click, [×] button, or any [data-search-close] */
    var closeTriggers = searchModal.querySelectorAll('[data-search-close]');
    for (var i = 0; i < closeTriggers.length; i++) {
      closeTriggers[i].addEventListener('click', closeSearch);
    }

    /* Empty submit guard — don't redirect to /?s= with no query */
    var searchForm = searchModal.querySelector('.search-modal__form');
    if (searchForm) {
      searchForm.addEventListener('submit', function (e) {
        if (!searchInput || !searchInput.value.trim()) {
          e.preventDefault();
          searchInput && searchInput.focus();
        }
      });
    }
  }

  /* ── Live AJAX search (REST API) ─────────────────────── */
  var NoircraftLabLiveSearch = (function () {
    var MIN_CHARS  = 3;
    var DEBOUNCE   = 250;
    var CFG        = window.noircraftlabSearch || {};
    var i18n       = CFG.i18n || {};
    var langPrefix = CFG.langPrefix || '';
    var homeUrl    = CFG.homeUrl || '/';
    var debounceId, lastQuery = '', currentReq = 0;

    function escHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function escRegex(str) {
      return String(str).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function highlight(text, query) {
      var safe = escHtml(text);
      if (!query) return safe;
      try {
        var re = new RegExp('(' + escRegex(query) + ')', 'gi');
        return safe.replace(re, '<mark>$1</mark>');
      } catch (e) { return safe; }
    }

    /**
     * Append ?noircraftlab_q=QUERY to a permalink so the single-post page
     * can scroll-to-highlight the term. Mirrors search.php logic.
     * Handles URLs that already contain a query string.
     */
    function buildDeepLink(url, query) {
      if (!url || !query) return url || '#';
      var separator = url.indexOf('?') === -1 ? '?' : '&';
      return url + separator + 'noircraftlab_q=' + encodeURIComponent(query);
    }

    /**
     * Single REST call to /wp-json/noircraftlab/v1/search.
     * Returns flat array of items; we group client-side.
     * Polylang language filter handled server-side via 'lang' WP_Query arg.
     */
    function fetchAll(query) {
      var langSlug = langPrefix ? langPrefix.replace('/', '') : 'ru';
      var url = '/wp-json/noircraftlab/v1/search'
              + '?q='    + encodeURIComponent(query)
              + '&lang=' + encodeURIComponent(langSlug);
      return fetch(url, { credentials: 'same-origin' })
        .then(function (r) { return r.ok ? r.json() : []; })
        .then(function (items) { return Array.isArray(items) ? items : []; })
        .catch(function () { return []; });
    }

    function renderGroup(label, items, query) {
      if (!items || !items.length) return '';
      var lis = items.map(function (item) {
        var title    = item.title || '';
        var rawUrl   = item.link  || '#';
        var url      = buildDeepLink(rawUrl, query);
        var img      = item.thumb || null;
        var catSlug  = item.cat_slug || 'default';
        var thumb;
        if (img) {
          thumb = '<span class="search-suggestions__thumb" '
                + 'style="background-image:url(' + escHtml(img).replace(/"/g, '%22') + ')"></span>';
        } else {
          thumb = '<span class="search-suggestions__thumb search-suggestions__thumb--placeholder '
                + 'search-suggestions__thumb--cat-' + escHtml(catSlug) + '">'
                + '<span aria-hidden="true">F</span></span>';
        }
        return '<li role="option" class="search-suggestions__item">'
             +   '<a href="' + escHtml(url) + '" class="search-suggestions__link">'
             +     thumb
             +     '<span class="search-suggestions__title">' + highlight(title, query) + '</span>'
             +   '</a>'
             + '</li>';
      }).join('');
      return '<div class="search-suggestions__group">'
           +   '<h3 class="search-suggestions__group-title">' + escHtml(label) + '</h3>'
           +   '<ul class="search-suggestions__list">' + lis + '</ul>'
           + '</div>';
    }

    function renderEmpty(query) {
      var msg = (i18n.noResults || 'No results for "%s".').replace('%s', escHtml(query));
      return '<div class="search-suggestions__empty">' + msg + '</div>';
    }

    function renderViewAll(query) {
      var label = (i18n.viewAll || 'View all results for "%s"').replace('%s', escHtml(query));
      var url = homeUrl + '?s=' + encodeURIComponent(query);
      return '<a class="search-suggestions__view-all" href="' + escHtml(url) + '">'
           +   label + ' →'
           + '</a>';
    }

    function renderLoading() {
      return '<div class="search-suggestions__loading">' + escHtml(i18n.searching || 'Searching…') + '</div>';
    }

    function show(html) {
      if (!suggestions) return;
      suggestions.innerHTML = html;
      suggestions.hidden = false;
      searchModal.classList.add('has-suggestions');
    }

    function hide() {
      if (!suggestions) return;
      suggestions.hidden = true;
      suggestions.innerHTML = '';
      searchModal.classList.remove('has-suggestions');
    }

    function performSearch(query) {
      var reqId = ++currentReq;
      show(renderLoading());

      fetchAll(query).then(function (items) {
        if (reqId !== currentReq) return;       /* stale request — discard */

        /* Group by post type — ordering matches search.php buckets */
        var groups = { post: [], page: [] };
        for (var i = 0; i < items.length; i++) {
          var t = items[i].type;
          if (groups[t]) groups[t].push(items[i]);
        }

        var totalCount = items.length;
        if (totalCount === 0) {
          show(renderEmpty(query) + renderViewAll(query));
          return;
        }

        var html = '';
        html += renderGroup(i18n.articles    || 'Articles',    groups.post,    query);
        html += renderGroup(i18n.information || 'Information', groups.page,    query);
        html += renderViewAll(query);
        show(html);
      });
    }

    function onInput() {
      var query = (searchInput.value || '').trim();
      clearTimeout(debounceId);

      if (query.length < MIN_CHARS) {
        hide();
        lastQuery = '';
        return;
      }
      if (query === lastQuery) return;
      lastQuery = query;

      debounceId = setTimeout(function () {
        performSearch(query);
      }, DEBOUNCE);
    }

    return {
      init: function () {
        if (!searchInput || !suggestions) return;
        searchInput.addEventListener('input', onInput);
      },
      reset: function () {
        clearTimeout(debounceId);
        hide();
        lastQuery = '';
        currentReq++;
      }
    };
  })();

  if (searchInput && suggestions) {
    NoircraftLabLiveSearch.init();
  }

  /* ── BFCache restore guard (Fix bug #1: page hang on browser back) ────
   * When user navigates back to a page that was cached with the modal open,
   * the DOM is restored but JS state is partially lost. MutationObserver +
   * stale event listeners can cascade into an infinite loop. Solution: detect
   * bfcache restore via pageshow.persisted and forcibly reset modal state. */
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) return;
    if (searchModal && searchModal.classList.contains('is-open')) {
      searchModal.classList.remove('is-open', 'has-suggestions');
      searchModal.setAttribute('aria-hidden', 'true');
      if (searchToggle) searchToggle.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }
    if (searchInput) searchInput.value = '';
    if (suggestions) {
      suggestions.hidden = true;
      suggestions.innerHTML = '';
    }
  });

  /* ── Close on Escape key ─────────────────────────────── */
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (mobileNav && mobileNav.classList.contains('open')) {
      closeMenu();
      toggle.focus();
      return;
    }
    if (searchModal && searchModal.classList.contains('is-open')) {
      closeSearch();
    }
  });

  /* ── Close mobile menu on viewport resize to desktop ─── */
  var mq = window.matchMedia('(min-width: 1024px)');
  mq.addEventListener('change', function (e) {
    if (e.matches) closeMenu();
  });

})();
</script>

<!-- ─────────────────────────────────────────────────────────────────
     MAIN CONTENT WRAPPER
     ───────────────────────────────────────────────────────────────── -->
<main id="main-content" class="site-main" tabindex="-1">
