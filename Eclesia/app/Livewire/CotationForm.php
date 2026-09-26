<?php

namespace App\Livewire;

use App\Models\Nu;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CotationForm extends Component
{
    public Nu $nu;

    public ?string $noteOral = null;

    public ?string $noteEcrite = null;

    public function mount(Nu $nu): void
    {
        $this->nu = $nu;
        $this->noteOral = $nu->note_oral;
        $this->noteEcrite = $nu->note_ecrite;
    }

    public function enregistrer(): void
    {
        $this->validate([
            'noteOral' => ['required', 'numeric', 'min:0', 'max:20'],
            'noteEcrite' => ['required', 'numeric', 'min:0', 'max:20'],
        ]);

        $this->nu->update([
            'note_oral' => $this->noteOral,
            'note_ecrite' => $this->noteEcrite,
            'pourcentage' => $this->pourcentage(),
        ]);
    }

    /**
     * Pourcentage calculé automatiquement : (oral + écrit) × 100 / 40.
     */
    public function pourcentage(): float
    {
        $oral = (float) ($this->noteOral ?? 0);
        $ecrite = (float) ($this->noteEcrite ?? 0);

        return round(($oral + $ecrite) * 100 / 40, 1);
    }

    public function render(): View
    {
        return view('livewire.cotation-form', [
            'fidele' => $this->nu->fidele,
        ]);
    }
}
