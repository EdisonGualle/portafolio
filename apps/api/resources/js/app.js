import './bootstrap';

const onReady = (callback) => {
    if (document.readyState !== 'loading') {
        callback();
    } else {
        document.addEventListener('DOMContentLoaded', callback);
    }
};

onReady(() => {
    const header = document.querySelector('[data-site-header]');

    if (header) {
        const updateHeaderState = () => {
            if (window.scrollY > 36) {
                header.dataset.scrolled = 'true';
            } else {
                delete header.dataset.scrolled;
            }
        };

        updateHeaderState();
        window.addEventListener('scroll', updateHeaderState, { passive: true });
    }

    const navLinks = Array.from(document.querySelectorAll('[data-scroll-nav]'));

    if (!navLinks.length) {
        return;
    }

    const seenSections = new Set();
    const sections = [];

    navLinks.forEach((link) => {
        const target = link.getAttribute('href');

        if (!target || !target.startsWith('#')) {
            return;
        }

        const section = document.querySelector(target);

        if (!section || seenSections.has(section.id)) {
            return;
        }

        seenSections.add(section.id);
        sections.push(section);
    });

    if (!sections.length) {
        return;
    }

    const setActiveLink = (id) => {
        navLinks.forEach((link) => {
            if (link.getAttribute('href') === `#${id}`) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    const updateActiveLink = () => {
        let current = sections[0].id;

        sections.forEach((section) => {
            const offset = section.offsetTop - 160;

            if (window.scrollY >= offset) {
                current = section.id;
            }
        });

        setActiveLink(current);
    };

    updateActiveLink();

    window.addEventListener('scroll', updateActiveLink, { passive: true });
    window.addEventListener('resize', updateActiveLink);

    navLinks.forEach((link) => {
        const target = link.getAttribute('href');

        if (!target || !target.startsWith('#')) {
            return;
        }

        const section = document.querySelector(target);

        if (!section) {
            return;
        }

        link.addEventListener('click', (event) => {
            event.preventDefault();
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            setActiveLink(section.id);
        });
    });
});
