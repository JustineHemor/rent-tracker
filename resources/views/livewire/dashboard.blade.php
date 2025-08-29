<div>
    <div class="max-w-5xl mx-auto mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:select wire:model.live="year" label="Year" placeholder="Choose year...">
                <flux:select.option>2025</flux:select.option>
                <flux:select.option>2026</flux:select.option>
                <flux:select.option>2027</flux:select.option>
                <flux:select.option>2028</flux:select.option>
                <flux:select.option>2029</flux:select.option>
            </flux:select>

            <flux:select wire:model.live="table" label="Table" placeholder="Choose table...">
                <flux:select.option value="summarized">Summarized</flux:select.option>
                <flux:select.option value="raw">Raw</flux:select.option>
            </flux:select>
        </div>
    </div>
    @if($table === 'raw')
        <flux:card class="space-y-6 max-w-5xl mx-auto">
            <flux:table :paginate="$this->payments()">
                <flux:table.columns>
                    <flux:table.column>Customer</flux:table.column>
                    <flux:table.column sortable :sorted="$sort_by === 'created_at'" :direction="$sort_direction" wire:click="sort('created_at')">Created At</flux:table.column>
                    <flux:table.column sortable :sorted="$sort_by === 'posted_at'" :direction="$sort_direction" wire:click="sort('posted_at')">Posted At</flux:table.column>
                    <flux:table.column sortable :sorted="$sort_by === 'for_month'" :direction="$sort_direction" wire:click="sort('for_month')">For Month</flux:table.column>
                    <flux:table.column sortable :sorted="$sort_by === 'amount'" :direction="$sort_direction" wire:click="sort('amount')">Amount</flux:table.column>
                    <flux:table.column :direction="$sort_direction">Remarks</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->payments() as $payment)
                        <flux:table.row :key="$payment->id">
                            <flux:table.cell class="flex items-center gap-3">
                                {{--                            <flux:avatar size="xs" src="{{ $payment->customer_avatar }}" />--}}

                                {{ $payment->user->name }}
                            </flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap">{{ $payment->created_at }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">{{ $payment->posted_at }}</flux:table.cell>

                            <flux:table.cell class="whitespace-nowrap">{{ \Carbon\Carbon::createFromFormat('m', $payment->for_month)->format('F') }}</flux:table.cell>


                            <flux:table.cell variant="strong">{{ $payment->amount }}</flux:table.cell>
                            <flux:table.cell>{{ $payment->remarks }}</flux:table.cell>

                            <flux:table.cell>
                                <flux:button variant="primary" size="sm" wire:click="download('{{ $payment->image_proof }}')">Download Image</flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card>
    @else
        <flux:card class="space-y-6 max-w-5xl mx-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Month</flux:table.column>
                    @foreach($this->users() as $user)
                        <flux:table.column>{{ $user }}</flux:table.column>
                    @endforeach
                    <flux:table.column>Total</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($this->summarizedPayments() as $payment)
                        <flux:table.row>
                            <flux:table.cell>{{ $payment['month'] }}</flux:table.cell>
                            @foreach($this->users() as $user)
                                <flux:table.cell>{{ number_format($payment[$user] ?? 0) }}</flux:table.cell>
                            @endforeach
                            <flux:table.cell variant="strong">{{ number_format($payment['total']) }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card>
    @endif
</div>
