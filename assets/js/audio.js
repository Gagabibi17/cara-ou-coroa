/**
 * Módulo de Efeitos Sonoros Sintetizados (Web Audio API)
 * Gera áudios realistas de moeda sem necessidade de arquivos externos.
 */

const AppAudio = (function () {
    let audioCtx = null;
    let enabled = localStorage.getItem('sound_enabled') !== 'false';

    function getAudioContext() {
        if (!audioCtx) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) {
                audioCtx = new AudioContextClass();
            }
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    /**
     * Toca o som do giro e rotação rápida da moeda no ar
     */
    function playFlip() {
        if (!enabled) return;
        const ctx = getAudioContext();
        if (!ctx) return;

        const now = ctx.currentTime;

        // Frequências metálicas do tilintar da moeda
        const osc1 = ctx.createOscillator();
        const osc2 = ctx.createOscillator();
        const gain = ctx.createGain();

        osc1.type = 'triangle';
        osc1.frequency.setValueAtTime(1400, now);
        osc1.frequency.exponentialRampToValueAtTime(1800, now + 0.15);

        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(2200, now);
        osc2.frequency.exponentialRampToValueAtTime(3200, now + 0.2);

        gain.gain.setValueAtTime(0.2, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);

        osc1.connect(gain);
        osc2.connect(gain);
        gain.connect(ctx.destination);

        osc1.start(now);
        osc2.start(now);
        osc1.stop(now + 0.5);
        osc2.stop(now + 0.5);
    }

    /**
     * Toca o impacto da moeda aterrissando na superfície
     */
    function playLand() {
        if (!enabled) return;
        const ctx = getAudioContext();
        if (!ctx) return;

        const now = ctx.currentTime;

        // Impacto metálico
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(800, now);
        osc.frequency.exponentialRampToValueAtTime(300, now + 0.12);

        gain.gain.setValueAtTime(0.35, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.25);

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.start(now);
        osc.stop(now + 0.25);

        // Segundo tilintar rápido de quique
        setTimeout(() => {
            if (!enabled || !audioCtx) return;
            const t = audioCtx.currentTime;
            const bounceOsc = audioCtx.createOscillator();
            const bounceGain = audioCtx.createGain();
            bounceOsc.type = 'triangle';
            bounceOsc.frequency.setValueAtTime(1200, t);
            bounceGain.gain.setValueAtTime(0.15, t);
            bounceGain.gain.exponentialRampToValueAtTime(0.001, t + 0.15);
            bounceOsc.connect(bounceGain);
            bounceGain.connect(audioCtx.destination);
            bounceOsc.start(t);
            bounceOsc.stop(t + 0.15);
        }, 90);
    }

    /**
     * Alterna o estado de ativação do áudio
     */
    function toggle() {
        enabled = !enabled;
        localStorage.setItem('sound_enabled', enabled);
        return enabled;
    }

    function isEnabled() {
        return enabled;
    }

    return {
        playFlip,
        playLand,
        toggle,
        isEnabled
    };
})();
