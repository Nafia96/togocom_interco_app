<script>
document.addEventListener('DOMContentLoaded', function() {
    function setEndToMonthEnd(dateStr) {
        if (!dateStr) return null;
        const parts = dateStr.split('-');
        if (parts.length !== 3) return null;
        const year = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1; // 0-based
        const lastDay = new Date(year, month + 1, 0).getDate();
        return `${year}-${String(month + 1).padStart(2, '0')}-${String(lastDay).padStart(2, '0')}`;
    }

    const starts = Array.from(document.querySelectorAll('input[name="start_date"], input#start_date'));
    const ends = Array.from(document.querySelectorAll('input[name="end_date"], input#end_date'));

    starts.forEach(s => {
        // Prefer end input within the same form
        let form = s.closest('form');
        let e = form ? form.querySelector('input[name="end_date"], input#end_date') : null;
        if (!e) e = ends.length ? ends[0] : null;
        if (!e) return;

        function update() {
            const sVal = s.value;
            if (sVal) {
                e.min = sVal;
                if (!e.value || e.value < sVal) {
                    const newEnd = setEndToMonthEnd(sVal);
                    if (newEnd) e.value = newEnd;
                }
            } else {
                e.removeAttribute('min');
            }
        }

        s.addEventListener('change', update);
        e.addEventListener('change', function() {
            if (s.value && e.value && e.value < s.value) e.value = s.value;
        });

        // initialize
        update();
    });
});
</script>

<style>
/* Uniform select widths: base styling applied to form selects inside cards/forms */
.uniform-select-fixed {
    min-width: 160px;
    max-width: 360px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Equalize widths of visible select.form-select elements within the page
    try {
        const containers = Array.from(document.querySelectorAll('.card, form'));
        const selects = [];
        containers.forEach(c => {
            c.querySelectorAll('select.form-select').forEach(s => {
                if (s.offsetParent !== null) selects.push(s);
            });
        });

        if (selects.length) {
            // compute natural widths
            let max = 0;
            selects.forEach(s => {
                const rect = s.getBoundingClientRect();
                if (rect.width > max) max = rect.width;
            });
            // apply a sensible cap and minimum
            const min = 140;
            const cap = 420;
            const width = Math.min(Math.max(max, min), cap);
            selects.forEach(s => {
                s.style.minWidth = width + 'px';
                s.classList.add('uniform-select-fixed');
            });
        }
    } catch (e) {
        // silent
    }
});
</script>

<style>
/* Align breadcrumb items and labels consistently across billing pages */
.breadcrumb.mb-2 {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.6rem;
}
.breadcrumb.mb-2 .breadcrumb-item {
    display: flex;
    align-items: center;
}
.breadcrumb.mb-2 .breadcrumb-item strong {
    display: inline-block;
    min-width: 110px;
}

/* Value area inside breadcrumb: truncate long values and keep visual alignment */
.breadcrumb-value {
    display: inline-block;
    max-width: 240px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
    color: #333;
    font-weight: 500;
}

/* Filters form: keep labels and controls tidy */
form.row.g-3.mb-4 .form-label,
form.row.g-2.mb-3 .form-label {
    display: inline-block;
    min-width: 110px;
}
form.row.g-3.mb-4 .form-control,
form.row.g-3.mb-4 .form-select,
form.row.g-2.mb-3 .form-control,
form.row.g-2.mb-3 .form-select {
    width: 100%;
    box-sizing: border-box;
}

/* Small screens: reduce min widths */
@media (max-width: 768px) {
    .breadcrumb.mb-2 .breadcrumb-item strong,
    form.row.g-3.mb-4 .form-label,
    form.row.g-2.mb-3 .form-label {
        min-width: 0;
    }
    .breadcrumb-value {
        max-width: 140px;
    }
}
</style>
