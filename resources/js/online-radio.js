class OnlineRadio {
    constructor(root) {
        this.root = root;
        this.config = JSON.parse(root.dataset.onlineRadio || '{}');
        this.storageKey = `online-radio:${this.config.blockId}`;
        this.stations = [];
        this.filteredStations = [];
        this.selectedStationId = this.restoreStationId();

        this.audio = root.querySelector('[data-radio-audio]');
        this.current = root.querySelector('[data-radio-current]');
        this.status = root.querySelector('[data-radio-status]');
        this.search = root.querySelector('[data-radio-search]');
        this.list = root.querySelector('[data-radio-list]');
        this.count = root.querySelector('[data-radio-count]');
        this.previous = root.querySelector('[data-radio-previous]');
        this.next = root.querySelector('[data-radio-next]');
        this.retry = root.querySelector('[data-radio-retry]');

        this.bindEvents();
        this.loadStations();
    }

    bindEvents() {
        this.search?.addEventListener('input', () => this.filterStations());
        this.previous?.addEventListener('click', () => this.moveSelection(-1));
        this.next?.addEventListener('click', () => this.moveSelection(1));
        this.retry?.addEventListener('click', () => this.loadStations());

        this.audio?.addEventListener('playing', () => {
            const station = this.selectedStation();

            if (station) {
                this.setStatus(this.format('listening', { station: station.name }));
            }
        });

        this.audio?.addEventListener('error', () => {
            if (this.audio?.src) {
                this.setStatus(this.t('streamFailed'), true);
            }
        });
    }

    async loadStations() {
        this.setLoading(true);
        this.hideRetry();
        this.setStatus(this.t('loading'));

        try {
            const response = await fetch(this.appendParams(this.config.routes?.radios, {
                language: this.config.language,
            }), {
                headers: { Accept: 'application/json' },
            });

            if (! response.ok) {
                throw new Error(`Request failed: ${response.status}`);
            }

            const payload = await response.json();
            this.stations = Array.isArray(payload.radios)
                ? payload.radios.filter((station) => this.validStation(station))
                : [];

            if (this.stations.length === 0) {
                this.selectedStationId = null;
                this.filteredStations = [];
                this.renderStations();
                this.setStatus(this.t('empty'));

                return;
            }

            const restored = this.stations.find((station) => String(station.id) === String(this.selectedStationId));
            this.selectStation(restored ?? this.stations[0], false);
            this.filterStations();
            this.setStatus(this.t('ready'));
        } catch {
            this.stations = [];
            this.filteredStations = [];
            this.renderStations();
            this.setStatus(this.t('failed'), true);
            this.showRetry();
        } finally {
            this.setLoading(false);
        }
    }

    filterStations() {
        const query = this.normalize(this.search?.value);
        this.filteredStations = query === ''
            ? [...this.stations]
            : this.stations.filter((station) => this.normalize(station.name).includes(query));

        this.renderStations();
    }

    renderStations() {
        if (! this.list) {
            return;
        }

        this.list.replaceChildren();

        if (this.filteredStations.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'rounded-2xl border border-dashed border-white/10 px-4 py-8 text-center text-sm text-slate-400';
            empty.textContent = this.stations.length === 0 ? this.t('empty') : this.t('noResults');
            this.list.append(empty);
        } else {
            this.filteredStations.forEach((station, index) => {
                this.list.append(this.stationButton(station, index));
            });
        }

        if (this.count) {
            this.count.textContent = this.format('showing', { count: this.filteredStations.length });
        }

        const navigable = this.filteredStations.length > 1;
        this.previous.disabled = ! navigable;
        this.next.disabled = ! navigable;
    }

    stationButton(station, index) {
        const selected = String(station.id) === String(this.selectedStationId);
        const button = document.createElement('button');
        button.type = 'button';
        button.className = [
            'group flex min-h-14 w-full items-center gap-3 rounded-2xl border px-3 py-2.5 text-start transition',
            selected
                ? 'border-emerald-300/40 bg-emerald-300/10 text-white'
                : 'border-white/10 bg-[#071d20]/70 text-slate-200 hover:border-white/20 hover:bg-white/10',
        ].join(' ');
        button.setAttribute('role', 'listitem');
        button.setAttribute('aria-pressed', selected ? 'true' : 'false');
        button.setAttribute('aria-label', this.format('play', { station: station.name }));

        const number = document.createElement('span');
        number.className = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/5 text-xs font-semibold text-slate-400 ring-1 ring-white/10 group-hover:text-emerald-200';
        number.textContent = String(index + 1).padStart(2, '0');

        const name = document.createElement('span');
        name.className = 'min-w-0 flex-1 truncate text-sm font-medium';
        name.textContent = station.name;

        const live = document.createElement('span');
        live.className = selected
            ? 'rounded-full bg-emerald-300/15 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-emerald-200'
            : 'hidden';
        live.textContent = this.t('nowPlaying');

        button.append(number, name, live);
        button.addEventListener('click', () => this.selectStation(station, true));

        return button;
    }

    async selectStation(station, play = false) {
        if (! station || ! this.audio) {
            return;
        }

        const changed = String(station.id) !== String(this.selectedStationId)
            || this.audio.getAttribute('src') !== station.url;
        this.selectedStationId = station.id;
        this.current.textContent = station.name;
        this.persistStationId(station.id);

        if (changed) {
            this.audio.pause();
            this.audio.src = station.url;
            this.audio.load();
        }

        this.renderStations();

        if (! play) {
            return;
        }

        try {
            await this.audio.play();
        } catch {
            this.setStatus(this.t('streamFailed'), true);
        }
    }

    moveSelection(offset) {
        if (this.filteredStations.length === 0) {
            return;
        }

        const currentIndex = this.filteredStations.findIndex(
            (station) => String(station.id) === String(this.selectedStationId),
        );
        const start = currentIndex >= 0 ? currentIndex : 0;
        const nextIndex = (start + offset + this.filteredStations.length) % this.filteredStations.length;

        this.selectStation(this.filteredStations[nextIndex], true);
    }

    selectedStation() {
        return this.stations.find((station) => String(station.id) === String(this.selectedStationId));
    }

    validStation(station) {
        return station
            && Number(station.id) > 0
            && typeof station.name === 'string'
            && station.name.trim() !== ''
            && typeof station.url === 'string'
            && /^https?:\/\//i.test(station.url);
    }

    setLoading(loading) {
        if (this.search) {
            this.search.disabled = loading;
        }

        if (loading) {
            this.previous.disabled = true;
            this.next.disabled = true;
        }
    }

    setStatus(message, error = false) {
        if (! this.status) {
            return;
        }

        this.status.textContent = message;
        this.status.classList.toggle('text-rose-300', error);
        this.status.classList.toggle('text-slate-400', ! error);
    }

    showRetry() {
        this.retry?.classList.remove('hidden');
        this.retry?.classList.add('inline-flex');
    }

    hideRetry() {
        this.retry?.classList.add('hidden');
        this.retry?.classList.remove('inline-flex');
    }

    persistStationId(stationId) {
        try {
            localStorage.setItem(this.storageKey, String(stationId));
        } catch {
            // Ignore storage failures.
        }
    }

    restoreStationId() {
        try {
            return localStorage.getItem(this.storageKey);
        } catch {
            return null;
        }
    }

    appendParams(url, params = {}) {
        const next = new URL(url, window.location.origin);

        Object.entries(params).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '') {
                next.searchParams.set(key, value);
            }
        });

        return next.toString();
    }

    normalize(value) {
        return String(value ?? '').trim().toLocaleLowerCase(this.config.locale);
    }

    format(key, replacements = {}) {
        return Object.entries(replacements).reduce(
            (message, [name, value]) => message.replaceAll(`:${name}`, String(value)),
            this.t(key),
        );
    }

    t(key) {
        return this.config.labels?.[key] ?? key;
    }
}

const onlineRadioInstances = new WeakMap();

function initOnlineRadios(scope = document) {
    const roots = scope instanceof Element && scope.matches('[data-online-radio]')
        ? [scope]
        : Array.from(scope.querySelectorAll?.('[data-online-radio]') ?? []);

    roots.forEach((root) => {
        if (! onlineRadioInstances.has(root)) {
            onlineRadioInstances.set(root, new OnlineRadio(root));
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initOnlineRadios();

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node instanceof Element) {
                    initOnlineRadios(node);
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });
});

document.addEventListener('livewire:initialized', () => initOnlineRadios());
document.addEventListener('livewire:navigated', () => initOnlineRadios());
