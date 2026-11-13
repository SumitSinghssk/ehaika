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
