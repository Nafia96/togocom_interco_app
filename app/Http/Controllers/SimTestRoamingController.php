<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SimTestRoamingController extends Controller
{
    private $searchColumns = [
        'msisdn' => 'msisdn',
        'imsi' => 'imsi',
        'rp_name' => 'rp_name',
        'country' => 'country',
        'comments' => 'comments',
    ];

    public function index(Request $request)
    {
        if (session('id') == null) {
            return redirect()->route('home');
        }

        $rows = $this->buildQuery($request)->get();
        $countries = DB::connection('mysql')->table('sim_test_roaming')
            ->whereNotNull('country')->distinct()->orderBy('country')->pluck('country');
        $rpNames = DB::connection('mysql')->table('sim_test_roaming')
            ->whereNotNull('rp_name')->distinct()->orderBy('rp_name')->pluck('rp_name');

        return view('kpi.sim-test-roaming', [
            'rows' => $rows,
            'countries' => $countries,
            'rpNames' => $rpNames,
            'rowCount' => $rows->count(),
        ]);
    }

    public function store(Request $request)
    {
        if (session('id') == null) {
            return redirect()->route('home');
        }

        $values = $this->validatedValues($request);
        if ($this->identityExists($values)) {
            return redirect()->back()->withErrors(['msisdn' => 'Cette SIM existe déjà dans ce groupe.'])->withInput();
        }

        DB::connection('mysql')->table('sim_test_roaming')->insert($values);

        return redirect()->route('kpi.sim-test-roaming', $this->returnQuery($request->input('return_query', '')))
            ->with('success', 'SIM ajoutée avec succès.');
    }

    public function update(Request $request, $id)
    {
        if (session('id') == null) {
            return redirect()->route('home');
        }

        $connection = DB::connection('mysql');
        abort_unless($connection->table('sim_test_roaming')->where('id', $id)->exists(), 404);

        $values = $this->validatedValues($request);
        if ($this->identityExists($values, $id)) {
            return redirect()->back()->withErrors(['msisdn' => 'Cette SIM existe déjà dans ce groupe.'])->withInput(array_merge(
                $request->except(['_token', '_method']),
                ['opening_mode' => 'edit', 'opening_id' => $id]
            ));
        }

        $connection->table('sim_test_roaming')->where('id', $id)->update($values);

        return redirect()->route('kpi.sim-test-roaming', $this->returnQuery($request->input('return_query', '')))
            ->with('success', 'SIM modifiée avec succès.');
    }

    public function destroy(Request $request, $id)
    {
        if (session('id') == null) {
            return redirect()->route('home');
        }

        $deleted = DB::connection('mysql')->table('sim_test_roaming')->where('id', $id)->delete();
        abort_unless($deleted, 404);

        return redirect()->route('kpi.sim-test-roaming', $this->returnQuery($request->input('return_query', '')))
            ->with('success', 'SIM supprimée avec succès.');
    }

    public function export(Request $request)
    {
        if (session('id') == null) {
            return redirect()->route('home');
        }

        $rows = $this->buildQuery($request)->get();
        $columns = [
            'group_number' => 'N°',
            'msisdn' => 'Msisdn',
            'imsi' => 'Imsi',
            'rp_name' => 'RP_name',
            'country' => 'Country',
            'comments' => 'COMMENTAIRES',
        ];

        return response()->streamDownload(function () use ($rows, $columns) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, array_values($columns), ';');
            foreach ($rows as $row) {
                fputcsv($output, array_map(function ($column) use ($row) {
                    return $row->{$column} ?? '';
                }, array_keys($columns)), ';');
            }
            fclose($output);
        }, 'sim_test_roaming.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validatedValues(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'group_number' => ['nullable', 'integer', 'min:1'],
            'msisdn' => ['required', 'string', 'max:20'],
            'imsi' => ['required', 'string', 'max:20'],
            'rp_name' => ['required', 'string', 'max:150'],
            'country' => ['required', 'string', 'max:100'],
            'comments' => ['nullable', 'string', 'max:255'],
            'return_query' => ['nullable', 'string', 'max:4000'],
        ])->validate();

        return [
            'group_number' => $validated['group_number'] ?? null,
            'msisdn' => trim($validated['msisdn']),
            'imsi' => trim($validated['imsi']),
            'rp_name' => trim($validated['rp_name']),
            'country' => trim($validated['country']),
            'comments' => isset($validated['comments']) ? trim($validated['comments']) : null,
        ];
    }

    private function identityExists(array $values, $exceptId = null)
    {
        $query = DB::connection('mysql')->table('sim_test_roaming')
            ->where('msisdn', $values['msisdn'])
            ->where('imsi', $values['imsi'])
            ->where('rp_name', $values['rp_name']);

        if ($values['group_number'] === null) {
            $query->whereNull('group_number');
        } else {
            $query->where('group_number', $values['group_number']);
        }
        if ($exceptId !== null) {
            $query->where('id', '<>', $exceptId);
        }

        return $query->exists();
    }

    private function buildQuery(Request $request)
    {
        $query = DB::connection('mysql')->table('sim_test_roaming');
        foreach (['country' => 'filter_country', 'rp_name' => 'filter_rp_name'] as $column => $filter) {
            $value = trim((string) $request->query($filter, ''));
            if ($value !== '') {
                $query->where($column, $value);
            }
        }
        foreach ($this->searchColumns as $key => $column) {
            $value = trim((string) $request->query('search_' . $key, ''));
            if ($value !== '') {
                $query->where($column, 'like', '%' . $value . '%');
            }
        }

        return $query->orderBy('group_number')->orderBy('country')->orderBy('rp_name')->orderBy('msisdn');
    }

    private function returnQuery($queryString)
    {
        parse_str($queryString, $query);
        $allowed = ['filter_country', 'filter_rp_name'];
        foreach (array_keys($this->searchColumns) as $column) {
            $allowed[] = 'search_' . $column;
        }

        return array_intersect_key($query, array_flip($allowed));
    }
}
