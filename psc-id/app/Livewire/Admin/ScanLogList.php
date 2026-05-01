<?php

namespace App\Livewire\Admin;

use App\Models\ScanLog;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ScanLogList extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $result = '';

    #[Url(history: true)]
    public string $dateFrom = '';

    #[Url(history: true)]
    public string $dateTo = '';

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingResult(): void  { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo(): void  { $this->resetPage(); }

    public function render()
    {
        $logs = ScanLog::query()
            ->with('staff')
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where(function ($q2) use ($term) {
                    $q2->whereHas('staff', fn ($s) =>
                            $s->where('full_name', 'like', $term)
                              ->orWhere('staff_id', 'like', $term)
                        )
                        ->orWhere('ip_address', 'like', $term);
                });
            })
            ->when($this->result, fn ($q) => $q->where('result', $this->result))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('scanned_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn ($q) => $q->whereDate('scanned_at', '<=', $this->dateTo))
            ->orderByDesc('scanned_at')
            ->paginate(25);

        return view('livewire.admin.scan-log-list', compact('logs'));
    }
}
