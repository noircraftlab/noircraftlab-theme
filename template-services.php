<?php
/**
 * Template Name: Services Page
 *
 * Custom Services page for Noircraft Lab.
 * Three anchored blocks: #wordpress, #content-seo, #multilingual.
 * All content (EN / UK / RU) is self-contained here; the current language
 * is detected via Polylang. No functions.php / style.css changes required.
 *
 * @package noircraftlab-theme
 */

get_header();

/* -------------------------------------------------------------------------
 * Current language (Polylang) — fallback to English.
 * ---------------------------------------------------------------------- */
$nc_lang = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
if ( ! in_array( $nc_lang, array( 'en', 'uk', 'ru' ), true ) ) {
	$nc_lang = 'en';
}

/* -------------------------------------------------------------------------
 * Language-aware Contact URL (mirrors the homepage Hero logic).
 * ---------------------------------------------------------------------- */
$nc_contact_url = '';
if ( function_exists( 'pll_get_post' ) ) {
	$nc_contact_en = get_page_by_path( 'contact' ); // EN contact page.
	if ( $nc_contact_en ) {
		$nc_contact_tr = pll_get_post( $nc_contact_en->ID, $nc_lang );
		if ( $nc_contact_tr ) {
			$nc_contact_url = get_permalink( $nc_contact_tr );
		}
	}
}
if ( empty( $nc_contact_url ) ) {
	$nc_contact_url = get_permalink( 18 ); // Fallback: EN contact page ID 18.
}

/* -------------------------------------------------------------------------
 * Inline Lucide-style icons (code / pen / globe) — gold via currentColor.
 * ---------------------------------------------------------------------- */
if ( ! function_exists( 'nc_services_icon' ) ) {
	function nc_services_icon( $name ) {
		$a = 'xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';
		switch ( $name ) {
			case 'code':
				return '<svg ' . $a . '><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
			case 'pen':
				return '<svg ' . $a . '><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>';
			case 'globe':
				return '<svg ' . $a . '><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/></svg>';
		}
		return '';
	}
}

/* -------------------------------------------------------------------------
 * Content (EN / UK / RU).
 * ---------------------------------------------------------------------- */
$nc_all = array(

	'en' => array(
		'h1'             => "Services",
		'intro'          => "When I build or rebuild a website, three things come together: a custom WordPress build, the content and SEO behind it, and a multilingual setup done properly. Together they make a site that's reliable and built to last.",
		'included_label' => "What's included",
		'cta_lead'       => "Have a project in mind? Most projects combine all three. Tell me what you're building and I'll tell you how I'd approach it.",
		'cta_btn'        => "Start a project →",
		'blocks'         => array(
			array(
				'id'    => 'wordpress',
				'icon'  => 'code',
				'title' => "WordPress development",
				'p'     => array(
					"I build custom WordPress themes from scratch — no page builders, no bloated templates. Every site is hand-coded, which keeps it fast, accessible, and easy to maintain for years.",
					"The result is a site you actually own: clean code, predictable behaviour, and no monthly fee for a builder plugin that locks you in. From editorial blogs to multilingual business sites.",
				),
				'list'  => array(
					"Custom theme, hand-coded — no page builders",
					"Performance- and accessibility-first markup",
					"Mobile-first, tested across devices",
					"30 days of post-launch fixes included",
				),
			),
			array(
				'id'    => 'content-seo',
				'icon'  => 'pen',
				'title' => "Content & SEO",
				'p'     => array(
					"I write long-form content in Russian and Ukrainian — AI-accelerated for speed, but always human-edited, so it reads like a person wrote it, not a model.",
					"On the technical side: Rank Math configured properly, a clean URL and heading structure, and internal linking that search engines actually reward. SEO is built in from day one, not bolted on later.",
				),
				'list'  => array(
					"Long-form articles (RU / UK), human-edited",
					"Rank Math setup and on-page SEO",
					"Clean structure, headings, and internal linking",
					"Meta titles and descriptions per page",
				),
			),
			array(
				'id'    => 'multilingual',
				'icon'  => 'globe',
				'title' => "Multilingual setup",
				'p'     => array(
					"Multilingual sites are easy to get wrong. I set them up so each language is a real, indexable version — correct hreflang, clean per-language URLs, and proper language routing.",
					"I work with Polylang and WPML and localize the content itself, not just the interface — so an English, Ukrainian, or Russian visitor each gets a page that feels native to them.",
				),
				'list'  => array(
					"Polylang or WPML integration",
					"Correct hreflang and per-language URLs",
					"Content localization (EN / UK / RU)",
					"A language switcher and routing that just work",
				),
			),
		),
	),

	'ru' => array(
		'h1'             => "Услуги",
		'intro'          => "Когда я создаю или модернизирую сайт, вместе сходятся три вещи: индивидуальная сборка на WordPress, контент и SEO под ней, и грамотно сделанная мультиязычность. В связке они дают сайт, который работает надёжно и создан надолго.",
		'included_label' => "Что входит",
		'cta_lead'       => "Есть проект на примете? Чаще всего он объединяет все три направления. Расскажите, что вы строите, — и я отвечу, как бы к этому подошёл.",
		'cta_btn'        => "Начать проект →",
		'blocks'         => array(
			array(
				'id'    => 'wordpress',
				'icon'  => 'code',
				'title' => "Разработка на WordPress",
				'p'     => array(
					"Я делаю индивидуальные темы WordPress с нуля — без конструкторов и раздутых шаблонов. Каждый сайт написан вручную: он быстрый, доступный и удобный в поддержке на годы вперёд.",
					"В итоге сайт по-настоящему ваш: чистый код, предсказуемое поведение и никаких ежемесячных платежей за плагин-конструктор, который привязывает к себе. От редакторских блогов до мультиязычных бизнес-сайтов.",
				),
				'list'  => array(
					"Индивидуальная тема вручную — без конструкторов",
					"Вёрстка с упором на скорость и доступность",
					"Mobile-first, проверено на разных устройствах",
					"30 дней правок после запуска включены",
				),
			),
			array(
				'id'    => 'content-seo',
				'icon'  => 'pen',
				'title' => "Контент и SEO",
				'p'     => array(
					"Я пишу лонгриды на русском и украинском — с ускорением ИИ ради скорости, но всегда с ручной редактурой, чтобы текст читался как написанный человеком, а не моделью.",
					"С технической стороны: правильно настроенный Rank Math, чистая структура URL и заголовков, перелинковка, которую поисковики действительно ценят. SEO заложено с первого дня, а не прикручено потом.",
				),
				'list'  => array(
					"Лонгриды (RU / UK) с ручной редактурой",
					"Настройка Rank Math и on-page SEO",
					"Чистая структура, заголовки и перелинковка",
					"Meta-заголовки и описания для каждой страницы",
				),
			),
			array(
				'id'    => 'multilingual',
				'icon'  => 'globe',
				'title' => "Мультиязычность",
				'p'     => array(
					"Мультиязычные сайты легко сделать неправильно. Я настраиваю их так, чтобы каждый язык был полноценной индексируемой версией — корректный hreflang, чистые URL по языкам и правильная маршрутизация.",
					"Работаю с Polylang и WPML и локализую сам контент, а не только интерфейс — чтобы англоязычный, украинский или русский посетитель видел страницу, родную именно для него.",
				),
				'list'  => array(
					"Интеграция Polylang или WPML",
					"Корректный hreflang и URL по языкам",
					"Локализация контента (EN / UK / RU)",
					"Переключатель языков и маршрутизация без сбоев",
				),
			),
		),
	),

	'uk' => array(
		'h1'             => "Послуги",
		'intro'          => "Коли я створюю або модернізую сайт, разом сходяться три речі: індивідуальна збірка на WordPress, контент і SEO під нею, та грамотно зроблена багатомовність. У зв'язці вони дають сайт, який працює надійно і створений надовго.",
		'included_label' => "Що входить",
		'cta_lead'       => "Маєте проєкт на думці? Найчастіше він об'єднує всі три напрями. Розкажіть, що ви будуєте, — і я відповім, як би до цього підійшов.",
		'cta_btn'        => "Почати проєкт →",
		'blocks'         => array(
			array(
				'id'    => 'wordpress',
				'icon'  => 'code',
				'title' => "Розробка на WordPress",
				'p'     => array(
					"Я роблю індивідуальні теми WordPress з нуля — без конструкторів і роздутих шаблонів. Кожен сайт написаний вручну: він швидкий, доступний і зручний у підтримці на роки вперед.",
					"У підсумку сайт по-справжньому ваш: чистий код, передбачувана поведінка і жодних щомісячних платежів за плагін-конструктор, який прив'язує до себе. Від редакторських блогів до багатомовних бізнес-сайтів.",
				),
				'list'  => array(
					"Індивідуальна тема вручну — без конструкторів",
					"Верстка з акцентом на швидкість і доступність",
					"Mobile-first, перевірено на різних пристроях",
					"30 днів правок після запуску включені",
				),
			),
			array(
				'id'    => 'content-seo',
				'icon'  => 'pen',
				'title' => "Контент і SEO",
				'p'     => array(
					"Я пишу лонгріди російською та українською — з прискоренням ШІ заради швидкості, але завжди з ручною редактурою, щоб текст читався як написаний людиною, а не моделлю.",
					"З технічного боку: правильно налаштований Rank Math, чиста структура URL і заголовків, перелінковка, яку пошуковики справді цінують. SEO закладене з першого дня, а не прикручене потім.",
				),
				'list'  => array(
					"Лонгріди (RU / UK) з ручною редактурою",
					"Налаштування Rank Math та on-page SEO",
					"Чиста структура, заголовки та перелінковка",
					"Meta-заголовки та описи для кожної сторінки",
				),
			),
			array(
				'id'    => 'multilingual',
				'icon'  => 'globe',
				'title' => "Багатомовність",
				'p'     => array(
					"Багатомовні сайти легко зробити неправильно. Я налаштовую їх так, щоб кожна мова була повноцінною індексованою версією — коректний hreflang, чисті URL за мовами та правильна маршрутизація.",
					"Працюю з Polylang і WPML і локалізую сам контент, а не лише інтерфейс — щоб англомовний, український чи російський відвідувач бачив сторінку, рідну саме для нього.",
				),
				'list'  => array(
					"Інтеграція Polylang або WPML",
					"Коректний hreflang і URL за мовами",
					"Локалізація контенту (EN / UK / RU)",
					"Перемикач мов і маршрутизація без збоїв",
				),
			),
		),
	),
);

$c = $nc_all[ $nc_lang ];
?>

<style>
.nc-services{
	--nc-gold: var(--color-gold, var(--gold, var(--accent-gold, var(--accent, #c9a227))));
	--nc-muted: var(--text-muted, var(--color-text-muted, rgba(255,255,255,0.72)));
}
.nc-services-inner{
	max-width:64rem;
	margin:0 auto;
	padding:clamp(3rem,2rem + 4vw,5rem) 1.5rem clamp(4rem,3rem + 4vw,6rem);
}
.nc-services .nc-intro{
	max-width:46rem;
	margin:1.5rem 0 0;
	font-size:1.125rem;
	line-height:1.7;
	color:var(--nc-muted);
}
.nc-services .nc-block{
	margin-top:clamp(2.5rem,2rem + 3vw,4rem);
	padding-top:clamp(2.5rem,2rem + 3vw,4rem);
	border-top:1px solid rgba(255,255,255,0.10);
	scroll-margin-top:6.5rem;
}
.nc-services .nc-block-head{
	display:flex;
	align-items:center;
	gap:.85rem;
	margin-bottom:1.25rem;
}
.nc-services .nc-icon{
	display:inline-flex;
	flex:0 0 auto;
	color:var(--nc-gold);
}
.nc-services .nc-block-title{
	margin:0;
	font-family:Georgia,'Times New Roman',serif;
	font-size:clamp(1.5rem,1.2rem + 1.4vw,2rem);
	line-height:1.2;
}
.nc-services .nc-block-body{ max-width:46rem; }
.nc-services .nc-block-body p{
	margin:0 0 1rem;
	line-height:1.75;
	color:var(--nc-muted);
}
.nc-services .nc-included-label{
	margin:1.75rem 0 .85rem !important;
	font-size:.72rem;
	font-weight:600;
	letter-spacing:.1em;
	text-transform:uppercase;
	color:var(--nc-gold);
}
.nc-services .nc-included{
	margin:0;
	padding:0;
	list-style:none;
	display:grid;
	gap:.6rem;
}
.nc-services .nc-included li{
	position:relative;
	padding-left:1.4rem;
	line-height:1.55;
	color:var(--nc-muted);
}
.nc-services .nc-included li::before{
	content:"";
	position:absolute;
	left:0;
	top:.55em;
	width:6px;
	height:6px;
	background:var(--nc-gold);
	transform:rotate(45deg);
}
.nc-services .nc-cta{
	margin-top:clamp(3rem,2.5rem + 3vw,4.5rem);
	padding-top:clamp(2.5rem,2rem + 3vw,3.5rem);
	border-top:1px solid rgba(255,255,255,0.10);
}
.nc-services .nc-cta-lead{
	max-width:42rem;
	margin:0 0 1.5rem;
	font-size:1.0625rem;
	line-height:1.7;
	color:var(--nc-muted);
}
@media (max-width:520px){
	.nc-services .nc-cta .btn{ width:100%; }
}
</style>

<div class="nc-services">
	<div class="nc-services-inner">

		<header class="section-header">
			<h1 class="section-title"><?php echo esc_html( $c['h1'] ); ?></h1>
			<span class="section-line"></span>
		</header>

		<p class="nc-intro"><?php echo esc_html( $c['intro'] ); ?></p>

		<?php foreach ( $c['blocks'] as $b ) : ?>
			<section class="nc-block" id="<?php echo esc_attr( $b['id'] ); ?>">
				<div class="nc-block-head">
					<span class="nc-icon"><?php echo nc_services_icon( $b['icon'] ); ?></span>
					<h2 class="nc-block-title"><?php echo esc_html( $b['title'] ); ?></h2>
				</div>
				<div class="nc-block-body">
					<?php foreach ( $b['p'] as $para ) : ?>
						<p><?php echo esc_html( $para ); ?></p>
					<?php endforeach; ?>
					<p class="nc-included-label"><?php echo esc_html( $c['included_label'] ); ?></p>
					<ul class="nc-included">
						<?php foreach ( $b['list'] as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endforeach; ?>

		<section class="nc-cta">
			<p class="nc-cta-lead"><?php echo esc_html( $c['cta_lead'] ); ?></p>
			<a class="btn btn--primary" href="<?php echo esc_url( $nc_contact_url ); ?>"><?php echo esc_html( $c['cta_btn'] ); ?></a>
		</section>

	</div>
</div>

<?php
get_footer();
