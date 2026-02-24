/**
 * E-SIP - Main Application JavaScript
 * Handles global UI interactions and utilities
 */

const ESIP = (() => {
    'use strict';

    // ─── Sidebar Toggle (mobile) ───
    function initSidebar() {
        const toggle   = document.getElementById('sidebar-toggle');
        const sidebar  = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        if (!toggle || !sidebar) return;

        const open  = () => { sidebar.classList.add('open'); backdrop?.classList.add('open'); };
        const close = () => { sidebar.classList.remove('open'); backdrop?.classList.remove('open'); };

        toggle.addEventListener('click', () => sidebar.classList.contains('open') ? close() : open());
        backdrop?.addEventListener('click', close);
    }

    // ─── Flash Messages ───
    function initFlashMessages() {
        document.querySelectorAll('.flash-message').forEach(el => {
            setTimeout(() => {
                el.classList.add('dismissing');
                el.addEventListener('animationend', () => el.remove());
            }, 4000);
        });
    }

    // ─── Confirm Dialogs ───
    function initConfirmActions() {
        document.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', e => {
                if (!confirm(el.dataset.confirm)) e.preventDefault();
            });
        });
    }

    // ─── Timer Utility ───
    class Timer {
        constructor(seconds, onTick, onEnd) {
            this.remaining = seconds;
            this.onTick = onTick;
            this.onEnd  = onEnd;
            this.interval = null;
        }
        start() {
            this.onTick(this.remaining);
            this.interval = setInterval(() => {
                this.remaining--;
                this.onTick(this.remaining);
                if (this.remaining <= 0) {
                    this.stop();
                    if (this.onEnd) this.onEnd();
                }
            }, 1000);
        }
        stop() { clearInterval(this.interval); }
        reset(seconds) { this.stop(); this.remaining = seconds; }
    }

    // ─── Format seconds to MM:SS ───
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }

    // ─── XP Animation ───
    function animateXP(element, from, to, duration = 800) {
        const start = performance.now();
        const step = ts => {
            const elapsed = ts - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = Math.floor(from + (to - from) * eased);
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    // ─── AJAX Helper ───
    async function post(url, data) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(data)
        });
        return res.json();
    }

    // ─── Escape HTML ───
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ─── Debounce ───
    function debounce(fn, ms = 300) {
        let timer;
        return (...args) => { clearTimeout(timer); timer = setTimeout(() => fn(...args), ms); };
    }

    // ─── Initialize ───
    function init() {
        initSidebar();
        initFlashMessages();
        initConfirmActions();

        // Add animation classes to cards on load
        document.querySelectorAll('.card-hover').forEach((card, i) => {
            card.style.animationDelay = `${i * 50}ms`;
            card.classList.add('animate-fade-in');
        });
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Public API
    return { Timer, formatTime, animateXP, post, escapeHtml, debounce };
})();
