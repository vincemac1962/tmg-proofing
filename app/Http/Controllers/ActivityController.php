<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()->orderBy('created_at', 'desc');

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $activities = $query->paginate(10);
        return view('activities.index', [
            'activities' => $activities,
            'title' => 'All Activities'
        ]);
    }

    public function indexByJobId($jobId)
    {
        $activities = Activity::where('job_id', $jobId)->get();
        return view('activities.index', compact('activities', 'jobId'));
    }


    public function jobActivities($id)
    {
        // create Eloquent query to get all activities for a specific job ID
        $activities = Activity::where('job_id', $id)
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->join('proofing_jobs', 'activities.job_id', '=', 'proofing_jobs.id')
            ->join('customers', 'proofing_jobs.customer_id', '=', 'customers.id')
            ->join('proofing_companies', 'proofing_jobs.proofing_company_id', '=', 'proofing_companies.id')
            ->select('activities.*', 'customers.company_name', 'proofing_jobs.contract_reference', 'proofing_jobs.advert_location', 'proofing_jobs.status', 'proofing_companies.name as proofing_company_name', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        // return the view with the activities and title details
        return view('activities.index_by_job_id', [
            'activities' => $activities,
            'title' => "Activities for Job #$id"
        ]);
    }

    public function show($id)
    {
        // return the activity record, username, and proofing job details
        $activity = Activity::with(['proofingJob.customer', 'proofingJob.proofingCompany', 'user'])
            ->select('activities.*', 'users.name as user_name')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->where('activities.id', $id)
            ->firstOrFail();

        return view('activities.show', compact('activity'));
    }

    public function create()
    {
        $activityTypes = ['proof uploaded', 'proof viewed', 'proof amended', 'proof approved'];
        return view('activities.create', compact('activityTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:proofing_jobs,id',
            'user_id' => 'required|exists:users,id',
            'activity_type' => 'required|in:proof uploaded,proof viewed,proof amended,proof approved',
            'notes' => 'nullable|string'
        ]);

        Activity::create($validated);
        return redirect()->route('activities.index');
    }

    public function edit($id)
    {
        // return the activity record, username, and proofing job details
        $activity = Activity::with(['proofingJob.customer', 'proofingJob.proofingCompany', 'user'])
            ->select('activities.*', 'users.name as user_name')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->where('activities.id', $id)
            ->firstOrFail();

        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string'
        ]);
        $activity->update($validated);
        return redirect()->route('activities.job', ['id' => $activity->job_id])->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity, Request $request)
    {
        $activity->delete();
        return redirect()->route('activities.job', ['id' => $request->input('proofingJob')])->with('success', 'Activity record deleted successfully.');
    }
}
