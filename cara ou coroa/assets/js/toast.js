/**
 * Módulo de Notificações Toast
 * Regra: "Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)"
 */

const AppToast = (function () {
    const containerId = 'toast-container';

    function getContainer() {
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'fixed top-4 right-4 z-50 flex flex-col space-y-3 max-w-sm w-full pointer-events-none px-4 sm:px-0';
            document.body.appendChild(container);
        }
        return container;
    }

    const configs = {
        success: {
            bg: 'bg-emerald-900/90 border-emerald-500 text-emerald-100',
            bar: 'bg-emerald-400',
            icon: `<svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`
        },
        error: {
            bg: 'bg-rose-900/90 border-rose-500 text-rose-100',
            bar: 'bg-rose-400',
            icon: `<svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
        },
        warning: {
            bg: 'bg-amber-900/90 border-amber-500 text-amber-100',
            bar: 'bg-amber-400',
            icon: `<svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`
        },
        info: {
            bg: 'bg-cyan-900/90 border-cyan-500 text-cyan-100',
            bar: 'bg-cyan-400',
            icon: `<svg class="w-5 h-5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
        }
    };

    /**
     * Exibe um Toast
     * @param {string} message - Texto da mensagem
     * @param {'success'|'error'|'warning'|'info'} type - Categoria do toast
     * @param {number} duration - Duração em milissegundos (padrão: 4000)
     */
    function show(message, type = 'info', duration = 4000) {
        const container = getContainer();
        const cfg = configs[type] || configs.info;

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex flex-col backdrop-blur-md border rounded-xl shadow-xl overflow-hidden toast-enter ${cfg.bg}`;

        toast.innerHTML = `
            <div class="flex items-start p-3.5 space-x-3">
                <div class="mt-0.5">${cfg.icon}</div>
                <div class="flex-1 text-sm font-medium leading-snug">${message}</div>
                <button type="button" class="text-white/60 hover:text-white transition-colors p-0.5" aria-label="Fechar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="h-1 w-full bg-black/20">
                <div class="toast-progress h-full ${cfg.bar}" style="width: 100%; transition: width ${duration}ms linear;"></div>
            </div>
        `;

        const closeBtn = toast.querySelector('button');
        const progressBar = toast.querySelector('.toast-progress');

        const removeToast = () => {
            toast.style.transition = 'all 0.25s ease-out';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px) scale(0.95)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 260);
        };

        closeBtn.addEventListener('click', removeToast);

        container.appendChild(toast);

        // Dispara animação da barra de tempo
        requestAnimationFrame(() => {
            progressBar.style.width = '0%';
        });

        const timer = setTimeout(removeToast, duration);

        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            progressBar.style.transition = 'none';
        });
    }

    return {
        show,
        success: (msg, dur) => show(msg, 'success', dur),
        error: (msg, dur) => show(msg, 'error', dur || 5000),
        warning: (msg, dur) => show(msg, 'warning', dur),
        info: (msg, dur) => show(msg, 'info', dur)
    };
})();
