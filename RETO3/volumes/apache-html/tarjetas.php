<?php
$page_title = 'Txiribitones - Mis tarjetas';
$active_page = 'tarjetas';
$page_header = 'Gestionar mis tarjetas';
$page_description = 'Revisa y gestiona tus tarjetas de crédito y débito';

ob_start();
?>

<link rel="stylesheet" href="style.css">

<div class="credit-cards" role="tablist" aria-label="Mis tarjetas">
    <div class="credit-card active" data-card="1" role="tab" aria-selected="true" tabindex="0">
        <div class="top-row">
            <div class="chip" aria-hidden="true"></div>
            <div class="bank">Banco Azul</div>
        </div>
        <div>
            <div class="number">**** **** **** 1234</div>
        </div>
        <div class="meta">
            <div class="holder">Usuario Titular</div>
            <div class="expiry">08/27</div>
        </div>
    </div>

    <div class="credit-card" data-card="2" role="tab" aria-selected="false" tabindex="0">
        <div class="top-row">
            <div class="chip" aria-hidden="true"></div>
            <div class="bank">Banco Verde</div>
        </div>
        <div>
            <div class="number">**** **** **** 9876</div>
        </div>
        <div class="meta">
            <div class="holder">Otra Cuenta</div>
            <div class="expiry">11/29</div>
        </div>
    </div>
</div>

<script>
(function(){
    const cards = Array.from(document.querySelectorAll('.credit-card'));
    function activate(target) {
        cards.forEach(c => {
            const isTarget = c === target;
            c.classList.toggle('active', isTarget);
            c.setAttribute('aria-selected', isTarget ? 'true' : 'false');
        });
    }
    cards.forEach(card => {
        card.addEventListener('click', () => {
            if (!card.classList.contains('active')) activate(card);
        });
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (!card.classList.contains('active')) activate(card);
            }
            // Soporta flechas para cambiar tarjeta
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                const idx = cards.indexOf(card);
                const next = cards[(idx + 1) % cards.length];
                next.focus();
                activate(next);
            }
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                const idx = cards.indexOf(card);
                const prev = cards[(idx - 1 + cards.length) % cards.length];
                prev.focus();
                activate(prev);
            }
        });
    });
})();
</script>
<?php
$page_content = ob_get_clean();

include 'base.php';
?>