(() => {
  'use strict';
  const ready = (fn) => document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn();
  ready(() => {
    const menuButton = document.getElementById('menubtn');
    const menu = document.getElementById('arch-menu');
    const searchButton = document.getElementById('searchbtn');
    const searchForm = document.getElementById('form-search-resp');
    const searchInput = document.getElementById('keyword');
    const suggestionBox = document.getElementById('header_search_autocomplete');

    if (menuButton && menu) menuButton.addEventListener('click', () => {
      const open = menu.style.display !== 'block';
      menu.style.display = open ? 'block' : 'none';
      menuButton.setAttribute('aria-expanded', String(open));
    });
    if (searchButton && searchForm) searchButton.addEventListener('click', () => {
      const open = searchForm.style.display !== 'flex';
      searchForm.style.display = open ? 'flex' : 'none';
      searchButton.setAttribute('aria-expanded', String(open));
      if (open && searchInput) searchInput.focus();
    });

    // Player source switching is explicit. Cross-origin iframe failures cannot be
    // reliably observed by the parent page, so no false automatic failover is shown.
    document.addEventListener('click', (event) => {
      const button = event.target.closest('.player-source');
      if (!button) return;
      const iframe = document.getElementById('player');
      const src = button.getAttribute('data-src');
      if (!iframe || !src || !/^https:\/\/(fmov\.my|movietvapi\.stream)\//i.test(src)) return;
      iframe.src = src;
      document.querySelectorAll('.player-source').forEach((item) => {
        const active = item === button;
        item.classList.toggle('is-active', active);
        item.setAttribute('aria-pressed', String(active));
      });
    });

    if (searchInput && suggestionBox) {
      let timer = 0;
      let controller = null;
      const hide = () => { suggestionBox.innerHTML = ''; suggestionBox.style.display = 'none'; };
      const render = (items) => {
        suggestionBox.replaceChildren();
        if (!items.length) { hide(); return; }
        const list = document.createElement('ul');
        items.forEach((item) => {
          const li = document.createElement('li');
          const type = document.createElement('span');
          type.className = 'tpe';
          type.textContent = item.type === 'tv' ? 'TV' : 'Movie';
          const link = document.createElement('a');
          link.className = 'ss-title';
          link.textContent = item.title;
          link.href = '/' + (item.type === 'tv' ? 'tv' : 'movies') + '/' + Number(item.id) + '/' + encodeURIComponent(item.slug);
          li.append(type, link);
          list.append(li);
        });
        suggestionBox.append(list);
        suggestionBox.style.display = 'block';
      };
      searchInput.addEventListener('input', () => {
        window.clearTimeout(timer);
        const query = searchInput.value.trim();
        if (query.length < 2) { hide(); return; }
        timer = window.setTimeout(async () => {
          if (controller) controller.abort();
          controller = new AbortController();
          try {
            const response = await fetch('/ajax-search.php?keyword=' + encodeURIComponent(query), {
              headers: { 'Accept': 'application/json' }, signal: controller.signal
            });
            if (!response.ok) throw new Error('Search request failed');
            const data = await response.json();
            render(Array.isArray(data.results) ? data.results : []);
          } catch (error) {
            if (error.name !== 'AbortError') hide();
          }
        }, 250);
      });
      searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') hide();
        if (event.key === 'Enter' && searchInput.value.trim().length >= 2) {
          event.preventDefault();
          window.location.href = '/search/' + encodeURIComponent(searchInput.value.trim().replace(/\s+/g, '-'));
        }
      });
      document.addEventListener('click', (event) => {
        if (!event.target.closest('.search_responsive')) hide();
      });
    }
  });
})();
