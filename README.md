# noircraftlab-theme

> Custom WordPress theme powering [Noircraft Lab](https://noircraftlab.com/) — a multilingual editorial portfolio site built without page builders.

![Theme preview](https://noircraftlab.com/wp-content/uploads/2026/05/noircraftlab-preview.jpeg)

---

## Live site

**[noircraftlab.com](https://noircraftlab.com/)** — EN · UK · RU

---

## Stack

| Layer | Technology |
|---|---|
| CMS | WordPress 7.0 |
| PHP | 8.3 |
| Multilingual | Polylang Free (EN / UK / RU) |
| SEO | Rank Math |
| Forms | WPForms Lite |
| Hosting | Hostinger Business |
| Builders | None — hand-coded |

---

## What this theme does

- **Custom theme from scratch** — no page builders, no Elementor, no Genesis. Pure PHP templates + vanilla CSS + vanilla JS.
- **Multilingual by design** — three-language support (EN primary, Ukrainian, Russian) via Polylang Free. Custom `gettext` filter with inline PHP translation arrays instead of `.mo` files (more reliable in shared hosting environments).
- **Noir Editorial design system** — dark cinematic palette (`#0D0D0D` base, `#D4AF37` gold accent), Georgia serif typography, mobile-first responsive layout.
- **Custom Post Type** — `case_study` CPT with `case_tag` taxonomy for portfolio case studies. Archive at `/cases/`, multilingual slugs per language.
- **Live search** — custom REST endpoint (`/wp-json/noircraftlab/v1/search`) that respects Polylang language filtering (Polylang Free does not pass `&lang=` to standard WP REST endpoints — this fixes that). Includes in-page Find FAB for single posts.
- **Schema markup** — `WebSite` + `SearchAction` on homepage; `NewsArticle` fallback on single posts (defers to Rank Math when active).
- **Performance** — no jQuery dependency in custom JS, no unused CSS blocks loaded per page, image sizes registered for each context (hero / card / single / thumb).
- **SEO-ready** — clean `<title>` separator, hreflang fallback, Rank Math integration, virtual `robots.txt` via Rank Math.

---

## Theme structure

```
noircraftlab-theme/
├── style.css               # Theme header + all CSS (~4 400 lines)
├── functions.php           # Bootstrap: setup, i18n, schema, CPT, search (~1 700 lines)
├── front-page.php          # Homepage (B1 sections: Hero → Contact)
├── header.php              # Global header + search modal
├── footer.php              # Global footer + nav menus
├── index.php               # Blog index / fallback
├── single.php              # Single post template
├── page.php                # Static page template
├── archive.php             # Archive fallback
├── archive-case_study.php  # Case studies archive
├── single-case_study.php   # Case study single post
├── search.php              # Search results page
├── 404.php                 # Custom 404
├── screenshot.png          # WP theme preview (1200×900)
├── js/
│   └── main.js             # Search modal + mobile nav + FAB JS
└── languages/              # .po source files (en, uk, ru)
```

---

## Key implementation notes

**Why inline PHP translation arrays instead of `.mo` files:**
WordPress's PHP MO parser failed to parse self-compiled `.mo` files on the shared hosting environment (`mo-import-failed` status). Inline arrays in `functions.php` guarantee translations work regardless of MO parser or textdomain state — a pragmatic workaround documented in the code.

**Why a custom REST search endpoint:**
Polylang Free does not honour the `&lang=` parameter on standard `/wp/v2/*` REST endpoints (that is a Polylang Pro feature). The custom endpoint runs `WP_Query` with `lang` arg, which Polylang Free *does* intercept via its query filters — same behaviour as the main `/?s=` search.

**Hreflang fallback:**
The theme outputs hreflang tags only when neither Polylang nor Yoast/Rank Math is active — avoiding duplicate tags in production while providing a safe fallback for any future plugin-free setup.

---

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Polylang Free (for multilingual routing)
- Rank Math SEO (recommended; theme degrades gracefully without it)

---

## License

GPL-2.0-or-later — see [GNU GPL v2](https://www.gnu.org/licenses/gpl-2.0.html).

---

## Built by

**Roman Miroshnychenko** — WordPress developer specialising in custom themes, multilingual builds, and technical SEO.

[noircraftlab.com](https://noircraftlab.com/) · [LinkedIn](https://www.linkedin.com/company/noircraftlab/)
