# STECH content migration map — old site → new block pages

**Goal:** move ALL existing content into the new designed, block-based pages. Content is
preserved verbatim; it's re-fitted into the approved blocks. Source of the old→new URL
mapping: `STECH-Migration-Audit-v3.csv` (client-approved). Machine-readable per-item list:
`scratchpad/migration-matched.json` (188 items).

The old content lives in the site DB as **Divi Builder** shortcodes. It is extracted with
`scratchpad/divi_extract.py` (Divi `et_pb_*` → structured items) and re-composed as ACF blocks.

## 188 matched items, by build batch

| Batch | Count | Destination | Approach |
|---|---|---|---|
| **News posts** | 76 | `/about/news-events/*` (core Posts) | Article HTML is already clean; keep post_content (strip Divi wrappers), set parent/category, add page-header + prose rendering via the News single template. Low-touch. |
| **Content pages** | 57 | `/about/*`, `/admissions-aid/*`, `/student-resources/*`, `/community/*` | **Main work.** Divi → blocks (see mapping below). Each rebuilt as a block-composed page. |
| **Programs** | 27 | `/programs/<cluster>/<slug>/` (Program CPT) | Program-detail block layout (page-header + info-tabs + outcomes + …). Content from old program pages. |
| **Jobs** | 21 | `/student-resources/.../career-services-job-board/*` | Import as `job_listing` (WP Job Manager) — title, company, location, description from the old job pages. |
| **High school** | 6 | `/high-school/*` | County landing pages — page-header + prose + steps/checklist + contact-block. |
| **Events** | 1 | Events Calendar | `tribe_events`. |

## Divi module → new block mapping (the "how content fits the new sections")

The extractor classifies every old module; each maps to an approved block:

| Divi module (old) | Extracted as | New STECH block |
|---|---|---|
| `et_pb_fullwidth_slider` / `et_pb_slide` (top hero) | `slide` {heading, text, image, button} | **page-header** (interior) or **hero** (home) |
| `et_pb_text` with an `<h1/h2>` + body | `richtext` {headings, html} | **lead-in** (short intro) or **prose** (long narrative) |
| `et_pb_blurb` (icon + title + text) | `blurb` {title, image, text, url} | **why** / **feature-grid** card (icon set), or **resources** row (if it links to a file) |
| `et_pb_blurb` rows used as steps | `blurb` sequence | **steps** or **checklist** |
| `et_pb_testimonial` | `testimonial` {author, role, quote} | **testimonial** (single) or **statement** (leadership pull-quote) |
| `et_pb_image` (standalone) | `image` {src, alt} | feeds a block's image field (gallery/feature-row/etc.) |
| `et_pb_button` | `cta` {text, url} | a block's CTA link field, or **cta-banner** |
| `et_pb_accordion` / toggles | (todo) | **accordion** |
| `et_pb_pricing_tables` | (todo) | **price-table** / **compare-table** |
| Contact info / map | text + address | **contact-block** / **locations** |
| Partner logos row | images | **logo-marquee** |

Every page ends with the fixed chrome the theme adds automatically (utility-bar, site-header,
site-footer) plus a **cta-banner** before the footer. Media (images) is wired in a later media
pass — the extractor records the old `src` so it can be re-attached from the uploads library.

## Per-page template pattern

- **Section landing** (e.g. `/about/`, `/admissions-aid/`): page-header → lead-in → feature-row/feature-grid → (steps/checklist/accordion as content dictates) → contact-block → cta-banner.
- **Content page** (e.g. `/about/leadership/`): page-header → prose → people (if staff) → resources (if downloads) → cta-banner.
- **Program detail**: page-header(with-form) → info-tabs → outcomes → accreditation → testimonial-carousel → related → cta-banner.
- **News single**: page-header → prose (the article) → related news → cta-banner.
- **County / high-school**: page-header → prose → steps → contact-block → cta-banner.

## Build phases (each verified live before the next)

1. **Structure**: create the full new page tree (parents/slugs from the CSV) as empty published pages so URLs + redirect targets resolve. (~57 pages + section parents.)
2. **Content pages** (57): extract each old page → map items → compose blocks → create/update → curl-verify. Fan-out in batches by section.
3. **Programs** (27): Program CPT posts with the program-detail layout.
4. **News** (76): posts into `/about/news-events/` with the news template.
5. **Jobs** (21): `job_listing` imports.
6. **Media pass**: re-attach images from the uploads library to block image fields.
7. **Redirects**: the 270-rule map (already generated) points every old URL at its new page.

> The 7 approved designed pages (Home, Programs, Program-detail, About, Admissions, Cost & Aid,
> Contact) are already built under `/redesign/` and become the canonical templates the rest follow.
