/* ════════════════════════════════════════════════════
   pageant.js — Minimal vanilla JS
   Only handles UI interactions that can't be done
   in pure HTML/CSS (score dot picker + live total).
   All page navigation is now handled by Laravel routes.
════════════════════════════════════════════════════ */

/**
 * Called when a judge clicks a score dot on the scoring panel.
 *
 * @param {string} criteriaKey - e.g. 'beauty', 'talent'
 * @param {number} value       - the score value e.g. 90
 * @param {HTMLElement} btn    - the clicked button element
 */
function pickScore(criteriaKey, value, btn) {
    // 1. Update the hidden input so the form submits the new value
    const input = document.getElementById('input-' + criteriaKey);
    if (input) input.value = value;

    // 2. Update the big score display number
    const display = document.getElementById('disp-' + criteriaKey);
    if (display) display.innerHTML = value + ' <span>/ 100</span>';

    // 3. Highlight only the clicked dot, remove others in same card
    const card = btn.closest('.criteria-card');
    card.querySelectorAll('.score-dot').forEach(d => d.classList.remove('selected'));
    btn.classList.add('selected');

    // 4. Recalculate and show the weighted average
    recalcTotal();
}

/**
 * Recalculates the weighted average from all four criteria inputs
 * and updates the total display.
 * Weights: Beauty 25%, Intelligence 25%, Talent 30%, Q&A 20%
 */
function recalcTotal() {
    const get = id => parseFloat(document.getElementById('input-' + id)?.value || 0);

    const beauty = get('beauty');
    const intel  = get('intelligence');
    const talent = get('talent');
    const qa     = get('qa');

    const weighted = (beauty * 0.25) + (intel * 0.25) + (talent * 0.30) + (qa * 0.20);

    const display = document.getElementById('total-display');
    if (display) display.textContent = weighted.toFixed(1);
}
