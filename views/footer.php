<?php
/**
 * View: Rodapé e Inclusão de Scripts
 * Fecha a estrutura da página e inclui os módulos JavaScript necessários
 */
?>
    </main>

    <!-- Rodapé -->
    <footer class="w-full border-t border-slate-800/80 bg-slate-950/80 py-6 mt-12 text-center text-xs text-slate-500">
        <div class="max-w-4xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-slate-400">Cara ou Coroa</span>
                <span>•</span>
                <span>PHP + PDO MySQL + Tailwind CSS</span>
            </div>
            <div>
                <span>Desenvolvido de acordo com <a href="requisitos.MD" class="text-amber-400 hover:underline">requisitos.MD</a></span>
            </div>
        </div>
    </footer>

    <!-- Container de Toasts e Modais -->
    <?php require_once __DIR__ . '/toasts.php'; ?>
    <?php require_once __DIR__ . '/modais.php'; ?>

    <!-- Módulos JavaScript Desacoplados -->
    <script src="assets/js/audio.js"></script>
    <script src="assets/js/toast.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/app.js"></script>

    <?php if (isset($_SESSION['db_status']) && !$_SESSION['db_status']['conectado']): ?>
        <script>
            // Notificação informativa via Toast caso o MySQL não esteja em execução no momento
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    AppToast.info("Operando em modo de Sessão Nativa. Para persistir no MySQL, inicie o serviço MySQL/XAMPP.", 6000);
                }, 800);
            });
        </script>
    <?php endif; ?>

</body>
</html>
