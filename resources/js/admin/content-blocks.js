/*
 * Content blocks: the data model, templates, HTML renderer and HTML parser
 * behind the page and blog content builder. Saved content is plain HTML
 * (classes prefixed `ps-`), styled by resources/css/content-blocks.css on
 * both the admin preview and the website.
 */

export const STYLE_OPTIONS = {
    intro: ['align', 'size', 'width', 'bg', 'space'],
    banner: ['align', 'space'],
    features: ['align', 'bg', 'space'],
    tiles: ['align', 'space'],
    wide: ['align', 'space'],
    text: ['align', 'size', 'width', 'bg', 'space'],
    quote: ['align', 'size', 'width', 'bg', 'space'],
    note: ['align', 'width', 'bg', 'space'],
    rich: ['width', 'bg', 'space'],
    cta: ['align', 'size', 'width', 'space'],
    faq: ['align', 'width', 'bg', 'space'],
};

export const STYLE_DEFAULTS = {
    intro: { align: 'center', width: 'narrow', space: 'normal', size: 'md' },
    banner: { align: 'left', width: 'full', space: 'normal', size: 'md' },
    features: { align: 'left', width: 'full', space: 'normal', size: 'md' },
    tiles: { align: 'left', width: 'full', space: 'normal', size: 'md' },
    wide: { align: 'left', width: 'full', space: 'normal', size: 'md' },
    text: { align: 'left', width: 'narrow', space: 'normal', size: 'md' },
    quote: { align: 'center', width: 'narrow', space: 'normal', size: 'md' },
    note: { align: 'left', width: 'full', space: 'normal', size: 'md' },
    rich: { align: 'left', width: 'full', space: 'normal', size: 'md' },
    cta: { align: 'center', width: 'full', space: 'normal', size: 'md' },
    faq: { align: 'left', width: 'wide', space: 'normal', size: 'md' },
};

export const SECTION_COLOURS = ['#f4f3f1', '#ffffff', '#f7e0e4', '#f1e8dc', '#eedff0', '#e3ece6', '#e1e9f2', '#fbf1d9', '#1f2a24', '#1d1d1f'];
export const BANNER_COLOURS = ['#f4f3f1', '#f7e0e4', '#f1e8dc', '#eedff0', '#e3ece6', '#e1e9f2', '#fbf1d9', '#1f2a24', '#1d1d1f'];

export const BLOCK_INFO = {
    intro: { name: 'Intro', description: 'Centred headline with a short lead.' },
    banner: { name: 'Image + text banner', description: 'Big photo beside bold copy, on a colour.' },
    features: { name: 'Feature cards', description: 'Two to four short highlights.' },
    tiles: { name: 'Image tiles', description: 'Two to four photos with captions.' },
    wide: { name: 'Wide image', description: 'Full-width photo with text over it.' },
    text: { name: 'Text', description: 'Heading and paragraphs.' },
    quote: { name: 'Quote', description: 'A pull quote or testimonial.' },
    note: { name: 'Note', description: 'A highlighted tip or key takeaway.' },
    rich: { name: 'Rich text', description: 'Free-form editor: lists, links, tables.' },
    cta: { name: 'Call to action', description: 'Headline with one or two buttons.' },
    faq: { name: 'FAQ', description: 'Questions that open to show the answer.' },
};

export const BLOCK_ORDER = ['intro', 'banner', 'features', 'tiles', 'wide', 'text', 'cta', 'faq', 'quote', 'note', 'rich'];

export const uid = () => Math.random().toString(36).slice(2, 10);

/* Every image slot in a new block or layout starts with the same placeholder image, ready to be replaced. */
const dummy = (ctx) => ({ src: ctx.placeholder || '', alt: '' });
const url = (ctx, path) => `${(ctx.siteUrl || '').replace(/\/$/, '')}${path}`;

export function createBlock(type, ctx, n = 0) {
    const id = uid();

    switch (type) {
        case 'intro':
            return { id, type, eyebrow: '', heading: ctx.title || 'Your headline', lead: 'Sum it up in one or two sentences: what this is about and why it matters.' };
        case 'banner':
            return {
                id,
                type,
                image: dummy(ctx),
                focus: '',
                eyebrow: 'Highlight',
                heading: 'A bold headline',
                text: 'Describe the main idea in a sentence or two. Keep it short: the picture does most of the talking.',
                listStyle: 'none',
                items: [],
                reverse: n % 2 === 1,
                background: BANNER_COLOURS[(n % 6) + 1],
                light: false,
            };
        case 'features':
            return {
                id,
                type,
                heading: 'At a glance',
                items: [
                    { title: 'First point', text: 'A short supporting line.', image: dummy(ctx) },
                    { title: 'Second point', text: 'A short supporting line.', image: dummy(ctx) },
                    { title: 'Third point', text: 'A short supporting line.', image: dummy(ctx) },
                ],
            };
        case 'tiles':
            return {
                id,
                type,
                heading: '',
                items: [
                    { image: dummy(ctx), caption: 'Caption one' },
                    { image: dummy(ctx), caption: 'Caption two' },
                ],
            };
        case 'wide':
            return { id, type, image: dummy(ctx), focus: '', heading: 'A moment worth sharing', text: 'A line or two over the photo.' };
        case 'text':
            return { id, type, eyebrow: '', heading: 'A section heading', body: 'Write a paragraph here.\n\nStart a new paragraph with an empty line.' };
        case 'quote':
            return { id, type, text: 'A short line from a happy client, a team member or an expert.', by: 'Name, City' };
        case 'note':
            return { id, type, label: 'Good to know', text: 'A helpful tip readers should not miss.' };
        case 'rich':
            return { id, type, html: '<p>Write anything here, with headings, lists, links, tables and images.</p>' };
        case 'cta':
            return {
                id,
                type,
                eyebrow: '',
                heading: 'Ready to get started?',
                text: 'A short line that gives people a reason to click.',
                primary: { label: 'Get in touch', url: url(ctx, '/contact') },
                secondary: { label: '', url: '' },
                background: '#1f2a24',
                light: true,
            };
        case 'faq':
            return {
                id,
                type,
                heading: 'Frequently asked questions',
                intro: '',
                items: [
                    { q: 'What is the first question people ask?', a: 'Answer it clearly in a sentence or two.' },
                    { q: 'What do people often want to know next?', a: 'Keep answers short and link to more detail if needed.' },
                    { q: 'How can people get more help?', a: 'Tell them how to contact you.' },
                ],
            };
    }

    return null;
}

const with_ = (ctx, type, patch, n = 0) => ({ ...createBlock(type, ctx, n), ...patch });

/*
 * Ready-made layouts. Each one is a starting point the editor fills the
 * canvas with; every block stays fully editable afterwards.
 */
export const TEMPLATES = {
    page: [
        {
            key: 'standard',
            name: 'Standard page',
            description: 'Intro, overview, highlights, details, FAQ and a call to action.',
            build: (ctx) => [
                with_(ctx, 'intro', { eyebrow: '' }),
                with_(ctx, 'text', { heading: 'Overview', body: 'Introduce the page in a paragraph or two.' }),
                with_(ctx, 'features', { heading: 'At a glance' }),
                with_(ctx, 'text', { heading: 'Details', body: 'Add the full details here.\n\nStart a new paragraph with an empty line.' }),
                createBlock('faq', ctx),
                with_(ctx, 'cta', { heading: 'Still have questions?', text: 'Our team is happy to help.', primary: { label: 'Contact us', url: url(ctx, '/contact') } }),
            ],
        },
        {
            key: 'about',
            name: 'About us',
            description: 'Your story, what you stand for, a testimonial and a call to action.',
            build: (ctx) => [
                with_(ctx, 'intro', { eyebrow: 'About us', lead: 'Who you are and what you do, in one or two sentences.' }),
                with_(ctx, 'banner', { eyebrow: 'Our story', heading: 'How it all started', text: 'Tell the story behind the business: when it began, why, and what has changed since.' }, 0),
                with_(ctx, 'features', {
                    heading: 'What we stand for',
                    items: [
                        { title: 'Quality', text: 'What you never compromise on.', image: dummy(ctx) },
                        { title: 'People', text: 'How you treat clients and your team.', image: dummy(ctx) },
                        { title: 'Trust', text: 'Why people can rely on you.', image: dummy(ctx) },
                    ],
                }),
                with_(ctx, 'banner', {
                    eyebrow: 'Our team',
                    heading: 'The people behind it',
                    text: 'Introduce the team and what makes them good at what they do.',
                    listStyle: 'points',
                    items: [
                        { strong: '10+ years', text: 'of experience' },
                        { strong: 'Hundreds', text: 'of happy clients' },
                        { strong: 'One goal:', text: 'doing great work' },
                    ],
                }, 1),
                createBlock('quote', ctx),
                with_(ctx, 'cta', { heading: 'Let’s work together', text: 'Tell us what you need and we will get back to you.', primary: { label: 'Contact us', url: url(ctx, '/contact') } }),
            ],
        },
        {
            key: 'service',
            name: 'Service / landing',
            description: 'Headline, benefits, how it works in steps, FAQ and a call to action.',
            build: (ctx) => [
                with_(ctx, 'intro', { eyebrow: 'Our services', lead: 'Say what you offer and who it is for in one or two sentences.' }),
                with_(ctx, 'features', {
                    heading: 'Why choose us',
                    items: [
                        { title: 'Benefit one', text: 'A short supporting line.', image: dummy(ctx) },
                        { title: 'Benefit two', text: 'A short supporting line.', image: dummy(ctx) },
                        { title: 'Benefit three', text: 'A short supporting line.', image: dummy(ctx) },
                        { title: 'Benefit four', text: 'A short supporting line.', image: dummy(ctx) },
                    ],
                }),
                with_(ctx, 'banner', {
                    eyebrow: 'How it works',
                    heading: 'Simple from start to finish',
                    text: 'Walk people through the process.',
                    listStyle: 'steps',
                    items: [
                        { strong: 'Get in touch.', text: 'Tell us what you need.' },
                        { strong: 'We plan it.', text: 'You get a clear proposal.' },
                        { strong: 'We deliver.', text: 'On time and on budget.' },
                    ],
                }, 0),
                createBlock('quote', ctx),
                createBlock('faq', ctx),
                with_(ctx, 'cta', { heading: 'Ready to get started?', text: 'It only takes a minute to get in touch.', primary: { label: 'Get a quote', url: url(ctx, '/contact') } }),
            ],
        },
        {
            key: 'legal',
            name: 'Policy / legal',
            description: 'Plain, readable sections for privacy, terms or refund policies.',
            build: (ctx) => [
                with_(ctx, 'intro', { eyebrow: '', lead: 'A one-line summary of what this policy covers.' }),
                with_(ctx, 'note', { label: 'Last updated', text: 'Write the date this policy last changed.' }),
                with_(ctx, 'text', { heading: '1. Introduction', body: 'Explain who you are and what this policy is for.' }),
                with_(ctx, 'text', { heading: '2. What this covers', body: 'Describe the information, services or situations this policy applies to.' }),
                with_(ctx, 'text', { heading: '3. Your rights', body: 'List what people can ask for and how to do it.' }),
                with_(ctx, 'text', { heading: '4. Contact us', body: 'Tell people how to reach you with questions about this policy.' }),
            ],
        },
    ],
    blog: [
        {
            key: 'article',
            name: 'Article',
            description: 'Opening paragraph, two main ideas, a quote and a key takeaway.',
            build: (ctx) => [
                with_(ctx, 'text', {
                    heading: '',
                    body: 'Open with a short paragraph that tells readers what this post is about and why it is worth their time.\n\nKeep paragraphs short: two to four sentences read best on a phone.',
                }),
                with_(ctx, 'text', { heading: 'The first big idea', body: 'Explain your first point. Add a photo or a quote below to break up the text.' }),
                with_(ctx, 'wide', { heading: '', text: '' }),
                with_(ctx, 'text', { heading: 'The second big idea', body: 'Go deeper, or share tips readers can use straight away.' }),
                createBlock('quote', ctx),
                with_(ctx, 'note', { label: 'Key takeaway', text: 'Sum up the post in a sentence readers will remember.' }),
            ],
        },
        {
            key: 'how-to',
            name: 'How-to guide',
            description: 'What you need, step-by-step instructions, tips and next steps.',
            build: (ctx) => [
                with_(ctx, 'text', { heading: '', body: 'Say what readers will be able to do by the end of this guide, and how long it takes.' }),
                with_(ctx, 'note', { label: 'You will need', text: 'List the tools, accounts or materials needed before starting.' }),
                with_(ctx, 'banner', {
                    eyebrow: 'Step by step',
                    heading: 'How to do it',
                    text: '',
                    listStyle: 'steps',
                    items: [
                        { strong: 'First,', text: 'describe the first step.' },
                        { strong: 'Next,', text: 'describe the second step.' },
                        { strong: 'Then,', text: 'describe the third step.' },
                        { strong: 'Finally,', text: 'check the result.' },
                    ],
                    background: BANNER_COLOURS[0],
                }, 0),
                with_(ctx, 'text', { heading: 'Tips for better results', body: 'Share the small things that make a big difference.' }),
                createBlock('faq', ctx),
                with_(ctx, 'note', { label: 'Key takeaway', text: 'Sum up the guide in one sentence.' }),
            ],
        },
        {
            key: 'list',
            name: 'List post',
            description: 'A short intro, numbered highlights as cards and a closing thought.',
            build: (ctx) => [
                with_(ctx, 'text', { heading: '', body: 'Introduce the list: what it covers and who it is for.' }),
                with_(ctx, 'features', {
                    heading: 'The list',
                    items: [
                        { title: '1. First item', text: 'Why it made the list.', image: dummy(ctx) },
                        { title: '2. Second item', text: 'Why it made the list.', image: dummy(ctx) },
                        { title: '3. Third item', text: 'Why it made the list.', image: dummy(ctx) },
                        { title: '4. Fourth item', text: 'Why it made the list.', image: dummy(ctx) },
                    ],
                }),
                with_(ctx, 'text', { heading: 'Our pick', body: 'If readers only try one thing from this list, which should it be and why?' }),
                createBlock('quote', ctx),
                with_(ctx, 'cta', { heading: 'Want more like this?', text: 'Read more posts on the blog.', primary: { label: 'Read the blog', url: url(ctx, '/blog') } }),
            ],
        },
        {
            key: 'news',
            name: 'News / announcement',
            description: 'The headline news, the details, what it means and a call to action.',
            build: (ctx) => [
                with_(ctx, 'text', { eyebrow: 'Announcement', heading: '', body: 'Lead with the news in one or two sentences: what is happening and when.' }),
                with_(ctx, 'wide', { heading: '', text: '' }),
                with_(ctx, 'text', { heading: 'The details', body: 'Explain the full story: who, what, where and why.' }),
                with_(ctx, 'text', { heading: 'What it means for you', body: 'Tell readers how this affects them.' }),
                with_(ctx, 'cta', { heading: 'Questions about this?', text: 'We are happy to help.', primary: { label: 'Contact us', url: url(ctx, '/contact') } }),
            ],
        },
    ],
};

/* ---------- Rendering ---------- */

const esc = (value) =>
    String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');

const paragraphs = (text) =>
    String(text ?? '')
        .split(/\n\s*\n/)
        .map((part) => part.trim())
        .filter(Boolean)
        .map((part) => `<p>${part.replace(/\n/g, '<br />')}</p>`)
        .join('\n');

const PLACEHOLDER_IMG =
    'data:image/svg+xml,' +
    encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="1000" viewBox="0 0 800 1000"><rect width="800" height="1000" fill="#e9e7e3"/><g fill="none" stroke="#a8a49d" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" transform="translate(320 420)"><rect x="0" y="0" width="160" height="130" rx="18"/><circle cx="52" cy="46" r="16"/><path d="M0 110l50-46 40 34 30-24 40 36"/></g><text x="400" y="640" text-anchor="middle" font-family="Arial, sans-serif" font-size="34" fill="#8a867f">Add an image</text></svg>',
    );

const img = (image, opts, focus = '') => {
    const src = image?.src || (opts.preview ? PLACEHOLDER_IMG : '');
    if (!src) return '';
    const cls = focus ? ` class="ps-focus-${focus}"` : '';

    return `<img src="${esc(src)}" alt="${esc(image?.alt ?? '')}"${cls} />`;
};

const optional = (tag, text, cls = '') => (String(text ?? '').trim() ? `<${tag}${cls ? ` class="${cls}"` : ''}>${esc(String(text).trim())}</${tag}>` : '');

const optionalHtml = (tag, html, cls = '') =>
    String(html ?? '').trim() ? `<${tag}${cls ? ` class="${cls}"` : ''}>${String(html).trim().replace(/\n+/g, '<br />')}</${tag}>` : '';

const listHtml = (style, items) => {
    const rows = (items ?? []).filter((item) => item.strong.trim() || item.text.trim());
    if (style === 'none' || !rows.length) return '';
    const tag = style === 'steps' ? 'ol' : 'ul';
    const lis = rows
        .map((item) => `<li>${item.strong.trim() ? `<strong>${esc(item.strong.trim())}</strong> ` : ''}${item.text.trim().replace(/\n+/g, '<br />')}</li>`)
        .join('\n');

    return `<${tag} class="ps-${style}">\n${lis}\n</${tag}>`;
};

const cols = (count) => `ps-grid ps-cols-${Math.min(4, Math.max(2, count))}`;

const linkHtml = (link, cls) => (link?.label?.trim() ? `<a class="${cls}" href="${esc(link.url.trim() || '#')}">${esc(link.label.trim())}</a>` : '');

const join = (parts) => parts.filter(Boolean).join('\n');

function styleAttrs(block) {
    const style = block.style ?? {};
    const options = STYLE_OPTIONS[block.type];
    const defaults = STYLE_DEFAULTS[block.type];
    const classes = [];
    const css = [];

    if (options.includes('align') && style.align && style.align !== defaults.align) classes.push(`ps-align-${style.align}`);
    if (options.includes('width') && style.width && style.width !== defaults.width) classes.push(`ps-w-${style.width}`);
    if (options.includes('size') && style.size && style.size !== defaults.size) classes.push(`ps-size-${style.size}`);
    if (options.includes('space') && style.space && style.space !== defaults.space) classes.push(`ps-space-${style.space}`);
    if (options.includes('bg') && style.bg) {
        classes.push('ps-has-bg');
        if (style.light) classes.push('ps-light');
        css.push(`background-color:${style.bg}`);
    }

    return { classes, css };
}

function decorate(html, block) {
    const { classes, css } = styleAttrs(block);
    if (!classes.length && !css.length) return html;

    return html.replace(/^<div class="([^"]*)"(?: style="([^"]*)")?/, (_m, cls, existing) => {
        const style = [existing?.replace(/;$/, ''), ...css].filter(Boolean).join(';');

        return `<div class="${[cls, ...classes].join(' ')}"${style ? ` style="${esc(style)};"` : ''}`;
    });
}

function renderInner(block, opts) {
    switch (block.type) {
        case 'intro':
            return join([`<div class="ps-intro">`, optional('p', block.eyebrow, 'ps-eyebrow'), optional('h2', block.heading), optionalHtml('p', block.lead, 'ps-lead'), `</div>`]);

        case 'banner': {
            const style = [`background-color:${block.background || '#f4f3f1'}`, block.light ? 'color:#ffffff' : ''].filter(Boolean).join(';');
            const media = img(block.image, opts, block.focus);

            return join([
                `<div class="ps-banner${block.reverse ? ' ps-banner--reverse' : ''}" style="${esc(style)};">`,
                media ? `<div class="ps-media">${media}</div>` : '',
                `<div class="ps-copy">`,
                optional('p', block.eyebrow, 'ps-eyebrow'),
                optional('h2', block.heading),
                paragraphs(block.text),
                listHtml(block.listStyle, block.items),
                `</div>`,
                `</div>`,
            ]);
        }

        case 'features': {
            const items = block.items
                .map((item) => join([`<div class="ps-feature">`, img(item.image, { preview: false }), optional('p', item.title, 'ps-feature-title'), paragraphs(item.text), `</div>`]))
                .join('\n');

            return join([`<div class="ps-features">`, optional('h2', block.heading, 'ps-heading'), `<div class="${cols(block.items.length)}">`, items, `</div>`, `</div>`]);
        }

        case 'tiles': {
            const items = block.items.map((item) => join([`<div class="ps-tile">`, img(item.image, opts), optional('p', item.caption, 'ps-caption'), `</div>`])).join('\n');

            return join([`<div class="ps-tiles">`, optional('h2', block.heading, 'ps-heading'), `<div class="${cols(block.items.length)}">`, items, `</div>`, `</div>`]);
        }

        case 'wide': {
            const copy = block.heading.trim() || block.text.trim() ? join([`<div class="ps-wide-copy">`, optional('h2', block.heading), paragraphs(block.text), `</div>`]) : '';

            return join([`<div class="ps-wide">`, img(block.image, opts, block.focus), copy, `</div>`]);
        }

        case 'text':
            return join([`<div class="ps-text">`, optional('p', block.eyebrow, 'ps-eyebrow'), optional('h2', block.heading), paragraphs(block.body), `</div>`]);

        case 'quote':
            return join([`<div class="ps-quote">`, optional('p', block.text, 'ps-quote-text'), optional('p', block.by, 'ps-quote-by'), `</div>`]);

        case 'note': {
            const label = block.label.trim() ? `<strong>${esc(block.label.trim().replace(/:$/, ''))}:</strong> ` : '';

            return `<div class="ps-note">\n<p>${label}${block.text.trim().replace(/\n+/g, '<br />')}</p>\n</div>`;
        }

        case 'rich':
            return `<div class="ps-rich">\n${block.html.trim()}\n</div>`;

        case 'cta': {
            const actions = [linkHtml(block.primary, 'ps-btn'), linkHtml(block.secondary, 'ps-btn ps-btn--outline')].filter(Boolean).join('');

            return join([
                `<div class="ps-cta${block.light ? ' ps-light' : ''}" style="background-color:${esc(block.background || '#f4f3f1')};">`,
                optional('p', block.eyebrow, 'ps-eyebrow'),
                optional('h2', block.heading),
                paragraphs(block.text),
                actions ? `<p class="ps-actions">${actions}</p>` : '',
                `</div>`,
            ]);
        }

        case 'faq': {
            const items = block.items
                .filter((item) => item.q.trim())
                .map((item) => join([`<div class="ps-faq-item">`, optional('p', item.q, 'ps-faq-q'), `<div class="ps-faq-a"><div>`, paragraphs(item.a), `</div></div>`, `</div>`]))
                .join('\n');

            return join([`<div class="ps-faq">`, optional('h2', block.heading, 'ps-heading'), optionalHtml('p', block.intro, 'ps-faq-intro'), items ? `<div class="ps-faq-list">\n${items}\n</div>` : '', `</div>`]);
        }
    }

    return '';
}

export function renderBlock(block, opts = {}) {
    return decorate(renderInner(block, opts), block);
}

export function isEmptyBlock(block) {
    return (
        renderBlock(block)
            .replace(/<img[^>]*>/g, 'IMG')
            .replace(/<[^>]+>/g, '')
            .trim() === ''
    );
}

export function serialize(blocks) {
    const visible = blocks.filter((block) => !isEmptyBlock(block));

    if (visible.length && visible.every((block) => block.type === 'rich' && !styleAttrs(block).classes.length)) {
        return visible.map((block) => block.html.trim()).join('\n\n');
    }

    return visible.map((block) => renderBlock(block)).join('\n\n');
}

/* ---------- Parsing saved HTML back into blocks ---------- */

const escText = (value) => value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
const INLINE_TAGS = { STRONG: 'strong', B: 'strong', EM: 'em', I: 'em', U: 'u' };

export function cleanInline(html) {
    const doc = new DOMParser().parseFromString(`<body>${html}</body>`, 'text/html');

    const walk = (node) => {
        if (node.nodeType === Node.TEXT_NODE) return escText((node.textContent ?? '').replace(/ /g, ' ').replace(/\s*\n\s*/g, ' '));
        if (node.nodeType !== Node.ELEMENT_NODE) return '';

        const inner = Array.from(node.childNodes).map(walk).join('');

        if (node.tagName === 'BR') return '\n';
        if (/^(P|DIV|LI|H[1-6]|BLOCKQUOTE)$/.test(node.tagName)) return `${inner}\n\n`;
        if (!inner) return '';

        const tag = INLINE_TAGS[node.tagName];
        if (tag) return `<${tag}>${inner}</${tag}>`;

        if (node.tagName === 'A') {
            const href = (node.getAttribute('href') ?? '').trim();

            return /^(https?:|mailto:|tel:|\/|#)/i.test(href) ? `<a href="${esc(href)}">${inner}</a>` : inner;
        }

        return inner;
    };

    return Array.from(doc.body.childNodes)
        .map(walk)
        .join('')
        .replace(/[ \t]+\n/g, '\n')
        .replace(/\n{3,}/g, '\n\n')
        .trim();
}

export const plainText = (html) =>
    String(html ?? '')
        .replace(/<[^>]+>/g, '')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&amp;/g, '&')
        .replace(/\s+/g, ' ')
        .trim();

const text = (el) => {
    if (!el) return '';
    const clone = el.cloneNode(true);
    clone.querySelectorAll('br').forEach((br) => br.replaceWith('\n'));

    return (clone.textContent ?? '').replace(/[ \t]+\n/g, '\n').trim();
};

const imgOf = (el) => {
    const image = el?.querySelector('img');

    return { src: image?.getAttribute('src') ?? '', alt: image?.getAttribute('alt') ?? '' };
};

const focusOf = (el) => (el?.querySelector('img')?.className ?? '').match(/ps-focus-(top|center|bottom)/)?.[1] ?? '';

const styleValue = (el, prop) => (el.getAttribute('style') ?? '').match(new RegExp(`(?:^|;)\\s*${prop}\\s*:\\s*([^;]+)`, 'i'))?.[1]?.trim() ?? '';

const directChildren = (el, selector) => Array.from(el.children).filter((child) => child.matches(selector));

const inlineOf = (el) => (el ? cleanInline(el.innerHTML) : '');

const leadingBold = (el) => {
    const first = Array.from(el.childNodes).find((node) => node.nodeType === Node.ELEMENT_NODE || node.textContent?.trim());

    return first && first.nodeType === Node.ELEMENT_NODE && /^(STRONG|B)$/.test(first.tagName) ? first : null;
};

const bodyParagraphs = (els) =>
    els
        .map((p) => inlineOf(p))
        .filter(Boolean)
        .join('\n\n');

const listItems = (list) =>
    list
        ? Array.from(list.querySelectorAll(':scope > li')).map((li) => {
              const lead = text(leadingBold(li));
              const clone = li.cloneNode(true);
              leadingBold(clone)?.remove();

              return { strong: lead, text: inlineOf(clone) };
          })
        : [];

const isLight = (colour) => /^(#fff(fff)?|white|rgb\(\s*255\s*,\s*255\s*,\s*255\s*\))$/i.test(colour.replace(/\s*!important/, ''));

const linkFrom = (a) => ({ label: text(a), url: a?.getAttribute('href') ?? '' });

function styleFrom(el) {
    const cls = el.className;
    const match = (re) => cls.match(re)?.[1];
    const style = {};

    const align = match(/\bps-align-(left|center|right)\b/);
    const width = match(/\bps-w-(narrow|wide|full)\b/);
    const size = match(/\bps-size-(sm|md|lg)\b/);
    const space = match(/\bps-space-(tight|normal|roomy|xl)\b/);

    if (align) style.align = align;
    if (width) style.width = width;
    if (size) style.size = size;
    if (space) style.space = space;
    if (el.classList.contains('ps-has-bg')) {
        style.bg = styleValue(el, 'background-color');
        style.light = el.classList.contains('ps-light');
    }

    return style;
}

function parseBlock(el) {
    const has = (cls) => el.classList.contains(cls);
    const id = uid();

    if (has('ps-intro')) {
        return { id, type: 'intro', eyebrow: text(el.querySelector('.ps-eyebrow')), heading: text(el.querySelector('h2, h3')), lead: inlineOf(el.querySelector('.ps-lead')) };
    }

    if (has('ps-banner')) {
        const copy = el.querySelector('.ps-copy') ?? el;
        const list = copy.querySelector('ul, ol');

        return {
            id,
            type: 'banner',
            image: imgOf(el.querySelector('.ps-media')),
            focus: focusOf(el.querySelector('.ps-media')),
            eyebrow: text(copy.querySelector('.ps-eyebrow')),
            heading: text(copy.querySelector('h2, h3')),
            text: bodyParagraphs(directChildren(copy, 'p:not(.ps-eyebrow)')),
            listStyle: list ? (list.tagName === 'OL' ? 'steps' : 'points') : 'none',
            items: listItems(list),
            reverse: has('ps-banner--reverse'),
            background: styleValue(el, 'background-color') || '#f4f3f1',
            light: isLight(styleValue(el, 'color')),
        };
    }

    if (has('ps-features')) {
        return {
            id,
            type: 'features',
            heading: text(el.querySelector('.ps-heading')),
            items: Array.from(el.querySelectorAll('.ps-feature')).map((item) => ({
                title: text(item.querySelector('.ps-feature-title')),
                text: bodyParagraphs(directChildren(item, 'p:not(.ps-feature-title)')),
                image: imgOf(item),
            })),
        };
    }

    if (has('ps-tiles')) {
        return {
            id,
            type: 'tiles',
            heading: text(el.querySelector('.ps-heading')),
            items: Array.from(el.querySelectorAll('.ps-tile')).map((item) => ({ image: imgOf(item), caption: text(item.querySelector('.ps-caption')) })),
        };
    }

    if (has('ps-wide')) {
        const copy = el.querySelector('.ps-wide-copy');

        return { id, type: 'wide', image: imgOf(el), focus: focusOf(el), heading: text(copy?.querySelector('h2, h3')), text: copy ? bodyParagraphs(directChildren(copy, 'p')) : '' };
    }

    if (has('ps-text')) {
        return { id, type: 'text', eyebrow: text(el.querySelector('.ps-eyebrow')), heading: text(el.querySelector('h2, h3')), body: bodyParagraphs(directChildren(el, 'p:not(.ps-eyebrow)')) };
    }

    if (has('ps-quote')) {
        return { id, type: 'quote', text: text(el.querySelector('.ps-quote-text')), by: text(el.querySelector('.ps-quote-by')) };
    }

    if (has('ps-note')) {
        const p = el.querySelector('p') ?? el;
        const lead = leadingBold(p);
        const isLabel = !!lead && text(lead).endsWith(':');
        const clone = p.cloneNode(true);
        if (isLabel) leadingBold(clone)?.remove();

        return { id, type: 'note', label: isLabel ? text(lead).replace(/:$/, '') : '', text: inlineOf(clone) };
    }

    if (has('ps-cta')) {
        const links = el.querySelectorAll('.ps-actions a');

        return {
            id,
            type: 'cta',
            eyebrow: text(el.querySelector('.ps-eyebrow')),
            heading: text(el.querySelector('h2, h3')),
            text: bodyParagraphs(directChildren(el, 'p:not(.ps-eyebrow):not(.ps-actions)')),
            primary: linkFrom(links[0]),
            secondary: linkFrom(links[1]),
            background: styleValue(el, 'background-color') || '#f4f3f1',
            light: has('ps-light'),
        };
    }

    if (has('ps-faq')) {
        return {
            id,
            type: 'faq',
            heading: text(el.querySelector('.ps-heading')),
            intro: inlineOf(el.querySelector('.ps-faq-intro')),
            items: Array.from(el.querySelectorAll('.ps-faq-item')).map((item) => ({
                q: text(item.querySelector('.ps-faq-q')),
                a: bodyParagraphs(Array.from(item.querySelectorAll('.ps-faq-a p'))),
            })),
        };
    }

    if (has('ps-rich')) {
        return { id, type: 'rich', html: el.innerHTML.trim() };
    }

    return null;
}

export function parse(html) {
    if (!String(html ?? '').trim()) return [];

    const doc = new DOMParser().parseFromString(`<body>${html}</body>`, 'text/html');
    const blocks = [];
    let loose = [];

    const flush = () => {
        const joined = loose.join('\n').trim();
        if (joined) blocks.push({ id: uid(), type: 'rich', html: joined });
        loose = [];
    };

    doc.body.childNodes.forEach((node) => {
        if (node.nodeType === Node.ELEMENT_NODE) {
            const block = parseBlock(node);
            if (block) {
                block.style = styleFrom(node);
                flush();
                blocks.push(block);
                return;
            }
            loose.push(node.outerHTML);
        } else if (node.nodeType === Node.TEXT_NODE && node.textContent?.trim()) {
            loose.push(`<p>${esc(node.textContent.trim())}</p>`);
        }
    });

    flush();

    return blocks;
}

export function blockSummary(block) {
    switch (block.type) {
        case 'quote':
            return plainText(block.text) || 'Quote';
        case 'note':
            return block.label || plainText(block.text) || 'Note';
        case 'cta':
            return block.heading || 'Call to action';
        case 'faq':
            return block.heading || 'FAQ';
        case 'rich':
            return plainText(block.html).slice(0, 60) || 'Rich text';
        default:
            return block.heading || BLOCK_INFO[block.type].name;
    }
}

export const imagesIn = (html) => Array.from(String(html ?? '').matchAll(/<img[^>]+src="([^"]+)"/g), (m) => ({ src: m[1], alt: '' })).filter((i) => !i.src.startsWith('data:'));

/* Opens and closes FAQ answers on the website. */
export function setupFaqs(root = document) {
    root.querySelectorAll('.ps-content .ps-faq:not(.ps-faq--ready)').forEach((faq, f) => {
        faq.querySelectorAll('.ps-faq-item').forEach((item, i) => {
            const question = item.querySelector('.ps-faq-q');
            const answer = item.querySelector('.ps-faq-a');
            if (!question || !answer) return;

            answer.id ||= `ps-faq-${f}-${i}`;
            question.setAttribute('role', 'button');
            question.setAttribute('tabindex', '0');
            question.setAttribute('aria-controls', answer.id);
            question.setAttribute('aria-expanded', 'false');

            const toggle = () => question.setAttribute('aria-expanded', String(item.classList.toggle('is-open')));

            question.addEventListener('click', toggle);
            question.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggle();
                }
            });
        });

        faq.classList.add('ps-faq--ready');
    });
}
