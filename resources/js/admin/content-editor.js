import {
    BANNER_COLOURS,
    BLOCK_INFO,
    BLOCK_ORDER,
    blockSummary,
    cleanInline,
    createBlock,
    imagesIn,
    parse,
    plainText,
    renderBlock,
    SECTION_COLOURS,
    serialize,
    STYLE_DEFAULTS,
    STYLE_OPTIONS,
    TEMPLATES,
    uid,
} from './content-blocks';

/* Lucide icon paths used inside the builder. */
const ICONS = {
    intro: '<path d="M6 12h12"/><path d="M6 20V4"/><path d="M18 20V4"/>',
    banner: '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/>',
    features: '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
    tiles: '<path d="M18 22H4a2 2 0 0 1-2-2V6"/><path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18"/><circle cx="12" cy="8" r="2"/><rect width="16" height="16" x="6" y="2" rx="2"/>',
    wide: '<rect width="20" height="12" x="2" y="6" rx="2"/>',
    text: '<path d="M15 12H3"/><path d="M17 18H3"/><path d="M21 6H3"/>',
    quote: '<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/><path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>',
    note: '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
    rich: '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
    cta: '<path d="M14 4.1 12 6"/><path d="m5.1 8-2.9-.8"/><path d="m6 12-1.9 2"/><path d="M7.2 2.2 8 5.1"/><path d="M9.037 9.69a.498.498 0 0 1 .653-.653l11 4.5a.5.5 0 0 1-.074.949l-4.349 1.041a1 1 0 0 0-.74.739l-1.04 4.35a.5.5 0 0 1-.95.074z"/>',
    faq: '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
    x: '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
    plus: '<path d="M5 12h14"/><path d="M12 5v14"/>',
    undo: '<path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/>',
    redo: '<path d="m15 14 5-5-5-5"/><path d="M20 9H9.5A5.5 5.5 0 0 0 4 14.5A5.5 5.5 0 0 0 9.5 20H13"/>',
    monitor: '<rect width="20" height="14" x="2" y="3" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>',
    tablet: '<rect width="16" height="20" x="4" y="2" rx="2"/><path d="M12 18h.01"/>',
    mobile: '<rect width="14" height="20" x="5" y="2" rx="2"/><path d="M12 18h.01"/>',
    list: '<path d="M21 12h-8"/><path d="M21 6H8"/><path d="M21 18h-8"/><path d="M3 6v4c0 1.1.9 2 2 2h3"/><path d="M3 10v6c0 1.1.9 2 2 2h3"/>',
    panel: '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M15 3v18"/>',
    up: '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
    down: '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
    copy: '<rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>',
    trash: '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>',
    grip: '<circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/>',
    sparkles: '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>',
    layout: '<rect width="18" height="7" x="3" y="3" rx="1"/><rect width="9" height="7" x="3" y="14" rx="1"/><rect width="5" height="7" x="16" y="14" rx="1"/>',
    check: '<path d="M20 6 9 17l-5-5"/>',
    save: '<path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/>',
    upload: '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>',
    images: '<path d="M18 22H4a2 2 0 0 1-2-2V6"/><path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18"/><circle cx="12" cy="8" r="2"/><rect width="16" height="16" x="6" y="2" rx="2"/>',
    image: '<rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
    bold: '<path d="M6 12h9a4 4 0 0 1 0 8H7a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h7a4 4 0 0 1 0 8"/>',
    italic: '<line x1="19" x2="10" y1="4" y2="4"/><line x1="14" x2="5" y1="20" y2="20"/><line x1="15" x2="9" y1="4" y2="20"/>',
    link: '<path d="M9 17H7A5 5 0 0 1 7 7h2"/><path d="M15 7h2a5 5 0 1 1 0 10h-2"/><line x1="8" x2="16" y1="12" y2="12"/>',
    chevron: '<path d="m6 9 6 6 6-6"/>',
    edit: '<path d="M12 22h6a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v10"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10.4 12.6a2 2 0 1 1 3 3L8 21l-4 1 1-4Z"/>',
};

export const icon = (name, cls = 'h-4 w-4') =>
    `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" class="${cls}" aria-hidden="true">${ICONS[name] ?? ''}</svg>`;

const DEVICES = [
    { id: 'desktop', label: 'Desktop', icon: 'monitor', width: 'w-full max-w-[1280px]' },
    { id: 'tablet', label: 'Tablet', icon: 'tablet', width: 'w-[820px] max-w-full' },
    { id: 'mobile', label: 'Mobile', icon: 'mobile', width: 'w-[390px] max-w-full' },
];

const PREVIEW_WIDTHS = { intro: 520, text: 520, quote: 520, note: 460, rich: 460, faq: 560 };

const isTyping = (target) => target instanceof HTMLElement && (target.isContentEditable || /^(INPUT|TEXTAREA|SELECT)$/.test(target.tagName));

/* Gives list items a stable key so x-for keeps the right inputs when items move, and every block a style object. */
const keyItems = (block) => {
    if (!block) return block;
    block.style ??= {};
    if (Array.isArray(block.items)) block.items.forEach((item) => (item._k ??= uid()));

    return block;
};

const clone = (value) => JSON.parse(JSON.stringify(value));

export default function registerContentEditor(Alpine) {
    Alpine.data('contentEditor', (config) => ({
        kind: config.kind === 'blog' ? 'blog' : 'page',
        label: config.label || 'Content',
        html: config.html || '',
        initialHtml: config.html || '',
        open: false,
        blocks: [],
        selectedId: null,
        insertAt: null,
        leftTab: 'add',
        device: 'desktop',
        panel: null,
        dropIndex: null,
        movingId: null,
        drag: null,
        past: [],
        future: [],
        snapshot: '[]',
        lastEditAt: 0,
        leaving: false,
        missing: false,
        form: null,

        BLOCK_INFO,
        BLOCK_ORDER,
        DEVICES,
        SECTION_COLOURS,
        BANNER_COLOURS,
        icon,

        init() {
            this.form = this.$root.closest('form');

            this.$watch('blocks', () => this.onBlocksChanged());

            this.form?.addEventListener('submit', (event) => {
                if (config.required && this.isEmpty) {
                    event.preventDefault();
                    this.missing = true;
                    this.$root.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    this.$nextTick(() => {
                        const formData = Alpine.$data(this.form);
                        if (formData && 'submitting' in formData) formData.submitting = false;
                    });
                    return;
                }
                this.leaving = true;
            });

            window.addEventListener('beforeunload', (event) => {
                if (!this.leaving && this.changed) event.preventDefault();
            });

            window.addEventListener('keydown', (event) => this.onKey(event));

            window.addEventListener('admin-theme-changed', () => {
                if (this.selected?.type === 'rich') setTimeout(() => this.mountRich(), 50);
            });

            if (new URLSearchParams(window.location.search).get('editor') === 'content') this.openEditor();
        },

        /* ---------- Context ---------- */

        get ctx() {
            const title = this.form?.querySelector('[name=title]')?.value?.trim() || config.title || '';
            const seen = new Set();
            const images = [...(config.images || []).map((src) => ({ src, alt: title })), ...imagesIn(this.html)].filter((image) => {
                if (!image.src || image.src === config.placeholder || seen.has(image.src)) return false;
                seen.add(image.src);
                return true;
            });

            return { title, images, siteUrl: config.siteUrl || '', placeholder: config.placeholder || '' };
        },

        get templates() {
            return TEMPLATES[this.kind];
        },

        get selected() {
            return this.blocks.find((block) => block.id === this.selectedId) ?? null;
        },

        get selectedIndex() {
            return this.selected ? this.blocks.indexOf(this.selected) : -1;
        },

        get isEmpty() {
            return !plainText(this.html) && !/<img/i.test(this.html);
        },

        get changed() {
            return this.html !== this.initialHtml;
        },

        get outline() {
            return parse(this.html).map((block) => ({ name: BLOCK_INFO[block.type].name, summary: blockSummary(block), type: block.type }));
        },

        get deviceWidth() {
            return DEVICES.find((d) => d.id === this.device).width;
        },

        /* ---------- Opening and closing ---------- */

        openEditor(template = null) {
            const blocks = (template ? this.buildTemplate(template) : parse(this.html)).map(keyItems);
            this.snapshot = JSON.stringify(blocks);
            this.blocks = blocks;
            this.past = [];
            this.future = [];
            this.selectedId = null;
            this.insertAt = null;
            this.panel = null;
            this.open = true;
            this.missing = false;
            document.documentElement.style.overflow = 'hidden';

            if (template) this.html = serialize(blocks);

            const url = new URL(window.location.href);
            url.searchParams.set('editor', 'content');
            window.history.replaceState(window.history.state, '', url);

            this.$nextTick(() => this.$refs.dialog?.focus({ preventScroll: true }));
        },

        closeEditor() {
            this.unmountRich();
            this.open = false;
            this.selectedId = null;
            document.documentElement.style.overflow = '';

            const url = new URL(window.location.href);
            url.searchParams.delete('editor');
            window.history.replaceState(window.history.state, '', url);
        },

        saveForm() {
            if (!this.form) return;
            this.syncRich();
            this.html = serialize(this.blocks);
            this.closeEditor();
            this.$nextTick(() => this.form.requestSubmit());
        },

        /* ---------- History ---------- */

        onBlocksChanged() {
            const next = JSON.stringify(this.blocks);
            if (next === this.snapshot) return;

            const now = Date.now();
            if (now - this.lastEditAt > 800) {
                this.past.push(this.snapshot);
                if (this.past.length > 100) this.past.shift();
            }
            this.future = [];
            this.lastEditAt = now;
            this.snapshot = next;
            this.html = serialize(this.blocks);
            if (!this.isEmpty) this.missing = false;
        },

        checkpoint() {
            this.lastEditAt = 0;
        },

        restore(json) {
            this.unmountRich();
            this.snapshot = json;
            this.lastEditAt = 0;
            this.blocks = JSON.parse(json);
            this.html = serialize(this.blocks);
            if (!this.blocks.some((block) => block.id === this.selectedId)) this.selectedId = null;
            this.$nextTick(() => this.selected?.type === 'rich' && this.mountRich());
        },

        undo() {
            const previous = this.past.pop();
            if (previous === undefined) return;
            this.future.push(this.snapshot);
            this.restore(previous);
        },

        redo() {
            const next = this.future.pop();
            if (next === undefined) return;
            this.past.push(this.snapshot);
            this.restore(next);
        },

        /* ---------- Block operations ---------- */

        buildTemplate(key) {
            const template = this.templates.find((t) => t.key === key) ?? this.templates[0];

            return template.build(this.ctx).map(keyItems);
        },

        useTemplate(key) {
            this.checkpoint();
            this.unmountRich();
            this.blocks = this.buildTemplate(key);
            this.selectedId = null;
            this.panel = null;
            this.$refs.canvas?.scrollTo({ top: 0, behavior: 'smooth' });
        },

        clearAll() {
            this.checkpoint();
            this.unmountRich();
            this.blocks = [];
            this.selectedId = null;
        },

        scrollToBlock(id) {
            requestAnimationFrame(() => this.$refs.canvas?.querySelector(`[data-block="${id}"]`)?.scrollIntoView({ behavior: 'smooth', block: 'center' }));
        },

        select(id) {
            if (this.selectedId === id) return;
            this.unmountRich();
            this.selectedId = id;
            this.insertAt = null;
            if (this.selected?.type === 'rich') this.$nextTick(() => this.mountRich());
        },

        insertBlock(type, at = null) {
            const index = at ?? this.insertAt ?? (this.selectedIndex >= 0 ? this.selectedIndex + 1 : this.blocks.length);
            const block = keyItems(createBlock(type, this.ctx, this.blocks.filter((b) => b.type === type).length));
            this.checkpoint();
            this.blocks.splice(index, 0, block);
            this.insertAt = null;
            this.panel = null;
            this.select(block.id);
            this.scrollToBlock(block.id);
        },

        moveBlock(id, delta) {
            const from = this.blocks.findIndex((b) => b.id === id);
            const to = from + delta;
            if (from < 0 || to < 0 || to >= this.blocks.length) return;
            this.moveTo(from, to);
            this.scrollToBlock(id);
        },

        moveTo(from, to) {
            if (from === to) return;
            this.checkpoint();
            const [block] = this.blocks.splice(from, 1);
            this.blocks.splice(to, 0, block);
        },

        duplicateBlock(id) {
            const index = this.blocks.findIndex((b) => b.id === id);
            if (index < 0) return;
            this.syncRich();
            const copy = { ...clone(this.blocks[index]), id: uid() };
            this.checkpoint();
            this.blocks.splice(index + 1, 0, copy);
            this.select(copy.id);
            this.scrollToBlock(copy.id);
        },

        removeBlock(id) {
            const index = this.blocks.findIndex((b) => b.id === id);
            if (index < 0) return;
            this.unmountRich();
            this.checkpoint();
            this.blocks.splice(index, 1);
            this.selectedId = null;
            const next = this.blocks[Math.min(index, this.blocks.length - 1)];
            if (next) this.select(next.id);
        },

        addBelow(index) {
            this.insertAt = index + 1;
            this.leftTab = 'add';
            this.panel = 'left';
        },

        addItem(list, item, max = 30) {
            if (list.length >= max) return;
            this.checkpoint();
            list.push({ ...item, _k: uid() });
        },

        removeItem(list, index, min = 0) {
            if (list.length <= min) return;
            this.checkpoint();
            list.splice(index, 1);
        },

        moveItem(list, index, delta) {
            const to = index + delta;
            if (to < 0 || to >= list.length) return;
            this.checkpoint();
            const [item] = list.splice(index, 1);
            list.splice(to, 0, item);
        },

        setListStyle(block, style) {
            block.listStyle = style;
            if (style !== 'none' && !block.items.length) block.items.push({ strong: '', text: '', _k: uid() });
        },

        /* ---------- Style panel ---------- */

        hasStyle(option) {
            return this.selected ? STYLE_OPTIONS[this.selected.type].includes(option) : false;
        },

        styleValue(option) {
            const block = this.selected;
            if (!block) return '';

            return block.style?.[option] ?? STYLE_DEFAULTS[block.type][option];
        },

        setStyle(patch) {
            const block = this.selected;
            if (!block) return;
            block.style = { ...(block.style ?? {}), ...patch };
        },

        toggleBg(on) {
            this.setStyle({ bg: on ? SECTION_COLOURS[0] : '', light: on ? !!this.selected.style?.light : false });
        },

        /* ---------- Rendering ---------- */

        render(block) {
            return renderBlock(block, { preview: true });
        },

        summary(block) {
            return blockSummary(block);
        },

        previewMarkup(type) {
            const block = createBlock(type, this.ctx);
            if (block.type === 'rich') {
                block.html = '<h2>Rich text</h2><p>Free-form writing with <strong>bold</strong> and <a href="#">links</a>.</p><ul><li>Bullet lists</li><li>Tables and images</li></ul>';
            }

            return renderBlock(block, { preview: true });
        },

        templateMarkup(key) {
            return this.buildTemplate(key)
                .map((block) => renderBlock(block, { preview: true }))
                .join('\n');
        },

        previewWidth(type) {
            return PREVIEW_WIDTHS[type] ?? 860;
        },

        /* Scales a fixed-width preview down to fit its frame. */
        fitPreview(frame, width, height = null) {
            const inner = frame.firstElementChild;
            const measure = () => {
                if (!frame.clientWidth) return;
                const scale = frame.clientWidth / width;
                inner.style.transform = `scale(${scale})`;
                if (height) {
                    const offset = inner.offsetHeight * scale < height ? (height - inner.offsetHeight * scale) / 2 : 0;
                    inner.style.transform = `translateY(${offset}px) scale(${scale})`;
                }
            };
            measure();
            new ResizeObserver(measure).observe(frame);
        },

        /* ---------- Drag and drop ---------- */

        indexAt(clientY) {
            const els = Array.from(this.$refs.canvas?.querySelectorAll('[data-block]') ?? []);
            const i = els.findIndex((el) => {
                const rect = el.getBoundingClientRect();
                return clientY < rect.top + rect.height / 2;
            });

            return i === -1 ? els.length : i;
        },

        startDrag(event, drag) {
            this.drag = drag;
            event.dataTransfer.effectAllowed = drag.kind === 'new' ? 'copy' : 'move';
            event.dataTransfer.setData('text/plain', drag.type ?? drag.id);
            if (drag.kind === 'move') this.movingId = drag.id;
        },

        endDrag() {
            this.drag = null;
            this.dropIndex = null;
            this.movingId = null;
        },

        onCanvasDragOver(event) {
            if (!this.drag) return;
            event.preventDefault();
            event.dataTransfer.dropEffect = this.drag.kind === 'new' ? 'copy' : 'move';
            this.dropIndex = this.indexAt(event.clientY);
        },

        onCanvasDrop(event) {
            if (!this.drag) return;
            event.preventDefault();
            const index = this.indexAt(event.clientY);

            if (this.drag.kind === 'new') {
                this.insertBlock(this.drag.type, index);
            } else {
                const from = this.blocks.findIndex((b) => b.id === this.drag.id);
                const to = from < index ? index - 1 : index;
                if (from >= 0) this.moveTo(from, to);
                this.select(this.drag.id);
            }

            this.endDrag();
        },

        onOutlineDrop(event, index) {
            if (this.drag?.kind !== 'move') return;
            event.preventDefault();
            const from = this.blocks.findIndex((b) => b.id === this.drag.id);
            if (from >= 0) this.moveTo(from, index);
            this.endDrag();
        },

        /* ---------- Images ---------- */

        async upload(file) {
            const data = new FormData();
            data.append('file', file, file.name);

            try {
                const response = await window.axios.post(config.uploadUrl, data, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content },
                });

                return response.data.location;
            } catch (error) {
                const res = error.response;
                throw res?.data?.errors?.file?.[0] ?? res?.data?.message ?? 'Upload failed. Use a JPG, PNG or WebP image under 2 MB.';
            }
        },

        /* ---------- Rich text block (TinyMCE) ---------- */

        richId() {
            return this.selectedId ? `ps-rich-${this.selectedId}` : null;
        },

        mountRich() {
            const block = this.selected;
            const el = this.$refs.canvas?.querySelector(`[data-rich="${block?.id}"]`);
            if (!block || block.type !== 'rich' || !el || !window.tinymce) return;

            window.tinymce.get(this.richId())?.remove();
            el.id = this.richId();
            el.innerHTML = block.html;
            const isDark = document.documentElement.classList.contains('dark');

            window.tinymce.init({
                target: el,
                license_key: 'gpl',
                menubar: false,
                convert_urls: false,
                skin: isDark ? 'oxide-dark' : 'oxide',
                content_css: false,
                content_style:
                    'body{margin:12px 16px;font-family:ui-sans-serif,system-ui,sans-serif;font-size:15px;line-height:1.6;color:#47464a}' +
                    'h2,h3,h4{color:#1a1c1a;font-weight:700;line-height:1.25}h2{font-size:24px}h3{font-size:19px}a{color:inherit;text-decoration:underline}' +
                    'img{max-width:100%;height:auto}table{border-collapse:collapse;width:100%}td,th{border:1px solid #e6e4e0;padding:6px 10px}',
                plugins: ['lists', 'link', 'image', 'table', 'code', 'autoresize'],
                toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image table | alignleft aligncenter alignright | code',
                autoresize_bottom_margin: 20,
                min_height: 260,
                images_upload_handler: (blobInfo) => this.upload(new File([blobInfo.blob()], blobInfo.filename())),
                setup: (editor) => {
                    const sync = () => {
                        if (this.selected?.id === block.id) block.html = editor.getContent();
                    };
                    editor.on('change input undo redo keyup', sync);
                },
            });
        },

        syncRich() {
            const editor = window.tinymce?.get(this.richId() ?? '');
            if (editor && this.selected?.type === 'rich') this.selected.html = editor.getContent();
        },

        unmountRich() {
            if (!window.tinymce) return;
            this.syncRich();
            window.tinymce.get(this.richId() ?? '')?.remove();
        },

        /* ---------- Keyboard ---------- */

        onKey(event) {
            if (!this.open) return;
            const mod = event.ctrlKey || event.metaKey;
            const key = event.key.toLowerCase();

            if (mod && key === 's') {
                event.preventDefault();
                this.saveForm();
            } else if (mod && !isTyping(event.target) && key === 'z') {
                event.preventDefault();
                event.shiftKey ? this.redo() : this.undo();
            } else if (mod && !isTyping(event.target) && key === 'y') {
                event.preventDefault();
                this.redo();
            } else if (event.key === 'Escape' && !document.querySelector('.tox-dialog, .tox-menu')) {
                if (this.panel) this.panel = null;
                else if (this.selectedId) this.select(null);
                else this.closeEditor();
            }
        },
    }));

    /* Image picker with upload, reuse of images already on the page, alt text and focus. */
    Alpine.data('blockImage', () => ({
        uploading: false,
        error: '',
        picking: false,

        async uploadFile(event, image) {
            const file = event.target.files?.[0];
            if (!file) return;
            this.error = '';
            this.uploading = true;

            try {
                image.src = await this.upload(file);
            } catch (message) {
                this.error = String(message);
            } finally {
                this.uploading = false;
                event.target.value = '';
            }
        },
    }));

    /* Small inline editor: paragraphs, bold, italic and links. Value is cleaned HTML with blank lines between paragraphs. */
    Alpine.data('inlineRich', (get, set) => ({
        last: null,
        linkUrl: null,
        range: null,
        needsSelection: false,
        active: { bold: false, italic: false, link: false },

        init() {
            this.$watch(get, (value) => this.load(value));
            this.load(get());
            this.onSelection = () => this.refresh();
            document.addEventListener('selectionchange', this.onSelection);
        },

        destroy() {
            document.removeEventListener('selectionchange', this.onSelection);
        },

        load(value) {
            if (value === this.last) return;
            this.$refs.box.innerHTML = String(value ?? '')
                .split(/\n\s*\n/)
                .map((part) => part.trim())
                .filter(Boolean)
                .map((part) => `<p>${part.replace(/\n/g, '<br>')}</p>`)
                .join('');
            this.last = value;
        },

        emit() {
            const next = cleanInline(this.$refs.box.innerHTML);
            this.last = next;
            set(next);
        },

        get empty() {
            return !String(get() ?? '').trim();
        },

        refresh() {
            const selection = document.getSelection();
            if (!selection?.anchorNode || !this.$refs.box?.contains(selection.anchorNode)) return;
            const el = selection.anchorNode.nodeType === Node.ELEMENT_NODE ? selection.anchorNode : selection.anchorNode.parentElement;
            this.active = { bold: document.queryCommandState('bold'), italic: document.queryCommandState('italic'), link: !!el?.closest('a') };
            if (!selection.isCollapsed) this.needsSelection = false;
        },

        command(name) {
            this.$refs.box.focus();
            document.execCommand('styleWithCSS', false, 'false');
            document.execCommand(name);
            this.emit();
            this.refresh();
        },

        anchorAt(node) {
            const el = node?.nodeType === Node.ELEMENT_NODE ? node : node?.parentElement;
            const anchor = el?.closest('a') ?? null;

            return anchor && this.$refs.box.contains(anchor) ? anchor : null;
        },

        openLink() {
            const selection = document.getSelection();
            if (!selection?.rangeCount || !this.$refs.box.contains(selection.anchorNode)) return (this.needsSelection = true);
            const anchor = this.anchorAt(selection.anchorNode);
            if (selection.isCollapsed && !anchor) return (this.needsSelection = true);

            const range = selection.getRangeAt(0).cloneRange();
            if (selection.isCollapsed && anchor) range.selectNodeContents(anchor);
            this.range = range;
            this.needsSelection = false;
            this.linkUrl = anchor?.getAttribute('href') ?? '';
            this.$nextTick(() => this.$refs.linkInput?.focus());
        },

        restoreSelection() {
            const selection = document.getSelection();
            if (!this.range || !selection) return;
            this.$refs.box.focus();
            selection.removeAllRanges();
            selection.addRange(this.range);
        },

        applyLink() {
            let href = String(this.linkUrl ?? '').trim();
            if (!href) return (this.linkUrl = null);
            if (!/^(https?:|mailto:|tel:|#|\/)/i.test(href)) href = `https://${href}`;
            this.restoreSelection();
            document.execCommand('createLink', false, href);
            this.linkUrl = null;
            this.emit();
        },

        removeLink() {
            this.restoreSelection();
            document.execCommand('unlink');
            this.linkUrl = null;
            this.emit();
        },

        paste(event) {
            event.preventDefault();
            document.execCommand('insertText', false, event.clipboardData.getData('text/plain'));
        },
    }));
}
