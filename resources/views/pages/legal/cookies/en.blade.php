<p>This Cookie Policy explains what cookies and similar technologies are used on this website and how you can control them. It complements our <a href="{{ route('legal.privacy') }}">Privacy Policy</a>.</p>

<h2>1. What are cookies?</h2>
<p>Cookies are small text files stored on your device by your browser. Under Article 399 of the Polish Electronic Communications Law (Prawo komunikacji elektronicznej) and the GDPR, we may only store or access information on your device without your consent where it is strictly necessary to provide the service you requested. All other cookies require your prior consent.</p>

<h2>2. Cookies we use</h2>
<h3>Strictly necessary (always active)</h3>
<table>
    <thead><tr><th>Cookie</th><th>Purpose</th><th>Expiration</th></tr></thead>
    <tbody>
        <tr><td>{{ $sessionCookie }}</td><td>Keeps your session and selected language.</td><td>{{ $sessionLifetime }} minutes</td></tr>
        <tr><td>XSRF-TOKEN</td><td>Protects forms against cross-site request forgery.</td><td>{{ $sessionLifetime }} minutes</td></tr>
        <tr><td>cc_cookie</td><td>Stores your cookie consent choice.</td><td>6 months</td></tr>
    </tbody>
</table>

<h3>Analytics (only with consent)</h3>
<table>
    <thead><tr><th>Cookie</th><th>Provider</th><th>Purpose</th><th>Expiration</th></tr></thead>
    <tbody>
        <tr><td>_ga</td><td>Google Analytics</td><td>Distinguishes unique visitors.</td><td>2 years</td></tr>
        <tr><td>_ga_*</td><td>Google Analytics</td><td>Maintains session state.</td><td>2 years</td></tr>
    </tbody>
</table>

<h3>External media (only with consent)</h3>
<p>Embedded YouTube, Vimeo or Facebook videos and Google Maps are blocked until you allow "External media". After that, these providers may set their own cookies. Please refer to their privacy policies: <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google / YouTube</a>, <a href="https://vimeo.com/privacy" target="_blank" rel="noopener">Vimeo</a>, <a href="https://www.facebook.com/privacy/policy" target="_blank" rel="noopener">Meta</a>.</p>

<h2>3. Managing your consent</h2>
<p>When you first visit the website you can choose "Accept all", "Reject all" or "Settings". No optional cookies are set before you make a choice, and rejecting is as easy as accepting. Your choice applies to all language versions of the website. You can change or withdraw your consent at any time using the "Cookie settings" link at the bottom of every page; when you withdraw consent for analytics, the related cookies are deleted.</p>
<p>You can also block or delete cookies in your browser settings. Blocking strictly necessary cookies may prevent parts of the website from working.</p>
