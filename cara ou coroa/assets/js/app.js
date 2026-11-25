/**
 * Aplicação Principal: Cara ou Coroa
 * Orquestração do Giro da Moeda, Animação 3D, Atualizações e Fallbacks
 */

document.addEventListener('DOMContentLoaded', () => {
    // Elementos DOM
    const btnGirar = document.getElementById('btn-girar');
    const coinObject = document.getElementById('coin-object');
    const coinShadow = document.getElementById('coin-shadow');
    const resultBanner = document.getElementById('result-banner');
    const resultText = document.getElementById('result-text');
    const resultBadge = document.getElementById('result-badge');

    // Estatísticas DOM
    const statTotal = document.getElementById('stat-total');
    const statCara = document.getElementById('stat-cara');
    const statCoroa = document.getElementById('stat-coroa');
    const statPctCara = document.getElementById('stat-pct-cara');
    const statPctCoroa = document.getElementById('stat-pct-coroa');
    const barCara = document.getElementById('bar-cara');
    const barCoroa = document.getElementById('bar-coroa');
    const streakBadge = document.getElementById('streak-badge');

    // Histórico DOM
    const historicoLista = document.getElementById('historico-lista');
    const historicoVazio = document.getElementById('historico-vazio');
    const btnLimparHistorico = document.getElementById('btn-limpar-historico');

    // Controles adicionais
    const btnToggleSound = document.getElementById('btn-toggle-sound');
    const soundIconOn = document.getElementById('sound-icon-on');
    const soundIconOff = document.getElementById('sound-icon-off');
    const btnInfo = document.getElementById('btn-info');
    const choiceButtons = document.querySelectorAll('.choice-btn');

    let isSpinning = false;
    let escolhaAtual = null; // 'cara', 'coroa' ou null

    // Atualiza estado inicial do som na interface
    atualizarIconeSom(AppAudio.isEnabled());

    // ----------------------------------------------------
    // Seleção de Aposta Opcional
    // ----------------------------------------------------
    choiceButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (isSpinning) return;
            const choice = btn.dataset.choice;

            choiceButtons.forEach(b => {
                b.classList.remove('bg-amber-500', 'text-slate-950', 'shadow-md', 'shadow-amber-500/20');
                b.classList.add('bg-slate-800/80', 'text-slate-300');
            });

            if (escolhaAtual === choice) {
                // Deselecionar
                escolhaAtual = null;
            } else {
                escolhaAtual = choice;
                btn.classList.remove('bg-slate-800/80', 'text-slate-300');
                btn.classList.add('bg-amber-500', 'text-slate-950', 'shadow-md', 'shadow-amber-500/20');
            }
        });
    });

    // ----------------------------------------------------
    // Ação Principal: GIRAR A MOEDA (Core Requirement)
    // ----------------------------------------------------
    btnGirar.addEventListener('click', executarGiro);

    // Suporte a tecla Espaço para girar rapidamente
    document.addEventListener('keydown', (e) => {
        if (e.code === 'Space' && e.target === document.body && !isSpinning) {
            e.preventDefault();
            executarGiro();
        }
    });

    async function executarGiro() {
        if (isSpinning) {
            AppToast.warning('A moeda já está no ar! Aguarde a conclusão do giro.');
            return;
        }

        isSpinning = true;
        btnGirar.disabled = true;
        btnGirar.classList.add('cursor-wait');

        // Limpa estado anterior de animação e resultado
        coinObject.classList.remove('animating-heads', 'animating-tails');
        coinShadow.classList.remove('shadow-animating');
        resultBanner.classList.add('hidden');
        resultBanner.classList.remove('animate-result-pop');

        // Dispara efeito sonoro do arremesso
        AppAudio.playFlip();

        try {
            // Chamada assíncrona para o backend seguro em PHP
            const response = await fetch('backend/girar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ escolha: escolhaAtual })
            });

            if (!response.ok) {
                throw new Error(`Erro na resposta do servidor (${response.status})`);
            }

            const data = await response.json();

            if (!data.sucesso) {
                throw new Error(data.mensagem || 'Erro ao processar o sorteio.');
            }

            // Força reflow para reiniciar animação CSS com precisão
            void coinObject.offsetWidth;

            const resultado = data.resultado; // 'cara' ou 'coroa'
            const animClass = (resultado === 'cara') ? 'animating-heads' : 'animating-tails';

            // Aplica as classes de rotação 3D e sombra
            coinObject.classList.add(animClass);
            coinShadow.classList.add('shadow-animating');

            // Aguarda o tempo exato da animação física (2.2 segundos)
            setTimeout(() => {
                finalizarGiro(data);
            }, 2200);

        } catch (error) {
            console.error('Falha ao girar a moeda:', error);
            AppToast.error(`Não foi possível girar a moeda: ${error.message}`);
            isSpinning = false;
            btnGirar.disabled = false;
            btnGirar.classList.remove('cursor-wait');
        }
    }

    /**
     * Finaliza o giro, toca som de pouso e atualiza os componentes da tela
     */
    function finalizarGiro(data) {
        AppAudio.playLand();

        isSpinning = false;
        btnGirar.disabled = false;
        btnGirar.classList.remove('cursor-wait');

        const resultado = data.resultado;
        const ehCara = resultado === 'cara';

        // Exibe o banner chamativo de resultado
        resultBanner.classList.remove('hidden');
        resultBanner.classList.add('animate-result-pop');

        resultText.textContent = ehCara ? 'DEU CARA!' : 'DEU COROA!';
        resultText.className = ehCara 
            ? 'text-2xl sm:text-3xl font-extrabold text-amber-300 drop-shadow' 
            : 'text-2xl sm:text-3xl font-extrabold text-sky-300 drop-shadow';

        // Se o usuário fez uma aposta prévia
        if (data.escolha_usuario) {
            resultBadge.classList.remove('hidden');
            if (data.acertou) {
                resultBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                resultBadge.innerHTML = '🎉 Você acertou a sua aposta!';
                AppToast.success(`Parabéns! Deu ${resultado.toUpperCase()} e você acertou!`);
            } else {
                resultBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30';
                resultBadge.innerHTML = '😅 Não foi dessa vez, tente de novo!';
                AppToast.info(`Deu ${resultado.toUpperCase()}. Mais sorte na próxima rodada!`);
            }
        } else {
            resultBadge.classList.add('hidden');
            AppToast.success(`Resultado: ${resultado.toUpperCase()}!`, 2500);
        }

        // Atualiza estatísticas na tela
        if (data.stats) {
            atualizarEstatisticasInterface(data.stats);
        }

        // Adiciona lançamento ao topo do histórico
        adicionarAoHistorico({
            id: data.id || Date.now(),
            resultado: resultado,
            escolha_usuario: data.escolha_usuario,
            acertou: data.acertou,
            data_formatada: data.data_hora
        });
    }

    /**
     * Atualiza os cards e barras de progresso de estatísticas
     */
    function atualizarEstatisticasInterface(stats) {
        if (!stats) return;

        statTotal.textContent = stats.total ?? 0;
        statCara.textContent = stats.cara ?? 0;
        statCoroa.textContent = stats.coroa ?? 0;

        const pctCara = stats.pct_cara ?? 0;
        const pctCoroa = stats.pct_coroa ?? 0;

        statPctCara.textContent = `${pctCara}%`;
        statPctCoroa.textContent = `${pctCoroa}%`;

        barCara.style.width = `${pctCara}%`;
        barCoroa.style.width = `${pctCoroa}%`;

        // Sequência ativa (se presente)
        if (stats.sequencia && stats.sequencia.vezes > 1) {
            streakBadge.classList.remove('hidden');
            streakBadge.textContent = `🔥 Sequência: ${stats.sequencia.vezes}x ${stats.sequencia.lado.toUpperCase()}`;
        } else {
            streakBadge.classList.add('hidden');
        }
    }

    /**
     * Insere dinamicamente uma nova linha no histórico visual
     */
    function adicionarAoHistorico(item) {
        if (historicoVazio) {
            historicoVazio.classList.add('hidden');
        }

        const ehCara = item.resultado === 'cara';
        const card = document.createElement('div');
        card.className = 'flex items-center justify-between p-3.5 bg-slate-800/60 border border-slate-700/60 rounded-xl hover:bg-slate-800 transition duration-150 animate-result-pop';

        let badgeAposta = '';
        if (item.escolha_usuario) {
            badgeAposta = item.acertou
                ? `<span class="text-xs px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-400 font-medium">Acertou</span>`
                : `<span class="text-xs px-2 py-0.5 rounded-md bg-rose-500/20 text-rose-400 font-medium">Errou</span>`;
        }

        card.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shadow-md ${ehCara ? 'bg-amber-500 text-slate-950' : 'bg-sky-500 text-slate-950'}">
                    ${ehCara ? 'C' : 'K'}
                </div>
                <div>
                    <div class="font-bold text-sm text-slate-200 capitalize flex items-center space-x-2">
                        <span>${item.resultado}</span>
                        ${badgeAposta}
                    </div>
                    <div class="text-xs text-slate-400">${item.data_formatada || 'Agora'}</div>
                </div>
            </div>
            <div class="text-xs font-semibold ${ehCara ? 'text-amber-400' : 'text-sky-400'}">
                #${item.id || '1'}
            </div>
        `;

        if (historicoLista.firstChild) {
            historicoLista.insertBefore(card, historicoLista.firstChild);
        } else {
            historicoLista.appendChild(card);
        }

        // Mantém visualmente no máximo 30 itens na lista da interface
        while (historicoLista.children.length > 30) {
            historicoLista.removeChild(historicoLista.lastChild);
        }
    }

    // ----------------------------------------------------
    // Limpar Histórico com MODAL Customizado (Regra de Negócio)
    // ----------------------------------------------------
    if (btnLimparHistorico) {
        btnLimparHistorico.addEventListener('click', async () => {
            const confirmou = await AppModal.confirm({
                title: 'Limpar Histórico de Lançamentos',
                message: 'Tem certeza que deseja apagar todos os registros do histórico e zerar as estatísticas? Esta ação não pode ser desfeita.',
                confirmText: 'Sim, limpar tudo',
                cancelText: 'Cancelar',
                danger: true
            });

            if (!confirmou) return;

            try {
                const response = await fetch('backend/limpar_historico.php', {
                    method: 'POST'
                });
                const res = await response.json();

                if (res.sucesso) {
                    historicoLista.innerHTML = '';
                    if (historicoVazio) historicoVazio.classList.remove('hidden');

                    atualizarEstatisticasInterface({
                        total: 0,
                        cara: 0,
                        coroa: 0,
                        pct_cara: 0,
                        pct_coroa: 0
                    });

                    resultBanner.classList.add('hidden');
                    AppToast.success(res.mensagem || 'Histórico resetado com sucesso!');
                } else {
                    AppToast.error(res.mensagem || 'Erro ao limpar histórico.');
                }
            } catch (err) {
                AppToast.error('Erro de conexão ao tentar limpar histórico: ' + err.message);
            }
        });
    }

    // ----------------------------------------------------
    // Alternância do Efeito Sonoro
    // ----------------------------------------------------
    if (btnToggleSound) {
        btnToggleSound.addEventListener('click', () => {
            const enabled = AppAudio.toggle();
            atualizarIconeSom(enabled);
            AppToast.info(enabled ? 'Som ativado.' : 'Som desativado.', 2000);
        });
    }

    function atualizarIconeSom(enabled) {
        if (enabled) {
            soundIconOn.classList.remove('hidden');
            soundIconOff.classList.add('hidden');
        } else {
            soundIconOn.classList.add('hidden');
            soundIconOff.classList.remove('hidden');
        }
    }

    // ----------------------------------------------------
    // Modal de Informações / Sobre o Sistema
    // ----------------------------------------------------
    if (btnInfo) {
        btnInfo.addEventListener('click', () => {
            AppModal.alert({
                title: 'Sobre o Cara ou Coroa',
                message: `
                    <p class="mb-3">Este projeto foi construído seguindo rigorosamente as especificações técnicas:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-slate-300 text-xs sm:text-sm">
                        <li><strong>Backend:</strong> PHP com sessões nativas e estrutura desacoplada.</li>
                        <li><strong>Banco de Dados:</strong> MySQL conectado via <code>PDO</code> com Prepared Statements.</li>
                        <li><strong>Frontend:</strong> HTML5, Tailwind CSS com design responsivo Mobile-First e animação 3D pura em CSS.</li>
                        <li><strong>Sons:</strong> Síntese de áudio realista com Web Audio API.</li>
                        <li><strong>Modais e Toasts:</strong> Caixa de diálogo e notificações customizadas que substituem as caixas nativas.</li>
                    </ul>
                `,
                buttonText: 'Fechar'
            });
        });
    }

    // ----------------------------------------------------
    // Carregamento Inicial de Dados (Histórico e Stats)
    // ----------------------------------------------------
    carregarDadosIniciais();

    async function carregarDadosIniciais() {
        try {
            // Carrega Estatísticas
            const resStats = await fetch('backend/estatisticas.php');
            if (resStats.ok) {
                const dataStats = await resStats.json();
                if (dataStats.sucesso) {
                    atualizarEstatisticasInterface(dataStats);
                }
            }

            // Carrega Histórico Recente
            const resHist = await fetch('backend/historico.php?limite=20');
            if (resHist.ok) {
                const dataHist = await resHist.json();
                if (dataHist.sucesso && dataHist.dados && dataHist.dados.length > 0) {
                    if (historicoVazio) historicoVazio.classList.add('hidden');
                    dataHist.dados.forEach(item => {
                        adicionarAoHistorico(item);
                    });
                }
            }
        } catch (err) {
            console.warn('Falha silenciosa no carregamento inicial (normal em primeiro acesso):', err);
        }
    }
});
