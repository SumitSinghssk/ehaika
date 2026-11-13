import './bootstrap';

import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { setupFaqs } from './admin/content-blocks';

/*
 * Ethaika website: behaviour and motion. Brief: docs/ethaika-home-prompt.md
 * Progressive: without script every section is readable; with reduced motion
 * nothing travels, while the header, the FAQ and the form still work.
 * Motion is transform, opacity and clip-path only.
 */

gsap.registerPlugin(ScrollTrigger);
window.Alpine = Alpine;
window.Swiper = Swiper;

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
const HEADER = 72;

/* ---------------- smooth scroll and in-page links ---------------- */
let lenis = null;
function setupScroll() {
    if (!reducedMotion) {
        lenis = new Lenis({ lerp: 0.1 });
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((t) => lenis.raf(t * 1000));
        gsap.ticker.lagSmoothing(0);
    }

    const go = (target, immediate = false) => {
        const offset = target.id === 'main' ? 0 : -HEADER - 16;
        if (lenis) lenis.scrollTo(target, { offset, duration: 1.3, immediate });
        else window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY + offset, behavior: immediate || reducedMotion ? 'auto' : 'smooth' });
    };

    // menu links are stored as absolute URLs (…/public/#features): treat any link to this page's #id as in-page
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || a.target === '_blank') return;
        const url = new URL(a.href, location.href);
        const samePage = url.origin === location.origin && url.pathname.replace(/\/$/, '') === location.pathname.replace(/\/$/, '');
        if (!samePage) return;
        if (!url.hash) {
            // a link to this same page without a hash (Home): glide to the top
            e.preventDefault();
            history.replaceState(null, '', location.pathname);
            lenis ? lenis.scrollTo(0, { duration: 1.3 }) : window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }
        const target = document.getElementById(decodeURIComponent(url.hash.slice(1)));
        if (!target) return;
        e.preventDefault();
        if (url.hash !== '#main') history.replaceState(null, '', url.hash);
        go(target);
    });

    // arriving with a hash (or back from the form with errors or a thank-you): land on it
    const landing =
        (location.hash && document.getElementById(decodeURIComponent(location.hash.slice(1)))) ||
        ($('#contact [aria-invalid="true"], #contact [role="status"]') && $('#contact'));
    if (landing) {
        requestAnimationFrame(() => go(landing, true));
        addEventListener('load', () => go(landing, true), { once: true });
    }
}

/* ---------------- header ---------------- */
/*
 * Hides when the visitor scrolls down, comes back after a small scroll up.
 * Driven by the visitor's own input (wheel, touch, keys), so automatic glides never toggle it.
 */
function setupHeader() {
    const header = $('#siteHeader');
    if (!header) return;
    let run = 0;
    let hidden = false;

    const apply = () => {
        if (window.scrollY < 120 || document.documentElement.classList.contains('menu-open')) {
            hidden = false;
            run = 0;
        }
        header.classList.toggle('is-hidden', hidden);
    };
    const intent = (d) => {
        if (!d) return;
        if (d > 0) {
            run = run > 0 ? run + d : d;
            if (run > 14) hidden = true;
        } else {
            run = run < 0 ? run + d : d;
            if (run < -6) hidden = false;
        }
        apply();
    };

    let touchY = null;
    addEventListener('wheel', (e) => intent(e.deltaMode ? e.deltaY * 16 : e.deltaY), { passive: true });
    addEventListener('touchstart', (e) => (touchY = e.touches[0].clientY), { passive: true });
    addEventListener(
        'touchmove',
        (e) => {
            if (touchY === null) return;
            const y = e.touches[0].clientY;
            intent(touchY - y);
            touchY = y;
        },
        { passive: true },
    );
    addEventListener('keydown', (e) => {
        if (e.target.closest('input, textarea, select')) return;
        if (['ArrowDown', 'PageDown', 'End', ' '].includes(e.key)) intent(40);
        else if (['ArrowUp', 'PageUp', 'Home'].includes(e.key)) intent(-40);
    });
    header.addEventListener('focusin', () => {
        hidden = false;
        apply();
    });
    addEventListener('scroll', apply, { passive: true });
    apply();
}

/* ---------------- FAQ: details open and close with a smooth height ---------------- */
function setupDetails() {
    $$('details.faq').forEach((d) => {
        const summary = $('summary', d);
        const body = $('.faq-body', d);
        if (!summary || !body) return;
        summary.addEventListener('click', (e) => {
            if (reducedMotion) return;
            e.preventDefault();
            if (d.open) {
                gsap.to(body, { height: 0, opacity: 0, duration: 0.35, ease: 'power2.inOut', onComplete: () => { d.open = false; gsap.set(body, { clearProps: 'all' }); ScrollTrigger.refresh(); } });
            } else {
                d.open = true;
                gsap.fromTo(body, { height: 0, opacity: 0 }, { height: 'auto', opacity: 1, duration: 0.45, ease: 'power2.out', onComplete: () => { gsap.set(body, { clearProps: 'all' }); ScrollTrigger.refresh(); } });
            }
        });
    });
}

/* ---------------- screen viewer: tabs, auto-advance while in view ---------------- */
function setupViewer() {
    $$('[data-viewer]').forEach(setupOneViewer);
}
function setupOneViewer(root) {
    const tabs = $$('.v-tab', root);
    const panels = $$('.v-panel', root);
    const bars = $$('.v-bar', root);
    let i = 0;
    let tween = null;
    let visible = false;
    const DURATION = 5;

    const show = (n, user = false) => {
        i = (n + tabs.length) % tabs.length;
        tabs.forEach((t, k) => t.setAttribute('aria-selected', String(k === i)));
        panels.forEach((p, k) => {
            p.classList.toggle('opacity-0', k !== i);
            p.classList.toggle('pointer-events-none', k !== i);
            p.toggleAttribute('inert', k !== i);
        });
        bars.forEach((b) => gsap.set(b, { scaleX: 0 }));
        if (tween) tween.kill();
        if (reducedMotion || user || !visible) return;
        tween = gsap.fromTo(bars[i], { scaleX: 0 }, { scaleX: 1, duration: DURATION, ease: 'none', onComplete: () => show(i + 1) });
    };
    tabs.forEach((t, k) =>
        t.addEventListener('click', () => {
            show(k, true);
            gsap.set(bars[k], { scaleX: 1 });
        }),
    );
    root.addEventListener('keydown', (e) => {
        if (!e.target.classList.contains('v-tab')) return;
        if (e.key === 'ArrowRight') { show(i + 1, true); tabs[i].focus(); }
        if (e.key === 'ArrowLeft') { show(i - 1, true); tabs[i].focus(); }
    });
    ScrollTrigger.create({
        trigger: root,
        start: 'top 80%',
        end: 'bottom 20%',
        onToggle: (st) => {
            visible = st.isActive;
            if (visible && !tween) show(i);
            else if (!visible && tween) { tween.kill(); tween = null; }
        },
    });
}


/* ---------------- contact form: sent in the background, answered in place ---------------- */
/*
 * Posts the form with fetch (Accept: application/json). Success replaces the form with the thank-you;
 * validation or bot-check errors (422) appear under each field; other failures show one message.
 * Without script the form posts normally and the page reloads with the same result.
 */
function setupForms() {
    $$('form[data-ajax]').forEach((form) => {
        const box = form.closest('[data-contact]');
        const button = $('button[type="submit"]', form);
        const alertBox = $('[data-form-error]', form);

        const clearErrors = () => {
            $$('.err[data-js]', form).forEach((e) => e.remove());
            $$('[aria-invalid]', form).forEach((el) => { el.removeAttribute('aria-invalid'); el.removeAttribute('aria-describedby'); });
            $$('.err', form).forEach((e) => e.remove());
            if (alertBox) { alertBox.hidden = true; alertBox.textContent = ''; }
        };
        const busy = (on) => {
            button.disabled = on;
            $('[data-label]', button).hidden = on;
            $('[data-sending]', button).hidden = !on;
        };
        const fail = (text) => {
            alertBox.textContent = text;
            alertBox.hidden = false;
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();
            busy(true);
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                    credentials: 'same-origin',
                });
                const data = await res.json().catch(() => ({}));

                if (res.ok && data.ok) {
                    const done = $('template[data-success]', box).content.firstElementChild.cloneNode(true);
                    $('[data-name]', done).textContent = data.name;
                    [...box.children].forEach((el) => { if (el.tagName !== 'TEMPLATE') el.hidden = true; });
                    box.appendChild(done);
                    if (!reducedMotion) gsap.from(done.children, { y: 20, opacity: 0, duration: 0.7, ease: 'expo.out', stagger: 0.08 });
                    done.focus({ preventScroll: true });
                    $('[data-again]', done).addEventListener('click', () => {
                        done.remove();
                        form.reset();
                        [...box.children].forEach((el) => (el.hidden = el.matches('[data-form-error]')));
                        alertBox.hidden = true;
                        $('input:not([type=hidden])', form)?.focus();
                    });
                    return;
                }

                if (res.status === 422 && data.errors) {
                    let first = null;
                    Object.entries(data.errors).forEach(([name, messages]) => {
                        const field = form.elements[name];
                        const wrap = field?.closest?.('.field');
                        if (!field || !wrap) { fail(messages[0]); return; }
                        const id = 'err-' + name;
                        const p = document.createElement('p');
                        p.className = 'err';
                        p.id = id;
                        p.dataset.js = '1';
                        p.textContent = messages[0];
                        wrap.appendChild(p);
                        field.setAttribute('aria-invalid', 'true');
                        field.setAttribute('aria-describedby', id);
                        first ??= field;
                    });
                    first?.focus();
                    return;
                }
                if (res.status === 419) return fail('This page has been open for a while. Please refresh it and send your message again.');
                if (res.status === 429) return fail('Too many messages in a short time. Please wait a minute and try again.');
                fail('Something went wrong and your message was not sent. Please try again.');
            } catch {
                fail('Your message could not be sent. Please check your connection and try again.');
            } finally {
                busy(false);
            }
        });
    });
}

/* ---------------- motion ---------------- */
const rise = (targets, trigger, vars = {}) => {
    const els = typeof targets === 'string' ? $$(targets) : [].concat(targets).filter(Boolean);
    if (!els.length) return;
    els.forEach((el) => (el.dataset.risen = '1'));
    gsap.to(els, {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'expo.out',
        stagger: 0.08,
        ...vars,
        scrollTrigger: { trigger: trigger || els[0], start: 'top 88%', once: true },
    });
};

function setupMotion() {
    window.__ethaikaMotion = true;

    if (reducedMotion) {
        gsap.set('.will-rise', { opacity: 1, y: 0 });
        return;
    }

    // headings: every line rises out of its mask once the heading enters
    $$('h1, h2').forEach((h) => {
        const lines = $$('.line > span', h);
        if (!lines.length) return;
        const hero = h.id === 'hero-title';
        gsap.from(lines, {
            yPercent: 110,
            duration: 1.15,
            ease: 'expo.out',
            stagger: 0.1,
            delay: hero ? 0.1 : 0,
            scrollTrigger: hero ? undefined : { trigger: h, start: 'top 90%', once: true },
        });
    });

    // hero: text follows the title; the product screen lifts upright as the page scrolls
    gsap.to('.hero-in', { opacity: 1, y: 0, duration: 1, ease: 'expo.out', stagger: 0.1, delay: 0.4 });
    const shot = $('.hero-shot');
    if (shot) {
        gsap.from(shot, { y: 60, opacity: 0, duration: 1.4, ease: 'expo.out', delay: 0.6 });
        gsap.fromTo(shot, { rotateX: 16, scale: 0.94 }, { rotateX: 0, scale: 1, ease: 'none', scrollTrigger: { trigger: shot, start: 'top 95%', end: 'top 25%', scrub: true } });
    }
    rise('.trust', '.trust', { stagger: 0.06, duration: 0.8 });

    // why ethaika: the figures card, which counts up, then the button
    rise('.why-card', '.why-card');
    rise('.why-in', '.why-in');
    $$('.count').forEach((el) => {
        const to = parseFloat(el.dataset.to);
        const o = { v: 0 };
        el.textContent = '0';
        gsap.to(o, {
            v: to,
            duration: 1.6,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 92%', once: true },
            onUpdate: () => (el.textContent = Math.round(o.v)),
        });
    });

    // real-time data: text, figures, the screen viewer rises into place
    rise('.rt-in', '#platform', { stagger: 0.1 });
    rise('#platform .stat', '#platform dl', { stagger: 0.1 });
    gsap.from('.viewer .shot', { y: 50, opacity: 0, duration: 1.2, ease: 'expo.out', scrollTrigger: { trigger: '.viewer', start: 'top 85%', once: true } });

    // integrations: text, then the logo tiles one by one
    rise('.int-in', '#integrations');
    rise('.logo', '.int-grid', { stagger: 0.06, duration: 0.9 });

    // features: bento cards in sequence, the photograph settles, then the table rows
    rise('.feat', '#features .feat', { stagger: 0.08 });
    gsap.to('.feat-img', { scale: 1, ease: 'none', scrollTrigger: { trigger: '.feat-img', start: 'top bottom', end: 'bottom top', scrub: true } });
    rise('.cmp', '.cmp');
    gsap.from('.cmp-row', { opacity: 0, y: 14, duration: 0.7, ease: 'expo.out', stagger: 0.05, scrollTrigger: { trigger: '.cmp', start: 'top 80%', once: true } });

    rise('.faq-in', '#faq');
    rise('.faq', '#faq', { stagger: 0.06 });
    rise('.contact-in', '#contact', { stagger: 0.12 });

    // footer: columns, then the name rises at full width
    gsap.from('.foot-rise', { opacity: 0, y: 24, duration: 1, ease: 'expo.out', stagger: 0.08, scrollTrigger: { trigger: '#siteFooter', start: 'top 85%', once: true } });
    gsap.from('.foot-mark .line > span', { yPercent: 100, duration: 1.2, ease: 'expo.out', scrollTrigger: { trigger: '.foot-mark', start: 'top 95%', once: true } });

    // every other part of any page: tiles, cards, panels and paragraphs rise as they enter, in small groups
    ScrollTrigger.batch($$('.will-rise').filter((el) => !el.dataset.risen), {
        start: 'top 90%',
        once: true,
        onEnter: (batch) => gsap.to(batch, { opacity: 1, y: 0, duration: 0.9, ease: 'expo.out', stagger: 0.07 }),
    });
    // photographs settle while they are in view
    $$('.photo img').forEach((img) => gsap.to(img, { scale: 1, ease: 'none', scrollTrigger: { trigger: img, start: 'top bottom', end: 'bottom top', scrub: true } }));

    addEventListener('load', () => ScrollTrigger.refresh());
}

window.setupContentBlocks = setupFaqs;

const boot = () => {
    setupFaqs();
    setupScroll();
    setupHeader();
    setupDetails();
    setupViewer();
    setupForms();
    setupMotion();
};

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
else boot();

Alpine.start();
