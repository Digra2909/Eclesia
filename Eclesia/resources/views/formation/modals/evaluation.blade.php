<x-ui.modal id="modal-eval-cotation" title="Évaluation des nouvelles unités (NU)" size="xl">
    <div class="alert alert-info small d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-info-circle"></i>
        <span>Notes sur l'orale et l'écrite (sur 20).</span>
    </div>

    @forelse ($nuCotations as $nu)
        <form action="{{ route('nus.update', ['nu' => $nu['id']]) }}" method="POST" class="card border-0 shadow-sm mb-2">
            @csrf
            @method('PUT')
            <div class="card-body row g-2 align-items-center">
                <div class="col-md-4">
                    <strong class="d-block">NU {{ $nu['code_nu'] }}</strong>
                    <span class="text-muted small">{{ $nu['fidele'] }}</span>
                </div>
                <div class="col-md-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-mic"></i></span>
                        <input type="number" name="note_oral" class="form-control" min="0" max="20" step="0.25" value="{{ $nu['note_oral'] }}" placeholder="Orale">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-pencil"></i></span>
                        <input type="number" name="note_ecrite" class="form-control" min="0" max="20" step="0.25" value="{{ $nu['note_ecrite'] }}" placeholder="Écrite">
                    </div>
                </div>
                <div class="col-md-2 text-muted small">
                    Pourcentage : <strong>{{ $nu['pourcentage'] ?? '—' }}</strong>
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                </div>
            </div>
        </form>
    @empty
        <div class="empty-state">
            <i class="bi bi-clipboard-check"></i>
            <p class="mb-0 mt-2">Aucune nouvelle unité à évaluer pour le moment.</p>
        </div>
    @endforelse

    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>