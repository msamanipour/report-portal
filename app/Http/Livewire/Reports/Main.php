<?php

namespace App\Http\Livewire\Reports;

use App\Models\Reports;
use App\Models\Times;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Main extends Component
{
    public $users, $times, $reports, $result, $chart, $report;

    public function mount()
    {
        $this->users = User::where('status', '1')->get();
        $this->times = Times::where('status', '1')->get();
        $this->reports = Reports::whereBetween('created_at', [Carbon::now()->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])
            ->get()->groupBy('creator_user')->toArray();
        $this->renderData();
    }

    public function renderData()
    {
        foreach ($this->reports as $key => $item) {
            foreach ($item as $time) {
                $this->result[$key][$time['time']] = [
                    'body' => $time['body'],
                    'push' => $time['push'],
                ];
            }
        }
    }

    public function renderChart()
    {
        $this->chart = [];
        foreach (Reports::whereBetween('created_at', [Carbon::now()->format('Y-m-d 00:00:00'), Carbon::now()->format('Y-m-d 23:59:59')])
                     ->get()->groupBy('creator_user')->toArray() as $key => $item) {
            $this->chart[$key] = count($item) * 100 / count($this->times);
        }
    }

    public function render()
    {
        $this->renderChart();
        return view('pages.reports.main');
    }

    public function changeReport()
    {
        foreach ($this->result as $user => $item) {
            foreach ($item as $time => $body) {
                if (Carbon::now()->addMinutes(10)->format('H:i') < $time) {
                    if (empty($body['body']) && !$body['push']) {
                        $report = Reports::where('date', Carbon::now()->format('Y-m-d'))->where('creator_user', $user)->where('time', $time)->first();
                        if ($report)
                            $report->delete();
                    } else {
                        Reports::updateOrCreate([
                            'date' => Carbon::now()->format('Y-m-d'),
                            'creator_user' => $user,
                            'time' => $time,
                        ], [
                            'body' => $body['body'] ?? '',
                            'push' => ($body['push']) ? 1 : 0,
                        ]);
                    }
                }
            }
        }
        $this->renderChart();
        $this->dispatchBrowserEvent('contentChanged', ['newName' => $this->chart]);
    }

//    public function prevTime($id)
//    {
//        $time = Times::find($id - 1);
//        return (isset($time)) ? $time->time : false;
//
//    }
//
//    public function nextTime($id)
//    {
//        $time = Times::find($id + 1);
//        return (isset($time)) ? $time->time : false;
//
//    }
}
