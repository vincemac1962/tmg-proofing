<?php
// app/Http/Controllers/ReportsMaintenanceController.php
namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportsMaintenanceController extends Controller
{
    public function index()
    {
        $reports = Report::all();
        return view('reports-maintenance.index', compact('reports'));
    }

    public function show(Report $report)
    {
        return view('reports-maintenance.show', compact('report'));
    }

    public function create()
    {
        return view('reports-maintenance.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_category' => 'required|string',
            'report_name' => 'required|string',
            'report_description' => 'required|string',
            'report_view' => 'required|string',
            'report_fields' => 'required|string',
        ]);

        Report::create($validated);
        return redirect()->route('reports-maintenance.index')->with('success', 'Report created successfully');
    }

    public function edit(Report $report)
    {
        return view('reports-maintenance.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'report_category' => 'required|string',
            'report_name' => 'required|string',
            'report_description' => 'required|string',
            'report_view' => 'required|string',
            'report_fields' => 'required|string',
        ]);

        $report->update($validated);
        return redirect()->route('reports-maintenance.index')->with('success', 'Report updated successfully');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports-maintenance.index')->with('success', 'Report deleted successfully');
    }
}