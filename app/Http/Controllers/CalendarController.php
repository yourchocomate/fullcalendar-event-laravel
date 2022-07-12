<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {  
            $weekends = DB::table('working_days')
                        ->where('status', 0)
                        ->get(['day'])
                        ->map(function($item) {
                            return $item->day;
                        })->toArray();
            $holidays = DB::table('holidays')
                        ->get()
                        ->map(function($item) {
                            return [
                                'title' => 'Holiday',
                                'start' => Carbon::parse($item->date)->format('Y-m-d'),
                                'end' => Carbon::parse($item->date)->format('Y-m-d'),
                            ];
                        })->toArray();
            $attendances = DB::table('attendances as a')
                    ->where('leave_id', '!=', null)
                    ->get()
                    ->map(function($item) {
                        return [
                            'title' => 'Leave',
                            'start' => Carbon::parse($item->date)->format('Y-m-d'),
                            'end' => Carbon::parse($item->date)->format('Y-m-d'),
                            'color' => 'red',
                        ];
                    })->toArray();

            $data = [...$this->getHolidays($weekends, $request->start), ...$holidays, ...$attendances];
            return response()->json($data);
        }
        return view('calendar');
    }
 
    public function getHolidays($days, $start_date) {

        $date = date('Y-m-d',strtotime($start_date));
        $i = 0;
        $response = array();
        while ($i <= 42) {
            $tsDate = strtotime($date. ' ' .'+ '.$i.' days');
            $day = date('D', $tsDate);
            if(in_array($day, $days)) {
                $data = array();
                $data['title'] = 'Weekend';
                $data['start'] = date('Y-m-d',$tsDate);
                $data['end'] = date('Y-m-d',$tsDate);
                $response[] = $data;
            }
            
            $i++;
        }
        return $response;
    }
}