document.querySelectorAll('input[type="password"]').forEach((input) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'password-field';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.append(input);

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'password-toggle';
    toggle.setAttribute('aria-label', 'Tampilkan kata sandi');
    toggle.setAttribute('aria-pressed', 'false');
    toggle.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    toggle.addEventListener('click', () => {
        const showPassword = input.type === 'password';
        input.type = showPassword ? 'text' : 'password';
        toggle.setAttribute('aria-label', showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        toggle.setAttribute('aria-pressed', String(showPassword));
        toggle.innerHTML = showPassword
            ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18M10.6 10.6a2 2 0 0 0 2.8 2.8"></path><path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a14 14 0 0 1-3.1 3.8M6.2 6.2C3.5 8 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.8-.5"></path></svg>'
            : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    });
    wrapper.append(toggle);
});

const searchInput = document.querySelector('#gallery-search-input');
const results = document.querySelector('#gallery-results');
const searchStatus = document.querySelector('#gallery-search-status');

if (searchInput && results) {
    const form = searchInput.closest('form');
    let debounce;
    let activeRequest;

    const loadResults = async (url) => {
        activeRequest?.abort();
        activeRequest = new AbortController();
        searchStatus.textContent = 'Memuat hasil pencarian...';

        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                signal: activeRequest.signal,
            });
            if (!response.ok) {
                throw new Error(`Pencarian gagal (${response.status}).`);
            }

            const data = await response.json();
            results.innerHTML = data.html;
            const currentUrl = new URL(url, window.location.href);
            const query = currentUrl.searchParams.get('q') || '';
            searchInput.value = query;
            window.history.replaceState({}, '', currentUrl);
            searchStatus.textContent = 'Hasil pencarian diperbarui.';
        } catch (error) {
            if (error.name !== 'AbortError') {
                searchStatus.textContent = 'Pencarian tidak dapat dimuat. Silakan coba kembali.';
            }
        }
    };

    searchInput.addEventListener('input', () => {
        window.clearTimeout(debounce);
        debounce = window.setTimeout(() => {
            const url = new URL(form.action, window.location.href);
            if (searchInput.value.trim()) {
                url.searchParams.set('q', searchInput.value.trim());
            }
            loadResults(url);
        }, 250);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = new URL(form.action, window.location.href);
        if (searchInput.value.trim()) {
            url.searchParams.set('q', searchInput.value.trim());
        }
        loadResults(url);
    });

    results.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (link && link.closest('.pagination')) {
            event.preventDefault();
            loadResults(link.href);
        }
    });
}
