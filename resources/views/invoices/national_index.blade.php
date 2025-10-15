@extends('template.principal_tamplate3')

@section('content')
<div class="container">
    <h2>Factures nationales</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped" id="nationalInvoicesTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Numéro</th>
                <th>Direction</th>
                <th>Période</th>
                <th>Date facture</th>
                <th>Volume total</th>
                <th>Valorisation</th>
                <th>Montant TTC</th>
                <th>Créé par</th>
                <th>Fichier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $inv)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inv->invoice_number }}</td>
                <td>{{ $inv->direction }}</td>
                <td>{{ $inv->period }}</td>
                <td>{{ $inv->invoice_date ? \Carbon\Carbon::parse($inv->invoice_date)->format('Y-m-d') : '' }}</td>
                <td>{{ number_format($inv->total_volume, 2, ',', ' ') }}</td>
                <td>{{ number_format($inv->total_valorisation, 2, ',', ' ') }}</td>
                <td>{{ number_format($inv->total_ttc, 2, ',', ' ') }}</td>
                <td>{{ $inv->created_by }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('national_invoice.generate', ['id' => $inv->id, 'format' => 'pdf']) }}" class="btn btn-sm btn-success">PDF</a>
                        <a href="{{ route('national_invoice.generate', ['id' => $inv->id, 'format' => 'word']) }}" class="btn btn-sm btn-secondary">Word</a>
                        @if($inv->facture_name && file_exists(public_path('facture/' . $inv->facture_name)))
                            <a href="{{ url('national_invoices/download/' . $inv->facture_name) }}" class="btn btn-sm btn-primary">Télécharger</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('script')
<script>
    $(function(){
        $('#nationalInvoicesTable').DataTable();
    });
</script>
@endsection

@endsection
