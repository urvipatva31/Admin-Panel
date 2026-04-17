document.addEventListener('DOMContentLoaded', () => {

    
    // === 1. THEME INITIALIZATION ===
    const body = document.body;
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = themeToggle ? themeToggle.querySelector('i') : null;

    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        if (themeIcon) themeIcon.className = 'ti ti-sun';
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isDark = body.classList.toggle('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            if (themeIcon) themeIcon.className = isDark ? 'ti ti-sun' : 'ti ti-moon';
        });
    }

    // === 2. SIDEBAR TOGGLE ===
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('sidebar-toggle');
    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('force-expand');
            }
        });
    }
// === 3. GLOBAL SEARCH LOGIC (FIXED FOR CSS CONFLICT) ===
const searchInput = document.getElementById('global-search-input');
const resultsBox = document.getElementById('search-results');

function performSearch(term) {
    if (term.length < 2) {
        if(resultsBox) resultsBox.style.display = 'none';
        return;
    }

    fetch(`/global-search?term=${encodeURIComponent(term)}`)
        .then(response => response.json())
        .then(data => {
            if (!resultsBox) return;
            
            resultsBox.innerHTML = '';
            // Using Flex to keep it clean and forced display
            resultsBox.style.display = 'block'; 

            if (data.results && data.results.length > 0) {
                // Header for the dropdown
                resultsBox.innerHTML += `<div style="padding:10px; font-size:11px; color:var(--text); opacity:0.6; border-bottom:1px solid var(--border); background:var(--input-bg);">${data.total} matches found</div>`;
                
                data.results.forEach(item => {
                    const finalUrl = `${item.url}${item.url.includes('?') ? '&' : '?'}highlight=${encodeURIComponent(term)}`;
                    
                    // We use var(--text) so it works in both Light and Dark mode
                    resultsBox.innerHTML += `
                        <a href="${finalUrl}" class="search-result-item" style="display:flex; justify-content:space-between; align-items:center; padding:12px 15px; border-bottom:1px solid var(--border); text-decoration:none; color:var(--text); font-size:14px; transition: background 0.2s;">
                            <span>${item.label}</span>
                            <span style="font-size:10px; background:var(--primary); color:white; padding:2px 6px; border-radius:4px; font-weight:bold;">${item.type}</span>
                        </a>`;
                });
            } else {
                resultsBox.innerHTML = '<div style="padding:20px; color:var(--text); opacity:0.5; text-align:center;">No matches found</div>';
            }
        });
}

if (searchInput && resultsBox) {
    // Memory fix: Show results again after page load (Pagination)
    const savedTerm = sessionStorage.getItem('lastSearch');
    if (savedTerm && savedTerm.length >= 2) {
        searchInput.value = savedTerm;
        setTimeout(() => performSearch(savedTerm), 200);
    }

    searchInput.addEventListener('input', function () {
        let term = this.value.trim();
        sessionStorage.setItem('lastSearch', term);
        performSearch(term);
    });

    // Close only on outside click
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.style.display = 'none';
        } else if (searchInput.contains(e.target) && searchInput.value.length >= 2) {
            resultsBox.style.display = 'block';
        }
    });
}

    // === 4. ENHANCED GLOBAL HIGHLIGHTER & NAVIGATOR ===
    const urlParams = new URLSearchParams(window.location.search);
    const highlightTerm = urlParams.get('highlight');

    if (highlightTerm) {
        const term = decodeURIComponent(highlightTerm);
        // Use a more specific selector to avoid highlighting things like sidebar menus
        const contentArea = document.querySelector('main') || document.body;
        const regex = new RegExp(`(${term})`, 'gi');

        // Improved logic: Find all text nodes to avoid breaking HTML structures
        const walker = document.createTreeWalker(contentArea, NodeFilter.SHOW_TEXT, null, false);
        const nodesToReplace = [];

        let node;
        while (node = walker.nextNode()) {
            if (node.nodeValue.toLowerCase().includes(term.toLowerCase()) &&
                !node.parentElement.closest('#sidebar') && // This stops "Dashboard" etc from being highlighted
                !['SCRIPT', 'STYLE', 'INPUT', 'TEXTAREA'].includes(node.parentElement.tagName)) {
                nodesToReplace.push(node);
            }
        }

        nodesToReplace.forEach(node => {
            const span = document.createElement('span');
            span.innerHTML = node.nodeValue.replace(regex, `<mark class="search-highlight" style="background:yellow; transition: all 0.3s;">$1</mark>`);
            node.parentNode.replaceChild(span, node);
        });

        const allHighlights = document.querySelectorAll('.search-highlight');
        const matchCount = allHighlights.length;

        if (matchCount > 0) {
            let currentIndex = 0;

            const nav = document.createElement('div');
            nav.id = 'search-navigator';
            nav.style.cssText = "position:fixed; bottom:20px; right:20px; background:#333; color:#fff; padding:8px 15px; border-radius:30px; display:flex; align-items:center; gap:12px; z-index:9999; box-shadow:0 10px 25px rgba(0,0,0,0.2);";
            nav.innerHTML = `
            <span style="font-size:13px; font-weight:500; border-right:1px solid #555; padding-right:10px;">
                <i class="ti ti-search" style="margin-right:5px;"></i> "${term}"
            </span>
            <span style="font-size:12px; min-width:40px;"><b id="current-idx">1</b> / <b>${matchCount}</b></span>
            <div style="display:flex; gap:5px;">
                <button id="prev-match" style="background:#444; border:none; color:white; cursor:pointer; padding:5px; border-radius:5px;"><i class="ti ti-chevron-up"></i></button>
                <button id="next-match" style="background:#444; border:none; color:white; cursor:pointer; padding:5px; border-radius:5px;"><i class="ti ti-chevron-down"></i></button>
            </div>
            <button id="close-search" style="background:none; border:none; color:#aaa; cursor:pointer;"><i class="ti ti-x"></i></button>
        `;
            document.body.appendChild(nav);

            const updateFocus = (index) => {
                allHighlights.forEach(h => {
                    h.style.background = "yellow";
                    h.style.outline = "none";
                });
                const active = allHighlights[index];
                active.style.background = "#ff9800"; // Distinct orange for focus
                active.style.outline = "2px solid #ff9800";
                active.scrollIntoView({ behavior: 'smooth', block: 'center' });
                document.getElementById('current-idx').innerText = index + 1;
            };

            updateFocus(0);

            document.getElementById('next-match').onclick = () => {
                currentIndex = (currentIndex + 1) % matchCount;
                updateFocus(currentIndex);
            };
            document.getElementById('prev-match').onclick = () => {
                currentIndex = (currentIndex - 1 + matchCount) % matchCount;
                updateFocus(currentIndex);
            };
            document.getElementById('close-search').onclick = () => {
                nav.remove();
                allHighlights.forEach(h => h.style.background = "transparent");
                window.history.replaceState({}, document.title, window.location.pathname);
            };
        }
    }
});