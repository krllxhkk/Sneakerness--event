<div id="wijzigenVerkoperModal" class="wijzigen-modal">
    <div class="wijzigen-modal-inhoud">

        <div class="wijzigen-modal-kop">
            <div>
                <p>Organisator Dashboard</p>
                <h2>Verkoper wijzigen</h2>
            </div>

            <button type="button" class="wijzigen-sluiten"
                    id="closeWijzigenModal">
                &times;
            </button>
        </div>

        <form id="wijzigenVerkoperForm" method="POST">
            @csrf
            @method('PUT')

            <div class="wijzigen-form-groep">
                <label for="WijzigenNaam">Naam *</label>
                <input type="text" id="WijzigenNaam"
                       name="Naam" maxlength="100" required>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenVerkooptSoort">Verkoopt soort *</label>
                <input type="text" id="WijzigenVerkooptSoort"
                       name="VerkooptSoort" maxlength="100" required>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenStandType">Stand type *</label>
                <select id="WijzigenStandType" name="StandType" required>
                    <option value="A">A</option>
                    <option value="AA">AA</option>
                    <option value="AA+">AA+</option>
                </select>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenDagen">Dagen *</label>
                <select id="WijzigenDagen" name="Dagen" required>
                    <option value="Zaterdag">Zaterdag</option>
                    <option value="Zondag">Zondag</option>
                    <option value="Zaterdag en zondag">Zaterdag en zondag</option>
                </select>
            </div>

            <div class="wijzigen-form-groep">
                <label for="WijzigenSpecialeStatus">Speciale status</label>
                <select id="WijzigenSpecialeStatus" name="SpecialeStatus">
                    <option value="">Geen</option>
                    <option value="Partner">Partner</option>
                    <option value="Premium">Premium</option>
                </select>
            </div>

            <div class="wijzigen-modal-knoppen">
                <button type="button" id="cancelWijzigenModal">
                    ANNULEREN
                </button>

                <button type="submit">
                    WIJZIGINGEN OPSLAAN
                </button>
            </div>
        </form>

    </div>
</div>

<script>
// Open het formulier om een verkoper te wijzigen.
function openWijzigenModal(verkoper) {
    console.log('Verkoper:', verkoper);

    const modal = document.getElementById('wijzigenVerkoperModal');
    const form = document.getElementById('wijzigenVerkoperForm');

    if (!modal || !form) {
        alert('Modal of formulier niet gevonden!');
        return;
    }

    form.action = `/verkopers/${verkoper.Id}`;

    document.getElementById('WijzigenNaam').value = verkoper.Naam ?? '';
    document.getElementById('WijzigenVerkooptSoort').value = verkoper.VerkooptSoort ?? '';
    document.getElementById('WijzigenStandType').value = verkoper.StandType ?? '';
    document.getElementById('WijzigenDagen').value = verkoper.Dagen ?? '';
    document.getElementById('WijzigenSpecialeStatus').value =
        verkoper.SpecialeStatus ?? '';

   modal.classList.add('actief');

}
// Sluit het formulier.
function closeWijzigenModal() {
    const modal = document.getElementById('wijzigenVerkoperModal');

    modal.classList.remove('actief');
    modal.style.display = 'none';
}

document.getElementById('closeWijzigenModal').addEventListener('click', closeWijzigenModal);
document.getElementById('cancelWijzigenModal').addEventListener('click', closeWijzigenModal);

document.getElementById('wijzigenVerkoperModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeWijzigenModal();
    }
});
</script>
