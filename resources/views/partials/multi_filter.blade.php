@php
    $selected = array_values(array_map('strval', $selected ?? []));
    $compact = $compact ?? false;
    $buttonLabel = count($selected) === 0
        ? 'Tous'
        : (count($selected) === 1 ? $selected[0] : count($selected) . ' sélectionnés');
@endphp
<div class="dropdown multi-filter {{ $compact ? 'multi-filter-compact' : '' }}" data-multi-filter>
    @if (!$compact)
        <label class="form-label">{{ $label }}</label>
    @endif
    <button type="button"
            class="btn btn-sm {{ $compact ? 'btn-outline-light' : 'btn-outline-primary' }} multi-filter-toggle dropdown-toggle {{ $compact ? 'w-100' : '' }}"
            data-bs-toggle="dropdown"
            data-bs-auto-close="outside"
            data-bs-display="static"
            aria-expanded="false">
        <span class="multi-filter-label">{{ $buttonLabel }}</span>
    </button>
    <div class="dropdown-menu p-2 multi-filter-menu">
        <input type="search" class="form-control form-control-sm mb-2 multi-filter-search" placeholder="Rechercher..." aria-label="Rechercher {{ $label }}">
        <div class="d-flex gap-2 mb-2">
            <button type="button" class="btn btn-sm btn-outline-secondary flex-fill multi-filter-select-visible">Tout cocher</button>
            <button type="button" class="btn btn-sm btn-outline-secondary flex-fill multi-filter-clear-visible">Effacer</button>
        </div>
        <div class="multi-filter-options">
            @foreach ($options as $option)
                @php
                    $option = (string) $option;
                @endphp
                <label class="multi-filter-option dropdown-item d-flex align-items-center gap-2">
                    <input type="checkbox"
                           class="form-check-input mt-0"
                           name="{{ $name }}[]"
                           value="{{ $option }}"
                           form="{{ $formId }}"
                           {{ in_array($option, $selected, true) ? 'checked' : '' }}>
                    <span>{{ $option }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
