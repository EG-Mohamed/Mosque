@php
    $cookieConsentConfig = [
        'locale' => app()->getLocale(),
        'rtl' => in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']),
        'revision' => 1,
        'translations' => [
            'consentModal' => [
                'title' => __('We value your privacy'),
                'description' => __('We use strictly necessary cookies to make this website work. With your permission, we would also like to use analytics cookies to understand how the website is used, and to load external media such as videos and maps. These are only activated if you accept them.').' <a href="'.route('legal.cookies').'">'.e(__('Cookie Policy')).'</a>',
                'acceptAllBtn' => __('Accept all'),
                'acceptNecessaryBtn' => __('Reject all'),
                'showPreferencesBtn' => __('Settings'),
                'footer' => '<a href="'.route('legal.privacy').'">'.e(__('Privacy Policy')).'</a> <a href="'.route('legal.terms').'">'.e(__('Terms of Use')).'</a>',
            ],
            'preferencesModal' => [
                'title' => __('Cookie settings'),
                'acceptAllBtn' => __('Accept all'),
                'acceptNecessaryBtn' => __('Reject all'),
                'savePreferencesBtn' => __('Save settings'),
                'closeIconLabel' => __('Close'),
                'serviceCounterLabel' => __('Service|Services'),
                'sections' => [
                    [
                        'title' => __('Your choices'),
                        'description' => __('You can choose which optional categories you allow. You can change or withdraw your consent at any time using the "Cookie settings" link at the bottom of every page.'),
                    ],
                    [
                        'title' => __('Strictly necessary'),
                        'description' => __('These cookies are required for the website to function (for example security, language selection and remembering your cookie choice) and cannot be switched off.'),
                        'linkedCategory' => 'necessary',
                        'cookieTable' => [
                            'headers' => [
                                'name' => __('Cookie'),
                                'description' => __('Purpose'),
                                'expiration' => __('Expiration'),
                            ],
                            'body' => [
                                [
                                    'name' => config('session.cookie'),
                                    'description' => __('Keeps your session and selected language.'),
                                    'expiration' => __(':count minutes', ['count' => config('session.lifetime')]),
                                ],
                                [
                                    'name' => 'XSRF-TOKEN',
                                    'description' => __('Protects forms against cross-site request forgery.'),
                                    'expiration' => __(':count minutes', ['count' => config('session.lifetime')]),
                                ],
                                [
                                    'name' => 'cc_cookie',
                                    'description' => __('Stores your cookie consent choice.'),
                                    'expiration' => __('6 months'),
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => __('Analytics'),
                        'description' => __('Help us understand how visitors use the website (Google Analytics 4). The data is aggregated and used only to improve the website.'),
                        'linkedCategory' => 'analytics',
                        'cookieTable' => [
                            'headers' => [
                                'name' => __('Cookie'),
                                'description' => __('Purpose'),
                                'expiration' => __('Expiration'),
                            ],
                            'body' => [
                                [
                                    'name' => '_ga',
                                    'description' => __('Distinguishes unique visitors (Google Analytics).'),
                                    'expiration' => __('2 years'),
                                ],
                                [
                                    'name' => '_ga_*',
                                    'description' => __('Maintains session state (Google Analytics).'),
                                    'expiration' => __('2 years'),
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => __('External media'),
                        'description' => __('Content embedded from third parties such as YouTube videos and Google Maps. These providers may set their own cookies and process your IP address.'),
                        'linkedCategory' => 'media',
                    ],
                    [
                        'title' => __('More information'),
                        'description' => __('For details on how we process personal data and your rights, read our').' <a href="'.route('legal.privacy').'">'.e(__('Privacy Policy')).'</a> '.__('and').' <a href="'.route('legal.cookies').'">'.e(__('Cookie Policy')).'</a>.',
                    ],
                ],
            ],
        ],
    ];
@endphp
<script type="application/json" id="cookie-consent-config">@json($cookieConsentConfig)</script>
