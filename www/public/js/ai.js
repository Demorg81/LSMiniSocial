document.addEventListener('DOMContentLoaded', function () {
    const improveBtn      = document.getElementById('improveBtn');
    const postContent     = document.getElementById('postContent');
    const aiSpinner       = document.getElementById('aiSpinner');
    const aiSuggestion    = document.getElementById('aiSuggestion');
    const aiSuggestionText = document.getElementById('aiSuggestionText');
    const acceptBtn       = document.getElementById('acceptBtn');
    const rejectBtn       = document.getElementById('rejectBtn');

    if (!improveBtn) return;

    let suggestedText = '';

    improveBtn.addEventListener('click', async function () {
        const text = postContent.value.trim();

        if (!text) {
            alert('Write something first before improving it.');
            return;
        }

        improveBtn.disabled = true;
        aiSpinner.classList.remove('d-none');
        aiSuggestion.classList.add('d-none');

        try {
            const response = await fetch('/ai/improve', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ text })
            });

            const data = await response.json();

            if (data.improved) {
                suggestedText = data.improved;
                aiSuggestionText.textContent = suggestedText;
                aiSuggestion.classList.remove('d-none');
            } else {
                alert('Could not get a suggestion. Try again.');
            }
        } catch (err) {
            alert('Error contacting AI service.');
        } finally {
            improveBtn.disabled = false;
            aiSpinner.classList.add('d-none');
        }
    });

    acceptBtn.addEventListener('click', function () {
        postContent.value = suggestedText;
        aiSuggestion.classList.add('d-none');
    });

    rejectBtn.addEventListener('click', function () {
        aiSuggestion.classList.add('d-none');
    });
});