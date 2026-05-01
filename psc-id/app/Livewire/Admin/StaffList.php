<?php

namespace App\Livewire\Admin;

use App\Models\Staff;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StaffList extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $status = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $staff = Staff::query()
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where('full_name', 'like', $term)
                  ->orWhere('staff_id', 'like', $term)
                  ->orWhere('department', 'like', $term)
                  ->orWhere('position', 'like', $term);
            })
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->orderBy('full_name')
            ->paginate(20);

        return view('livewire.admin.staff-list', compact('staff'));
    }
}
