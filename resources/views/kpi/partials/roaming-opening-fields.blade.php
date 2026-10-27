<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label for="roaming-opening-country" class="form-label">Pays</label>
        <input id="roaming-opening-country" type="text" name="country" value="{{ old('country', $openingValues['country'] ?? '') }}" class="form-control @error('country') is-invalid @enderror" required maxlength="100">
        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="roaming-opening-code" class="form-label">Code</label>
        <input id="roaming-opening-code" type="text" name="code" value="{{ old('code', $openingValues['code'] ?? '') }}" class="form-control @error('code') is-invalid @enderror" required maxlength="255">
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="roaming-opening-operator" class="form-label">Opérateur</label>
        <input id="roaming-opening-operator" type="text" name="operator" value="{{ old('operator', $openingValues['operator'] ?? '') }}" class="form-control @error('operator') is-invalid @enderror" required maxlength="150">
        @error('operator')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<fieldset>
    <legend class="fs-6 mb-2">Services ouverts</legend>
    <div class="row g-2">
        @foreach ($serviceColumns as $column)
            <div class="col-sm-6 col-lg-4">
                <div class="form-check">
                    <input class="form-check-input service-toggle" type="checkbox" name="{{ $column }}" value="1" id="roaming-opening-{{ $column }}" {{ old($column, $openingValues[$column] ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="roaming-opening-{{ $column }}">{{ $columnLabels[$column] }}</label>
                </div>
            </div>
        @endforeach
    </div>
</fieldset>
