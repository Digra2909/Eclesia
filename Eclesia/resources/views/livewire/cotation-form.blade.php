<tr wire:key="cotation-{{ $nu->id }}">
    <td>{{ $nu->code_nu }}</td>
    <td>{{ trim(implode(' ', array_filter([$fidele?->prenom, $fidele?->nom, $fidele?->postnom]))) }}</td>
    <td class="text-end">
        <div class="d-inline-flex gap-1">
            <input type="number" class="form-control form-control-sm text-danger" style="width: 70px;" min="0" max="20" step="any"
                   wire:model.live.debounce.500ms="noteOral" placeholder="0">
            <input type="number" class="form-control form-control-sm text-primary" style="width: 70px;" min="0" max="20" step="any"
                   wire:model.live.debounce.500ms="noteEcrite" placeholder="0">
        </div>
    </td>
    <td class="text-end">
        <span class="badge bg-success-subtle text-success fw-semibold">{{ $this->pourcentage() }}%</span>
    </td>
    <td class="text-end">
        <button type="button" class="btn btn-sm btn-primary" wire:click="enregistrer">
            <i class="bi bi-check2-circle me-1"></i>Enregistrer
        </button>
    </td>
</tr>