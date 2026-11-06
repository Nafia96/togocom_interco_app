document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-multi-filter]').forEach(function (filter) {
        const toggle = filter.querySelector('.multi-filter-toggle');
        const label = filter.querySelector('.multi-filter-label');
        const menu = filter.querySelector('.multi-filter-menu');
        const search = filter.querySelector('.multi-filter-search');
        const optionRows = Array.from(filter.querySelectorAll('.multi-filter-option'));
        const checkboxes = optionRows.map(row => row.querySelector('input[type="checkbox"]'));
        let originalParent = null;
        let originalNextSibling = null;
        let initialSelection = [];

        function selectedValues() {
            return checkboxes.filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);
        }

        function updateLabel() {
            const selected = selectedValues();
            label.textContent = selected.length === 0
                ? 'Tous'
                : (selected.length === 1 ? selected[0] : selected.length + ' sélectionnés');
            toggle.title = selected.length > 1 ? selected.join(', ') : '';
        }

        function normalize(value) {
            return value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase();
        }

        function positionMenu() {
            if (!menu.isConnected || !menu.classList.contains('show')) {
                return;
            }

            const buttonRect = toggle.getBoundingClientRect();
            const margin = 8;
            const width = Math.min(Math.max(buttonRect.width, 260), 320, window.innerWidth - margin * 2);
            menu.style.width = width + 'px';
            menu.style.maxWidth = 'calc(100vw - ' + margin * 2 + 'px)';

            const menuRect = menu.getBoundingClientRect();
            const measuredHeight = menuRect.height;
            const left = Math.max(margin, Math.min(buttonRect.left, window.innerWidth - width - margin));
            let top = buttonRect.bottom + 4;
            if (top + measuredHeight > window.innerHeight - margin && buttonRect.top > measuredHeight + margin) {
                top = buttonRect.top - measuredHeight - 4;
            }

            menu.style.left = left + 'px';
            menu.style.top = Math.max(margin, top) + 'px';
        }

        function filterOptions() {
            const query = normalize(search.value.trim());
            optionRows.forEach(function (row) {
                row.hidden = query !== '' && !normalize(row.textContent.trim()).includes(query);
            });
        }

        toggle.addEventListener('show.bs.dropdown', function () {
            originalParent = menu.parentNode;
            originalNextSibling = menu.nextSibling;
            initialSelection = selectedValues().slice().sort();
            menu.style.position = 'fixed';
            menu.style.zIndex = '1080';
            document.body.appendChild(menu);
        });

        toggle.addEventListener('shown.bs.dropdown', function () {
            positionMenu();
            if (search) {
                search.focus();
            }
            window.addEventListener('scroll', positionMenu, true);
            window.addEventListener('resize', positionMenu);
        });

        toggle.addEventListener('hidden.bs.dropdown', function () {
            window.removeEventListener('scroll', positionMenu, true);
            window.removeEventListener('resize', positionMenu);
            menu.removeAttribute('data-bs-popper');
            menu.removeAttribute('style');
            if (originalParent) {
                originalParent.insertBefore(menu, originalNextSibling && originalNextSibling.parentNode === originalParent ? originalNextSibling : null);
            }

            const currentSelection = selectedValues().slice().sort();
            if (currentSelection.length !== initialSelection.length || currentSelection.some((value, index) => value !== initialSelection[index])) {
                const form = document.getElementById(checkboxes[0] ? checkboxes[0].getAttribute('form') : '');
                if (form) {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            }
        });

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateLabel);
        });
        search.addEventListener('input', filterOptions);

        filter.querySelector('.multi-filter-select-visible').addEventListener('click', function (event) {
            event.stopPropagation();
            optionRows.forEach(function (row) {
                if (!row.hidden) {
                    row.querySelector('input[type="checkbox"]').checked = true;
                }
            });
            updateLabel();
        });

        filter.querySelector('.multi-filter-clear-visible').addEventListener('click', function (event) {
            event.stopPropagation();
            optionRows.forEach(function (row) {
                if (!row.hidden) {
                    row.querySelector('input[type="checkbox"]').checked = false;
                }
            });
            updateLabel();
        });

        menu.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        updateLabel();
    });
});
