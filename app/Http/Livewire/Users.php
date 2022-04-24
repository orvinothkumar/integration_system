<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;
    public $q = '';
    public $sortBy = 'id';
    public $sortAsc = true;
    public $confirmingUserDeletion = false;
    public $confirmingUserAdd = false;
    public $user = array();

    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];

    protected $rules = [
        'user.first_name' => 'required',
        'user.last_name' => 'required',
        'user.email' => 'required',
        'user.username' => 'required',
        'user.user_type' => 'required',
        'user.mobile' => 'required',
        'user.dob' => 'required',
        'user.address' => 'required',
        'user.validity_date' => 'required',
        'user.password' => 'required'
    ];

    public function mount()
    {
        //
    }

    public function render()
    {
        $users = User::whereNotNull('created_at')
            ->where('role_id', 2)
            ->with('role')
            ->when($this->q, function ($query) {
                return $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->q . '%')
                        ->orwhere('email', 'like', '%' . $this->q . '%')
                        ->orwhere('mobile', 'like', '%' . $this->q . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');//->get()->toArray();
        // dd($users);
        $query = $users->toSql();
        $users = $users->paginate(10);
        return view('livewire.users', [
            'users' => $users,
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

    public function confirmUserDeletion($confirmingUserDeletion)
    {
        $this->confirmingUserDeletion = $confirmingUserDeletion;
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        $this->confirmingUserDeletion = false;
        session()->flash('success', 'User Deleted Successfully');
    }

    public function confirmUserAdd()
    {
        $this->reset(['user']);
        $this->confirmingUserAdd = true;
    }

    public function confirmUserEdit(User $user)
    {
        $this->resetErrorBag();
        $this->user = $user;
        $this->confirmingUserAdd = true;
    }

    public function saveUser()
    {
        $validate = $this->validate();
        if (isset($this->user->id)) {
            $this->user->save();
            $Insertid = $this->user->id;
            session()->flash('success', 'User Saved Successfully');
        } else {
            $Insertid = User::create([
                'name' => trim($this->user['first_name'].' '.$this->user['last_name']),
                'first_name' => trim($this->user['first_name']),
                'last_name' => trim($this->user['last_name']),
                'username' => $this->user['username'],
                'user_type' => $this->user['user_type'],
                'email' => $this->user['email'],
                'mobile' => $this->user['mobile'],
                'dob' => $this->user['dob'],
                'address' => $this->user['address'],
                'validity_date' => date('Y-m-d H:i:s', strtotime($this->user['validity_date'])),
                'password' => Hash::make($this->user['password']),
                'role_id' => 2,
                'created_by' => 1,
                'is_verified' => 0,
                'status'=> 1
            ])->id;
            session()->flash('success', 'User Added Successfully');
        }
        $this->confirmingUserAdd = false;
    }
}
