<?php

namespace App\Http\Livewire\Reports;

use App\Models\Reports;
use App\Models\Times;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Main extends Component
{
    public $users, $times, $reports, $result, $report;

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
                    'body' => $time['body']
                ];
            }
        }
    }

    public function render()
    {
        return view('pages.reports.main');
    }

    public function changeReport()
    {
        foreach ($this->result as $user => $item) {
            foreach ($item as $time => $body) {
                if (\Carbon\Carbon::now()->format('H:i') < $time) {
                    Reports::updateOrCreate([
                        'date' => Carbon::now()->format('Y-m-d'),
                        'creator_user' => $user,
                        'time' => $time,
                    ], [
                        'body' => $body['body'],
                    ]);
                }
            }
        }
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
