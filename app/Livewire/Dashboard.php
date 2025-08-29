<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends Component
{
    use WithPagination;

    public $sort_by = 'created_at';
    public $sort_direction = 'desc';

    public ?int $year = null;

    #[Url]
    public string $table = 'summarized';

    public function render(): View
    {
        return view('livewire.dashboard');
    }

    public function mount(): void
    {
        $this->year = Carbon::now()->year;
    }

    #[Computed]
    public function users(): Collection
    {
        return User::query()->pluck('name');
    }

    #[Computed]
    public function summarizedPayments(): array
    {
        $raw_payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->where('for_year', $this->year)
            ->select(
                'for_month',
                'users.name as user_name',
                DB::raw('SUM(amount) as total_paid')
            )
            ->groupBy('for_month', 'users.name')
            ->get();


        $users = $this->users();
        $months = collect(range(1, 12));

        $pivot = [];

        foreach ($months as $month) {
            $row = ['month' => Carbon::create()->month($month)->format('F')];
            $total = 0;

            foreach ($users as $user) {
                $amount = $raw_payments
                    ->firstWhere(fn($r) => $r->for_month == $month && $r->user_name === $user)?->total_paid ?? 0;

                $row[$user] = $amount;
                $total += $amount;
            }

            $row['total'] = $total;
            $pivot[] = $row;
        }

        return $pivot;
    }

    #[Computed]
    public function payments(): LengthAwarePaginator
    {
        return Payment::query()
            ->with(['user'])
            ->tap(fn ($query) => $this->sort_by ? $query->orderBy($this->sort_by, $this->sort_direction) : $query)
            ->paginate(15);
    }

    public function sort(string $column): void
    {
        if ($this->sort_by === $column) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $column;
            $this->sort_direction = 'asc';
        }
    }

    public function download(string $image_proof): ?StreamedResponse
    {
        if (empty($image_proof)) {
            $this->js('alert("No image proof available for this payment.")');
            return null;
        }


        if (!Storage::exists("/payment_proofs/$image_proof")) {
            $this->js('alert("The image proof could not be found.")');
            return null;
        }

        return Storage::download("/payment_proofs/$image_proof", basename($image_proof));
    }
}
