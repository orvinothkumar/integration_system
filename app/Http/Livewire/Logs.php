<?php

namespace App\Http\Livewire;

use App\Models\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Logs extends Component
{
    use WithPagination;
    public $q = '';
    public $sortBy = 'id';
    public $sortAsc = true;
    public $confirmingLogDeletion = false;
    public $confirmingLogAdd = false;
    public $log = array();

    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];

    protected $rules = [
        'log.device_uuid' => 'required',
        'log.ward_name' => 'required',
        'log.room_name' => 'required',
        'log.spo2' => 'required',
        'log.pulse' => 'required',
        'log.respiration' => 'required',
    ];

    public function mount()
    {
        //
    }

    public function render()
    {
        $logs = Log::whereNotNull('created_at')
            ->when($this->q, function ($query) {
                return $query->where(function ($query) {
                    $query->where('device_uuid', 'like', '%' . $this->q . '%')
                        ->orwhere('ward_name', 'like', '%' . $this->q . '%')
                        ->orwhere('room_name', 'like', '%' . $this->q . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC'); //->get()->toArray();
        // dd($logs);
        $query = $logs->toSql();
        $logs = $logs->paginate(10);
        return view('livewire.logs', [
            'logs' => $logs,
            'query' => $query,
        ]);
    }

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function confirmLogDeletion($confirmingLogDeletion)
    {
        $this->confirmingLogDeletion = $confirmingLogDeletion;
    }

    public function deleteLog(Log $log)
    {
        $log->delete();
        $this->confirmingLogDeletion = false;
        session()->flash('success', 'Log Deleted Successfully');
    }

    public function confirmLogAdd()
    {
        $this->reset(['log']);
        $this->confirmingLogAdd = true;
    }

    public function confirmLogEdit(Log $log)
    {
        $this->resetErrorBag();
        $this->log = $log;
        $this->confirmingLogAdd = true;
    }

    public function saveLog()
    {
        $validate = $this->validate();
        if (isset($this->log->id)) {
            $this->log->save();
            $Insertid = $this->log->id;
            session()->flash('success', 'Log Saved Successfully');
        } else {
            $Insertid = Log::create([
                'device_uuid' => trim($this->log['device_uuid']),
                'ward_name' => $this->log['ward_name'],
                'room_name' => $this->log['room_name'],
                'spo2' => $this->log['spo2'],
                'pulse' => $this->log['pulse'],
                'respiration' => $this->log['respiration'],
                'status' => 1,
            ])->id;
            session()->flash('success', 'Log Added Successfully');
        }
        $this->confirmingLogAdd = false;
    }
}
