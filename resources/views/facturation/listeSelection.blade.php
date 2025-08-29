@extends('template.principal_tamplate')

@section('title', 'Liste des opérateurs')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item"><a href="{{ route('send_invoices') }}">Selection des opérateurs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Liste des opérateurs</li>
        </ol>
    </nav>
@endsection



@section('content')
<div class="col-12 col-md-12 col-lg-12">
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#F5F5DC">
            <h4 class="mb-0">Liste des opérateurs (Envoi des factures)</h4>
            <div class="d-flex align-items-center gap-2">
                <div style="width: 200px;">
                 <button type="button" class="btn btn-sm btn-primary" id="checkAllBtn">
                        Tout cocher / Décocher
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('envoyerFactures') }}">
                @csrf
                <input type="hidden" name="invoice_period" value="{{ $periode }}">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Opérateurs</h5>

                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nom de l’opérateur</th>
                                <th>Email principal</th>
                                <th>Période</th>
                                <th>Facture</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operators as $operator)
                                @php
                                    $facturePath = 'invoices/depart/' . substr($periode, 0, 4) . '/' . $operator->id . '/' . substr($periode, 5, 2) . '.pdf';
                                @endphp
                                <tr>
                                    <!-- Checkbox pour sélectionner l'opérateur -->
                                    <td>
                                        @if (file_exists(public_path($facturePath)))
                                            <input type="checkbox" name="operators[]" value="{{ $operator->id }}">
                                        @endif
                                    </td>
                                    <td>{{ $operator->name }}</td>
                                    <td>{{ $operator->email }}</td>
                                    <td>{{ $periode }}</td>
                                    <td>
                                        @if (file_exists(public_path($facturePath)))
                                            <a class="btn btn-sm btn-success" href="{{ asset($facturePath) }}" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Voir facture
                                            </a>
                                        @else
                                            <span class="btn btn-sm btn-danger disabled">
                                                <i class="fas fa-times-circle"></i> Pas de facture
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success">Envoyer les factures</button>
                </div>
            </form>
        </div>



    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        document.getElementById('checkAllBtn').addEventListener('click', function () {
            let checkboxes = document.querySelectorAll('input[name="operators[]"]');
            let allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        });
    });
</script>
@endsection
