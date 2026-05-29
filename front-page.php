<?php
/**
 * front-page.php — Noircraft Lab Theme
 *
 * Homepage — editorial single-scroll layout.
 *
 * Section order (per architecture 2026-05-12):
 *   01. Hero          #hero          — BUILT (B1.1)
 *   02. Services      #services      — BUILT (B1.2; cards link to /services/#anchor)
 *   03. Approach      #approach      — BUILT (B1.3)
 *   04. Cases         #cases         — BUILT (B1.4, CPT case_study preview + empty state)
 *   05. About         #about         — BUILT (B1.5)
 *   06. Testimonials  #testimonials  — HIDDEN (B1.6)
 *   07. Contact       #contact       — BUILT (B1.7)
 *   (Section 08 — Footer — lives in footer.php, already built.)
 *
 * Runs INSIDE <main id="main-content" class="site-main">, opened in
 * header.php and closed in footer.php.
 *
 * Homepage strings are translated via noircraftlab_home_translations()
 * (functions.php, appended in B1) — source language English.
 *
 * @package NoircraftLab
 * @version 1.0.11
 */

defined( 'ABSPATH' ) || exit;

get_header();

/* ── Localized Contact page URL for the primary CTA (Polylang-aware) ──────── */
$nc_contact_id   = 0;
$nc_contact_page = get_page_by_path( 'contact' );
if ( $nc_contact_page ) {
	$nc_contact_id = (int) $nc_contact_page->ID;
}
if ( ! $nc_contact_id ) {
	$nc_contact_id = 18; // Known EN Contact page ID (fallback).
}
if ( function_exists( 'pll_get_post' ) ) {
	$nc_translated = pll_get_post( $nc_contact_id );
	if ( $nc_translated ) {
		$nc_contact_id = (int) $nc_translated;
	}
}
$nc_contact_url = $nc_contact_id ? get_permalink( $nc_contact_id ) : home_url( '/contact/' );

/* ── Localized About page URL (Polylang-aware) ───────────────────────────── */
$nc_about_id   = 0;
$nc_about_page = get_page_by_path( 'about' );
if ( $nc_about_page ) {
	$nc_about_id = (int) $nc_about_page->ID;
}
if ( ! $nc_about_id ) {
	$nc_about_id = 11; // Known EN About page ID (fallback).
}
if ( function_exists( 'pll_get_post' ) ) {
	$nc_about_tr = pll_get_post( $nc_about_id );
	if ( $nc_about_tr ) {
		$nc_about_id = (int) $nc_about_tr;
	}
}
$nc_about_url = $nc_about_id ? get_permalink( $nc_about_id ) : home_url( '/about/' );

/* ── Localized Services page URL for the Services cards (Polylang-aware) ───── */
$nc_services_id   = 0;
$nc_services_page = get_page_by_path( 'services' );
if ( $nc_services_page ) {
	$nc_services_id = (int) $nc_services_page->ID;
}
if ( function_exists( 'pll_get_post' ) && $nc_services_id ) {
	$nc_services_tr = pll_get_post( $nc_services_id );
	if ( $nc_services_tr ) {
		$nc_services_id = (int) $nc_services_tr;
	}
}
$nc_services_url = $nc_services_id ? get_permalink( $nc_services_id ) : home_url( '/services/' );

/* ── "Learn more" card label — localized inline (kept out of gettext) ─────── */
$nc_lang        = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
$nc_more_labels = array(
	'en' => 'Learn more',
	'uk' => 'Докладніше',
	'ru' => 'Подробнее',
);
$nc_more_label  = isset( $nc_more_labels[ $nc_lang ] ) ? $nc_more_labels[ $nc_lang ] : $nc_more_labels['en'];

/* ── "View live site" card label — localized inline (kept out of gettext) ─── */
$nc_live_labels = array(
	'en' => 'View live site',
	'uk' => 'Подивитися сайт',
	'ru' => 'Открыть сайт',
);
$nc_live_label  = isset( $nc_live_labels[ $nc_lang ] ) ? $nc_live_labels[ $nc_lang ] : $nc_live_labels['en'];

/* Per-case live-site URLs, keyed by post slug (unique per language).
   To add a future case's link, append its three slugs here. */
$nc_live_urls = array(
	'findugo'    => 'https://findugo.com',
	'findugo-uk' => 'https://findugo.com',
	'findugo-ru' => 'https://findugo.com',
);

/* ── WhatsApp link with localized pre-filled message ──────────────────────── */
$nc_wa_msgs = array(
	'en' => 'Hi, I found you on noircraftlab.com and have a question about your services.',
	'uk' => 'Привіт, я знайшов вас на noircraftlab.com і маю питання щодо ваших послуг.',
	'ru' => 'Привет, я нашёл вас на noircraftlab.com и хочу спросить об услугах.',
);
$nc_wa_text = isset( $nc_wa_msgs[ $nc_lang ] ) ? $nc_wa_msgs[ $nc_lang ] : $nc_wa_msgs['en'];
$nc_wa_url  = 'https://wa.me/420608082043?text=' . rawurlencode( $nc_wa_text );
?>

<!-- ═══════════════════════════════════════════════════════════════════
     01 · HERO  (#hero)
     ══════════════════════════════════════════════════════════════════ -->
<section id="hero" class="home-section home-hero">
  <div class="container">
    <div class="home-hero__inner">

      <p class="home-hero__eyebrow">
        <?php esc_html_e( 'WORDPRESS · MULTILINGUAL · SEO', 'noircraftlab' ); ?>
      </p>

      <h1 class="home-hero__title">
        <?php esc_html_e( "Editorial WordPress sites that rank, convert, and don't break.", 'noircraftlab' ); ?>
      </h1>

      <p class="home-hero__sub">
        <?php esc_html_e( 'Custom themes, multilingual builds, and technical SEO — for brands that take content seriously. Designed, built, and shipped by one developer.', 'noircraftlab' ); ?>
      </p>

      <div class="home-hero__cta">
        <a class="btn btn--primary" href="<?php echo esc_url( $nc_contact_url ); ?>">
          <?php esc_html_e( 'Start a project', 'noircraftlab' ); ?>
          <span aria-hidden="true">&rarr;</span>
        </a>
        <a class="btn btn--ghost" href="#cases">
          <?php esc_html_e( 'See the work', 'noircraftlab' ); ?>
          <span aria-hidden="true">&darr;</span>
        </a>
      </div>

    </div><!-- /.home-hero__inner -->
  </div><!-- /.container -->
</section>

<style>
/* Services cards → clickable (link to /services/#anchor) + visible label.
   Scoped here so style.css is untouched (no theme Version bump needed). */
.home-services__grid .home-service{ position:relative; cursor:pointer; }
.home-service__more{
  display:inline-flex; align-items:center; gap:.4rem;
  margin-top:1.1rem;
  font-size:.8rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase;
  color:var(--color-gold,var(--gold,var(--accent,#c9a227)));
  text-decoration:none;
}
.home-service__arrow{ transition:transform .2s ease; }
.home-service:hover .home-service__arrow,
.home-service:focus-within .home-service__arrow{ transform:translateX(4px); }
/* stretched link — makes the whole card clickable as one link */
.home-service__more::after{ content:""; position:absolute; inset:0; z-index:1; }
.home-service__more:focus-visible{
  outline:2px solid var(--color-gold,var(--gold,var(--accent,#c9a227)));
  outline-offset:3px;
}
/* About photo — real portrait fills the square frame.
   Scoped here so style.css is untouched (no theme Version bump needed). */
.home-about__photo{ aspect-ratio:1 / 1; overflow:hidden; }
.home-about__photo img{
  display:block; width:100%; height:100%; object-fit:cover;
}
/* "View live site" link on case cards (external, opens new tab).
   Scoped here so style.css is untouched (no theme Version bump needed). */
.card-body a.card-meta{
  color:var(--color-gold,var(--gold,var(--accent,#c9a227)));
  text-decoration:none;
}
.card-body a.card-meta:hover{ text-decoration:underline; }
.card-body a.card-meta:focus-visible{
  outline:2px solid var(--color-gold,var(--gold,var(--accent,#c9a227)));
  outline-offset:3px;
}
.card-body .card-live{
  display:flex; width:max-content; max-width:100%;
  align-items:center; gap:.35rem;
  margin-top:.85rem;
  font-size:.8rem; font-weight:600; letter-spacing:.05em;
  color:var(--color-gold,var(--gold,var(--accent,#c9a227)));
  text-decoration:none;
}
.card-body .card-live:hover{ text-decoration:underline; }
.card-body .card-live:focus-visible{
  outline:2px solid var(--color-gold,var(--gold,var(--accent,#c9a227)));
  outline-offset:3px;
}
</style>

<!-- ═══════════════════════════════════════════════════════════════════
     02 · SERVICES  (#services)
     ══════════════════════════════════════════════════════════════════ -->
<section id="services" class="home-section home-services">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e( 'Services', 'noircraftlab' ); ?></h2>
      <span class="section-line" aria-hidden="true"></span>
    </div>

    <div class="home-services__grid">

      <article class="home-service">
        <div class="home-service__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <h3 class="home-service__title"><?php esc_html_e( 'WordPress development', 'noircraftlab' ); ?></h3>
        <p class="home-service__text"><?php esc_html_e( 'Custom themes built from scratch — no page builders. Performance-first, accessible, easy to maintain. From editorial blogs to multilingual business sites.', 'noircraftlab' ); ?></p>
        <a class="home-service__more" href="<?php echo esc_url( $nc_services_url . '#wordpress' ); ?>" aria-label="<?php echo esc_attr( $nc_more_label . ' — ' . __( 'WordPress development', 'noircraftlab' ) ); ?>">
          <?php echo esc_html( $nc_more_label ); ?>
          <span class="home-service__arrow" aria-hidden="true">&rarr;</span>
        </a>
      </article>

      <article class="home-service">
        <div class="home-service__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <h3 class="home-service__title"><?php esc_html_e( 'Content & SEO', 'noircraftlab' ); ?></h3>
        <p class="home-service__text"><?php esc_html_e( 'Long-form articles in Russian and Ukrainian — AI-accelerated, human-edited. Rank Math setup, clean structure, and internal linking that search engines reward.', 'noircraftlab' ); ?></p>
        <a class="home-service__more" href="<?php echo esc_url( $nc_services_url . '#content-seo' ); ?>" aria-label="<?php echo esc_attr( $nc_more_label . ' — ' . __( 'Content & SEO', 'noircraftlab' ) ); ?>">
          <?php echo esc_html( $nc_more_label ); ?>
          <span class="home-service__arrow" aria-hidden="true">&rarr;</span>
        </a>
      </article>

      <article class="home-service">
        <div class="home-service__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/></svg>
        </div>
        <h3 class="home-service__title"><?php esc_html_e( 'Multilingual setup', 'noircraftlab' ); ?></h3>
        <p class="home-service__text"><?php esc_html_e( 'Polylang and WPML integration with correct hreflang, language routing, and content localization for EN, UK, and RU audiences.', 'noircraftlab' ); ?></p>
        <a class="home-service__more" href="<?php echo esc_url( $nc_services_url . '#multilingual' ); ?>" aria-label="<?php echo esc_attr( $nc_more_label . ' — ' . __( 'Multilingual setup', 'noircraftlab' ) ); ?>">
          <?php echo esc_html( $nc_more_label ); ?>
          <span class="home-service__arrow" aria-hidden="true">&rarr;</span>
        </a>
      </article>

    </div><!-- /.home-services__grid -->
  </div><!-- /.container -->
</section>

<!-- ═══════════════════════════════════════════════════════════════════
     03 · APPROACH  (#approach)
     ══════════════════════════════════════════════════════════════════ -->
<section id="approach" class="home-section home-approach">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e( 'Approach', 'noircraftlab' ); ?></h2>
      <span class="section-line" aria-hidden="true"></span>
    </div>

    <ol class="home-approach__steps">

      <li class="home-approach__step">
        <span class="home-approach__num">01</span>
        <div class="home-approach__body">
          <h3 class="home-approach__title"><?php esc_html_e( 'Discovery', 'noircraftlab' ); ?></h3>
          <p class="home-approach__text"><?php esc_html_e( 'Audit the existing site, clarify goals, and define a clear scope before any code is written.', 'noircraftlab' ); ?></p>
        </div>
      </li>

      <li class="home-approach__step">
        <span class="home-approach__num">02</span>
        <div class="home-approach__body">
          <h3 class="home-approach__title"><?php esc_html_e( 'Design', 'noircraftlab' ); ?></h3>
          <p class="home-approach__text"><?php esc_html_e( 'Wireframes, design tokens, and content structure — approved before development starts.', 'noircraftlab' ); ?></p>
        </div>
      </li>

      <li class="home-approach__step">
        <span class="home-approach__num">03</span>
        <div class="home-approach__body">
          <h3 class="home-approach__title"><?php esc_html_e( 'Build', 'noircraftlab' ); ?></h3>
          <p class="home-approach__text"><?php esc_html_e( 'A custom WordPress theme with multilingual support and technical SEO built in from day one.', 'noircraftlab' ); ?></p>
        </div>
      </li>

      <li class="home-approach__step">
        <span class="home-approach__num">04</span>
        <div class="home-approach__body">
          <h3 class="home-approach__title"><?php esc_html_e( 'Launch & Support', 'noircraftlab' ); ?></h3>
          <p class="home-approach__text"><?php esc_html_e( 'QA, a performance audit, and deployment — with 30 days of post-launch fixes included.', 'noircraftlab' ); ?></p>
        </div>
      </li>

    </ol>
  </div><!-- /.container -->
</section>

<!-- ═══════════════════════════════════════════════════════════════════
     04 · CASES  (#cases) — CPT case_study preview (empty state until B2)
     ══════════════════════════════════════════════════════════════════ -->
<section id="cases" class="home-section home-cases">
  <div class="container">

    <?php
    $nc_cases = new WP_Query( array(
      'post_type'           => 'case_study',
      'post_status'         => 'publish',
      'posts_per_page'      => 3,
      'orderby'             => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
    ) );
    ?>

    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e( 'Selected work', 'noircraftlab' ); ?></h2>
      <span class="section-line" aria-hidden="true"></span>
    </div>

    <?php if ( $nc_cases->have_posts() ) : ?>

      <div class="article-grid">
        <?php while ( $nc_cases->have_posts() ) : $nc_cases->the_post(); ?>
          <article class="card">
            <a class="card-image-wrap" href="<?php the_permalink(); ?>">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'noircraftlab-card', array( 'loading' => 'lazy' ) ); ?>
              <?php else : ?>
                <span class="card-image-placeholder" aria-hidden="true"></span>
              <?php endif; ?>
            </a>
            <div class="card-body">
              <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
              <a class="card-meta" href="<?php the_permalink(); ?>">
                <span class="card-meta-dot" aria-hidden="true">&bull;</span>
                <?php esc_html_e( 'Read case study', 'noircraftlab' ); ?> &rarr;
              </a>
              <?php
              $nc_case_slug = get_post_field( 'post_name', get_the_ID() );
              $nc_case_live = isset( $nc_live_urls[ $nc_case_slug ] ) ? $nc_live_urls[ $nc_case_slug ] : '';
              if ( $nc_case_live ) :
              ?>
                <a class="card-live" href="<?php echo esc_url( $nc_case_live ); ?>" target="_blank" rel="noopener noreferrer">
                  <?php echo esc_html( $nc_live_label ); ?> <span aria-hidden="true">&#8599;&#xFE0E;</span>
                </a>
              <?php endif; ?>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <p class="home-cases__more">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'case_study' ) ); ?>">
          <?php esc_html_e( 'View all cases', 'noircraftlab' ); ?> &rarr;
        </a>
      </p>

      <?php wp_reset_postdata(); ?>

    <?php else : ?>

      <div class="home-cases__empty">
        <p class="home-cases__empty-title"><?php esc_html_e( 'Case studies are on the way.', 'noircraftlab' ); ?></p>
        <p class="home-cases__empty-text"><?php esc_html_e( 'Detailed write-ups of recent projects are being prepared.', 'noircraftlab' ); ?></p>
        <a class="btn btn--ghost" href="<?php echo esc_url( $nc_contact_url ); ?>">
          <?php esc_html_e( 'Start a project', 'noircraftlab' ); ?>
          <span aria-hidden="true">&rarr;</span>
        </a>
      </div>

    <?php endif; ?>

  </div><!-- /.container -->
</section>

<!-- ═══════════════════════════════════════════════════════════════════
     05 · ABOUT  (#about)
     ══════════════════════════════════════════════════════════════════ -->
<section id="about" class="home-section home-about">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e( 'About', 'noircraftlab' ); ?></h2>
      <span class="section-line" aria-hidden="true"></span>
    </div>

    <div class="home-about__layout">

      <div class="home-about__photo">
        <?php
        echo wp_get_attachment_image(
            111,                       // ID вложения в Медиатеке (about-portrait-650.webp)
            'full',                    // 650×650, 47 КБ — отдаём как есть
            false,
            array(
                'class'   => 'home-about__img',
                'loading' => 'lazy',   // секция ниже первого экрана
            )
        );
        ?>
      </div>

      <div class="home-about__body">
        <h3 class="home-about__heading"><?php esc_html_e( "Hi, I'm Roman.", 'noircraftlab' ); ?></h3>
        <p class="home-about__text"><?php esc_html_e( "I'm the developer behind Noircraft Lab — a one-person practice building editorial WordPress sites for content-first brands. Design, development, content, and SEO under a single point of accountability.", 'noircraftlab' ); ?></p>
        <p class="home-about__text"><?php esc_html_e( 'I work AI-augmented and finish everything by hand — clean code, no page builders, and multilingual done as a standard, not an afterthought.', 'noircraftlab' ); ?></p>

        <ul class="home-about__facts">
          <li><?php esc_html_e( 'Solo developer — full accountability', 'noircraftlab' ); ?></li>
          <li>EN &middot; UK &middot; RU</li>
          <li><?php esc_html_e( 'Custom WordPress — no page builders', 'noircraftlab' ); ?></li>
          <li><?php esc_html_e( 'AI-augmented, human-finished', 'noircraftlab' ); ?></li>
        </ul>

        <a class="home-about__link" href="<?php echo esc_url( $nc_about_url ); ?>">
          <?php esc_html_e( 'More about me', 'noircraftlab' ); ?> <span aria-hidden="true">&rarr;</span>
        </a>
      </div>

    </div><!-- /.home-about__layout -->
  </div><!-- /.container -->
</section>

<!-- ═══════════════════════════════════════════════════════════════════
     06 · TESTIMONIALS  (#testimonials) — HIDDEN (B1.6).
     Intentionally not rendered: no client reviews yet, and Cases above
     already shows a "coming soon" state. Reinstate post-launch as 3 quote
     cards (name, role, company — with consent). Markup removed on purpose.
     ══════════════════════════════════════════════════════════════════ -->

<!-- ═══════════════════════════════════════════════════════════════════
     07 · CONTACT  (#contact)
     ══════════════════════════════════════════════════════════════════ -->
<style>
/* WhatsApp link in Contact section — scoped here so style.css is untouched. */
.home-contact__whatsapp{
  display:inline-block;
  color:var(--color-gold,var(--gold,var(--accent,#c9a227)));
  text-decoration:none; font-size:.95rem;
}
.home-contact__whatsapp:hover{ text-decoration:underline; }
</style>
<section id="contact" class="home-section home-contact">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title"><?php esc_html_e( 'Contact', 'noircraftlab' ); ?></h2>
      <span class="section-line" aria-hidden="true"></span>
    </div>

    <div class="home-contact__inner">
      <h3 class="home-contact__heading"><?php esc_html_e( "Let's build something.", 'noircraftlab' ); ?></h3>
      <p class="home-contact__sub"><?php esc_html_e( "Tell me about your project and what you're aiming for — I usually reply within a day.", 'noircraftlab' ); ?></p>

      <div class="home-contact__actions">
        <a class="btn btn--primary" href="<?php echo esc_url( $nc_contact_url ); ?>">
          <?php esc_html_e( 'Get in touch', 'noircraftlab' ); ?>
          <span aria-hidden="true">&rarr;</span>
        </a>
        <a class="home-contact__email" href="mailto:hello@noircraftlab.com">hello@noircraftlab.com</a>
        <a class="home-contact__whatsapp" href="<?php echo esc_url( $nc_wa_url ); ?>" target="_blank" rel="noopener noreferrer">WhatsApp <span aria-hidden="true">&rarr;</span></a>
      </div>
    </div>

  </div><!-- /.container -->
</section>

<?php
get_footer();
