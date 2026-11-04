
<div id="wijzigenContactpersoonModal" class="wijzigen-modal">
    <div class="wijzigen-modal-inhoud">

        <div class="wijzigen-modal-kop">
            <div>
                <p>Organisator Dashboard</p>
                <h2>Contactpersoon wijzigen</h2>
            </div>

            <button type="button" class="wijzigen-sluiten"
                    id="closeWijzigenContactpersoonModal">
                &times;
            </button>
        </div>

        <form id="wijzigenContactpersoonForm" method="POST">
            @csrf
            @method('PUT')

            <div class="wijzigen-form-groep">
                <label for="WijzigenContactNaam">Naam *</label>
                <input type="text" id="WijzigenContactNaam"
                       name="Naam" maxlength="100" required>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenContactEmail">E-mailadres *</label>
                <input type="email" id="WijzigenContactEmail"
                       name="E-mailadres" maxlength="150" required>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenContactTelefoon">Telefoonnummer *</label>
                <input type="text" id="WijzigenContactTelefoon"
                       name="Telefoonnummer" maxlength="20" required>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenContactVerkoper">Verkoper *</label>
                <select id="WijzigenContactVerkoper"
                        name="VerkoperId" required>
                    @foreach($verkopers as $verkoper)
                        <option value="{{ $verkoper->Id }}">
                            {{ $verkoper->Naam }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="wijzigen-modal-knoppen">
                <button type="button" id="cancelWijzigenContactpersoonModal"
                        class="wijzigen-annuleren">
                    ANNULEREN
                </button>

                <button type="submit" class="wijzigen-opslaan">
                    WIJZIGINGEN OPSLAAN
                </button>
            </div>
        </form>

    </div>
</div>

<script>
// Open het formulier om een contactpersoon te wijzigen.
function openWijzigenContactpersoonModal(button) {
    const modal = document.getElementById('wijzigenContactpersoonModal');
    const form = document.getElementById('wijzigenContactpersoonForm');

    const id = button.dataset.id;

    form.action = `/contactpersonen/${id}`;

    document.getElementById('WijzigenContactNaam').value =
        button.dataset.naam;

    document.getElementById('WijzigenContactEmail').value =
        button.dataset.email;

    document.getElementById('WijzigenContactTelefoon').value =
        button.dataset.telefoon;

    document.getElementById('WijzigenContactVerkoper').value =
        button.dataset.verkoper;

    modal.style.display = 'flex';
    modal.classList.add('actief');
}

// Sluit het formulier.
function closeWijzigenContactpersoonModal() {
    const modal = document.getElementById('wijzigenContactpersoonModal');

    modal.classList.remove('actief');
    modal.style.display = 'none';
}

document.getElementById('closeWijzigenContactpersoonModal')
    .addEventListener('click', closeWijzigenContactpersoonModal);

document.getElementById('cancelWijzigenContactpersoonModal')
    .addEventListener('click', closeWijzigenContactpersoonModal);

document.getElementById('wijzigenContactpersoonModal')
    .addEventListener('click', function(event) {
        if (event.target === this) {
            closeWijzigenContactpersoonModal();
        }
    });
</script>
