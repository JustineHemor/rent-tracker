<div>
    <flux:card class="space-y-6 max-w-5xl mx-auto">
        <div class="space-y-6">
            <flux:input wire:model.blur="month_year" label="For Month" type="month" />
            <flux:input wire:model.blur="posted_date" label="Posted Date" type="date" />
            <flux:input wire:model.blur="amount" label="Amount" type="number" />
            <flux:input wire:model.blur="image_proof" label="Image Proof" type="file" />
            <flux:textarea wire:model.blur="remarks" label="Remarks" />
        </div>

        <div class="space-y-2">
            <flux:button wire:click="create" variant="primary">Submit</flux:button>
        </div>
    </flux:card>
</div>
