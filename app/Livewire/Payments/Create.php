<?php

namespace App\Livewire\Payments;

use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Validate(['required', 'date', 'date_format:Y-m'])]
    public string $month_year = '';
    #[Validate(['required', 'date', 'date_format:Y-m-d'])]
    public string $posted_date = '';
    #[Validate(['required', 'numeric'])]
    public string $amount = '';
    #[Validate(['string', 'max:255'])]
    public string $remarks = '';
    #[Validate(['image', 'nullable', 'max:1024'])]
    public mixed $image_proof = null;

    public function render(): View
    {
        return view('livewire.payments.create');
    }

    public function create(): void
    {
        $this->validate();

        if (filled($this->image_proof)) {
            $this->image_proof->store(path: 'payment_proofs');
        }

        $month_year = explode('-', $this->month_year);
        $year = $month_year[0];
        $month = $month_year[1];

        auth()->user()->payments()->create([
            'for_year' => $year,
            'for_month' => $month,
            'amount' => $this->amount,
            'image_proof' => $this->image_proof?->hashName(),
            'posted_at' => $this->posted_date,
            'remarks' => $this->remarks,
        ]);

        if ($this->image_proof instanceof TemporaryUploadedFile) {
            $this->image_proof->delete();
        }

        $this->reset();

        Flux::toast(text: 'Payment created successfully!', variant: 'success');

        $this->redirectRoute(name: 'dashboard', navigate: true);
    }
}
