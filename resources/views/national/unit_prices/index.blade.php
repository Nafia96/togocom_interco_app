@extends('template.principal_tamplate3')

@section('title', 'Configuration Prix Unitaire')

@section('content')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Configuration - Prix unitaire</li>
        </ol>
    </nav>
@stop

<div class="card">
    <div class="card-header">
        <h4>Configuration des prix unitaires par sens / période</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('unit_prices.store') }}">
            @csrf
            <div class="row g-2">
                <div class="col-md-4">
                    <label>Direction</label>
                    <select name="direction" class="form-control">
                        <option value="TGT->TGC">TGT->TGC</option>
                        <option value="TGC->TGT">TGC->TGT</option>
                        <option value="TGT->MAT">TGT->MAT</option>
                        <option value="MAT->TGT">MAT->TGT</option>
                        <!-- add more as needed -->
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Période (optionnel)</label>
                    <input type="month" name="period" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Prix unitaire</label>
                    <input type="number" step="0.0001" min="0" name="price" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Enregistrer</button>
                </div>
            </div>
        </form>

        <hr>
        <h5>Historique des prix</h5>
        <table class="table table-striped">
            <thead>
                <tr><th>Direction</th><th>Période</th><th>Prix</th><th>Depuis</th><th>Par</th></tr>
            </thead>
            <tbody>
                @foreach($prices as $p)
                    <tr>
                        <td>{{ $p->direction }}</td>
                        <td>{{ $p->period ?? '-' }}</td>
                        <td>{{ number_format($p->price, 4, ',', ' ') }}</td>
                        <td>{{ $p->effective_from }}</td>
                        <td>{{ $p->created_by }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
