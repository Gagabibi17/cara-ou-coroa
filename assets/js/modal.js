/**
 * Módulo de Modais Customizados
 * Regra: "sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL"
 * Substitui integralmente alert() e confirm() do navegador por modais acessíveis e elegantes.
 */

const AppModal = (function () {
    let activeModalElement = null;

    /**
     * Fecha qualquer modal dinâmico aberto
     */
    function closeCurrent() {
        if (!activeModalElement) return;

        const backdrop = activeModalElement;
        const card = backdrop.querySelector('.modal-card');

        if (card) {
            card.style.transition = 'all 0.2s ease-in';
            card.style.transform = 'scale(0.92) translateY(10px)';
            card.style.opacity = '0';
        }

        backdrop.style.transition = 'opacity 0.2s ease-in';
        backdrop.style.opacity = '0';

        setTimeout(() => {
            if (backdrop.parentNode) {
                backdrop.parentNode.removeChild(backdrop);
            }
            document.body.classList.remove('overflow-hidden');
            activeModalElement = null;
        }, 220);
    }

    /**
     * Exibe um modal no formato ALERT
     */
    function alert(options) {
        const opts = typeof options === 'string' ? { message: options } : options;
        const title = opts.title || 'Aviso';
        const message = opts.message || '';
        const btnText = opts.buttonText || 'Entendi';
        const onClose = opts.onClose || (() => {});

        closeCurrent();

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm modal-backdrop-show';

        modal.innerHTML = `
            <div class="modal-card bg-slate-900 border border-slate-700 w-full max-w-md rounded-2xl p-6 shadow-2xl modal-content-show text-white">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-100">${title}</h3>
                </div>
                <div class="text-sm text-slate-300 leading-relaxed mb-6">
                    ${message}
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn-confirm px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-semibold rounded-xl transition shadow-lg shadow-amber-500/20 active:scale-95">
                        ${btnText}
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.classList.add('overflow-hidden');
        activeModalElement = modal;

        const btn = modal.querySelector('.btn-confirm');
        btn.focus();
        btn.addEventListener('click', () => {
            closeCurrent();
            onClose();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeCurrent();
                onClose();
            }
        });
    }

    /**
     * Exibe um modal no formato CONFIRM
     */
    function confirm(options) {
        return new Promise((resolve) => {
            const title = options.title || 'Confirmação';
            const message = options.message || 'Tem certeza que deseja prosseguir?';
            const confirmText = options.confirmText || 'Confirmar';
            const cancelText = options.cancelText || 'Cancelar';
            const isDanger = options.danger !== false;

            closeCurrent();

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm modal-backdrop-show';

            const confirmBtnClass = isDanger 
                ? 'bg-rose-600 hover:bg-rose-500 text-white shadow-rose-900/40' 
                : 'bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-amber-500/20';

            modal.innerHTML = `
                <div class="modal-card bg-slate-900 border border-slate-700 w-full max-w-md rounded-2xl p-6 shadow-2xl modal-content-show text-white">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-xl ${isDanger ? 'bg-rose-500/20 text-rose-400' : 'bg-amber-500/20 text-amber-400'} flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-100">${title}</h3>
                    </div>
                    <div class="text-sm text-slate-300 leading-relaxed mb-6">
                        ${message}
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="btn-cancel px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition text-sm font-medium">
                            ${cancelText}
                        </button>
                        <button type="button" class="btn-confirm px-5 py-2 ${confirmBtnClass} rounded-xl font-semibold text-sm transition shadow-lg active:scale-95">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            document.body.classList.add('overflow-hidden');
            activeModalElement = modal;

            const confirmBtn = modal.querySelector('.btn-confirm');
            const cancelBtn = modal.querySelector('.btn-cancel');

            confirmBtn.addEventListener('click', () => {
                closeCurrent();
                if (typeof options.onConfirm === 'function') options.onConfirm();
                resolve(true);
            });

            cancelBtn.addEventListener('click', () => {
                closeCurrent();
                if (typeof options.onCancel === 'function') options.onCancel();
                resolve(false);
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeCurrent();
                    if (typeof options.onCancel === 'function') options.onCancel();
                    resolve(false);
                }
            });
        });
    }

    // Suporte ao fechamento via teclado tecla ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && activeModalElement) {
            closeCurrent();
        }
    });

    return {
        alert,
        confirm,
        closeCurrent
    };
})();
