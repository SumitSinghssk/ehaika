# Ethaika: home page build prompt (Laravel, premium light SaaS)

## Rules

- **Content:** use **only the content and images of ethaika.com**, with wording kept as on the site. Copy comes from the home page, the features page (FAQ) and the Contact Us page. Nothing is added or rewritten, and there are no invented headlines, claims or statistics.
- **Colour:** light. White page, `#F6F7FB` alternate sections, navy ink `#0B1220`, secondary `#5B6474`, hairline borders `#E6E9F0`, and Ethaika's product blue `#2563EB` (from its own screens) as the one accent, with tint `#EEF3FF` and deep `#1E40AF`.
- **Images:** ethaika.com's own product screens and partner logos, in their original colours.
- **Spacing:** compact. Sections are `clamp(64px, 8vw, 112px)` top and bottom, and the container is 1400px wide with `clamp(20px, 2.2vw, 32px)` margins. The hero is not a full empty screen.
- **Surfaces:** white cards with 1px hairline borders and 20px radius, no shadows. Product screens sit in a light frame. One soft blue wash behind the hero.
- **Type:** General Sans (400–700).
- **Buttons:** 12px radius, blue fill turning deep blue on hover. The secondary button is white with a hairline border.
- **Stack:** Laravel 12 starter kit (`D:\xampp\htdocs\laravel-starter-kit-main`), with Blade, Tailwind v4 through Vite, Alpine, GSAP + ScrollTrigger and Lenis.

## Dynamic parts

- **Copy:** `config/ethaika.php` holds every word of the page.
- **Header:** a plain white bar.
  - **Contents:** logo, then links from **Admin → Menus → Header**: Home, Platform (`#platform`), Blog (ethaika.com, new tab), Features (`#features`), About (ethaika.com, new tab), Contact Us (`#contact`). On the right, a "Schedule Demo" button.
  - **Scroll behaviour:** it hides on scroll down and returns on a small scroll up.
  - **Phones:** a full white menu sheet.
- **Footer:** link columns from **Admin → Menus → Footer** (Company: Home, About, Features, Contact Us; Utility Pages). Terms & Condition and Privacy Policy sit in the bottom row. Socials (LinkedIn) and any emails, phones or addresses come from **Admin → Settings**. "© {year} All Rights Reserved Ethaika.com".
- **Contact form:** the Contact Us form posts to `contact.store`. Each request is saved as an **Enquiry** in Admin, with bot protection. The visitor returns to `#contact` with inline errors or a thank-you.
- **Seeder:** `php artisan db:seed --class=EthaikaSiteSeeder` writes the menus and settings, saving a timestamped backup first.

## Sections

1. **Hero (centred):** "Trust, Test, and Evaluate your **AI Systems**" (the last two words in blue), the site's sentence, and "Book Your Demo" and "Start Your First Experiment".
   - **Product screen:** the Simulation Engine in a frame, lifting upright as you scroll.
   - **Trust row:** under it, the Integrations logos (Jira, GitHub, Azure DevOps, Anthropic, AWS Bedrock).
2. **Why Ethaika (centred):** the question headline, then one card split in three: 4+ Evaluation Providers, 6 Judge Types, 100s of Virtual Users. The numbers count up. Then "Start Your First Experiment".
3. **Real-time Evaluation Data (, alternate tone):**
   - **Text:** headline on the left, paragraph on the right.
   - **Figures:** three figure cards.
   - **Screen viewer:** tabs for Ethaika Platform, Trends and Run Review. It auto-advances every 5 s while in view, with a progress bar under the active tab. Clicking a tab or using the arrow keys switches it.
4. **Integrations:** the text and button on the left; on the right, a hairline grid of logo tiles and a blue tile for standard telemetry and integration protocols.
5. **Features (alternate tone):**
   - **Bento grid:** the six features. The first spans two columns with the team photo from the About page; Real-Time Run Notifications runs full width.
   - **Comparison table:** the site's table, with the Ethaika column in blue.
6. **FAQ:** a sticky heading on the left, and the six questions in one card on the right, opening smoothly.
7. **Contact ():** a blue-tint panel with "We're Here For You", the text and the engineers photo, and a white card with the form (boxed fields, blue focus ring).
8. **Footer:** described under Dynamic parts above.

## Motion

- **Smooth scroll:** Lenis, synced to ScrollTrigger. Menu links glide to their section under the header, and Home glides to the top.
- **Headings:** every heading's lines rise out of a mask as it enters.
- **Parts:** text, buttons, panels and tiles rise in with a short stagger.
- **Hero image:** revealed from the bottom, then it widens slightly as you scroll.
- **Figures:** the numbers count up.
- **Product screens:** they open from a framed window to full size as they scroll in, and settle from 1.12 scale.
- **Features:** the table rows slide in, and each feature's black bar draws.
- **FAQ:** opens and closes with a smooth height.
- **Footer:** the columns rise, then the name rises at full width.
- **Reduced motion:** everything is shown at once, with no travel.

## Done means

- **Loads cleanly:** `http://localhost/laravel-starter-kit-main/public/` has no console errors and no sideways scroll at 1440, 1024 and 390px.
- **Everything works:** menu links, the phone menu, the header hide/show, the FAQ, and the form (errors, then saved and thanked).

## Inner pages

All inner pages use the home page's design: tokens, General Sans, blue accent, hairline cards, framed screens and the same motion. They share these partials in `resources/views/website/partials/`:

- `page-hero` for the header
- `faq`
- `cta` (a blue-tint panel)
- `contact-form`

Their copy lives in `config/ethaika.php`, taken from the matching ethaika.com page.

- **Platform (`/platform`, from enterprise-ai-decision-intelligence):** the page's sections, in order:
  1. The four questions.
  2. The Hidden Enterprise Problem.
  3. The Cost Problem Is Growing.
  4. Why Existing Solutions Fall Short.
  5. Enter Ethaika, as a tab viewer: Simulation Engine, Eval Orchestration, Decision Dashboard, Run Review.
  6. How Ethaika Works.
  7. Why This Matters Right Now.
  8. Why Ethaika Wins, with figures and the comparison table.
  9. Vision.
  10. The call to action, with Coverage, Deployment and Outputs.
- **Features (`/features`):**
  1. A Unified Platform for AI Simulation, Evaluation, and Quality Insights, with the screen.
  2. The flexibility note, the integration logos and the real-time figures.
  3. Multi-Framework Evaluation.
  4. The six features.
  5. FAQ and call to action.
- **About (`/about`):**
  1. Building Trust Into Every AI Decision.
  2. The two team photographs.
  3. "On a personal and professional note" (six values).
  4. The features summary.
  5. FAQ and call to action.
- **Contact Us (`/contact-us`):**
  - **Header:** "We're ready to listen and respond".
  - **Form panel:** the form, plus any contact details from Admin → Settings.
  - **FAQ.**
  - **Behaviour:** the form posts and returns here with errors or a thank-you.
- **Blog (`/blog`, `/blog/category/{slug}`):**
  - **Top:** "Latest Updates & Insights Blog." and category filters.
  - **Articles:** the latest article large, the rest as cards.
  - **Below:** pagination and FAQ.
  - **Source:** Admin → Blog.
- **Article (`/blog/{slug}`):**
  - **Header:** breadcrumb, title, excerpt, date and reading time, and the framed featured image.
  - **Body:** styled by `.prose-eth`.
  - **Sticky side panel:** "Book your demo today.", categories and share links.
  - **Below:** "Keep reading" with two related posts, then the call to action.
- **Pages (`/{slug}`, Admin → Pages):** title, last-updated date, the body in `.prose-eth` (editor content blocks keep `.ps-content`), and the call to action.
- **Feed (`/blog/feed`):** RSS 2.0.

## Seeders

- **`EthaikaSiteSeeder`:** the header menu (Home, Platform, Blog, Features, About, Contact Us) and the footer menu (Company, Product, and Utility Pages, which moves to the bottom row), plus the name and LinkedIn in Settings.
- **`EthaikaBlogSeeder`:**
  - **Articles:** ethaika.com's three articles, with their images, in the categories Enterprise AI and AI Evaluation. Bodies are in `database/seeders/content/ethaika-blog`.
  - **Demo content:** it sets the starter kit's demo posts and categories to Inactive. Nothing is deleted.

After editing Blade views on Windows, run `php artisan view:cache`.
