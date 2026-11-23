import 'vanilla-cookieconsent/dist/cookieconsent.css';
import * as CookieConsent from 'vanilla-cookieconsent';

const syncEmbeds = () => {
    document.querySelectorAll('[data-consent-src]').forEach((frame) => {
        const category = frame.dataset.consentCategory || 'media';
        const isAccepted = CookieConsent.acceptedCategory(category);
        const wrapper = frame.closest('[data-consent-embed]');
        const placeholder = wrapper?.querySelector('[data-consent-placeholder]');

        if (isAccepted && frame.getAttribute('src') !== frame.dataset.consentSrc) {
            frame.setAttribute('src', frame.dataset.consentSrc);
        }

        if (! isAccepted && frame.hasAttribute('src')) {
            frame.removeAttribute('src');
        }

        placeholder?.classList.toggle('hidden', isAccepted);
    });
};

const bindPlaceholderButtons = () => {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-consent-accept]');

        if (! button) {
            return;
        }

        const accepted = CookieConsent.getUserPreferences().acceptedCategories;

        CookieConsent.acceptCategory([...new Set([...accepted, button.dataset.consentAccept])]);
        syncEmbeds();
    });
};

const configElement = document.getElementById('cookie-consent-config');

if (configElement) {
    const config = JSON.parse(configElement.textContent);

    bindPlaceholderButtons();

    CookieConsent.run({
        revision: config.revision,
        hideFromBots: true,
        disablePageInteraction: false,
        cookie: {
            name: 'cc_cookie',
            expiresAfterDays: 182,
            sameSite: 'Lax',
        },
        guiOptions: {
            consentModal: {
                layout: 'box wide',
                position: 'bottom center',
                equalWeightButtons: true,
                flipButtons: false,
            },
            preferencesModal: {
                layout: 'box',
                equalWeightButtons: true,
                flipButtons: false,
            },
        },
        categories: {
            necessary: {
                enabled: true,
                readOnly: true,
            },
            analytics: {
                autoClear: {
                    cookies: [
                        { name: /^_ga/ },
                        { name: '_gid' },
                    ],
                    reloadPage: true,
                },
            },
            media: {},
        },
        onConsent: syncEmbeds,
        onChange: syncEmbeds,
        language: {
            default: config.locale,
            rtl: config.rtl ? [config.locale] : [],
            translations: {
                [config.locale]: config.translations,
            },
        },
    });

    syncEmbeds();

    window.CookieConsent = CookieConsent;
}
