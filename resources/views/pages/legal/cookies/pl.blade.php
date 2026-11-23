<p>Niniejsza Polityka cookies wyjaśnia, jakie pliki cookie i podobne technologie są używane na tej stronie oraz jak możesz nimi zarządzać. Uzupełnia ona naszą <a href="{{ route('legal.privacy') }}">Politykę prywatności</a>.</p>

<h2>1. Czym są pliki cookie?</h2>
<p>Pliki cookie to niewielkie pliki tekstowe zapisywane na Twoim urządzeniu przez przeglądarkę. Zgodnie z art. 399 ustawy – Prawo komunikacji elektronicznej oraz RODO możemy przechowywać informacje lub uzyskiwać do nich dostęp na Twoim urządzeniu bez zgody tylko wtedy, gdy jest to niezbędne do świadczenia usługi, o którą prosisz. Wszystkie pozostałe pliki cookie wymagają Twojej uprzedniej zgody.</p>

<h2>2. Pliki cookie, których używamy</h2>
<h3>Niezbędne (zawsze aktywne)</h3>
<table>
    <thead><tr><th>Plik cookie</th><th>Cel</th><th>Ważność</th></tr></thead>
    <tbody>
        <tr><td>{{ $sessionCookie }}</td><td>Utrzymuje sesję i wybrany język.</td><td>{{ $sessionLifetime }} minut</td></tr>
        <tr><td>XSRF-TOKEN</td><td>Chroni formularze przed atakami CSRF.</td><td>{{ $sessionLifetime }} minut</td></tr>
        <tr><td>cc_cookie</td><td>Zapamiętuje Twój wybór dotyczący plików cookie.</td><td>6 miesięcy</td></tr>
    </tbody>
</table>

<h3>Analityczne (wyłącznie za zgodą)</h3>
<table>
    <thead><tr><th>Plik cookie</th><th>Dostawca</th><th>Cel</th><th>Ważność</th></tr></thead>
    <tbody>
        <tr><td>_ga</td><td>Google Analytics</td><td>Rozróżnia unikalnych użytkowników.</td><td>2 lata</td></tr>
        <tr><td>_ga_*</td><td>Google Analytics</td><td>Utrzymuje stan sesji.</td><td>2 lata</td></tr>
    </tbody>
</table>

<h3>Treści zewnętrzne (wyłącznie za zgodą)</h3>
<p>Osadzone filmy YouTube, Vimeo lub Facebook oraz Mapy Google są blokowane do czasu zezwolenia na „Treści zewnętrzne”. Następnie dostawcy ci mogą zapisywać własne pliki cookie. Zapoznaj się z ich politykami prywatności: <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google / YouTube</a>, <a href="https://vimeo.com/privacy" target="_blank" rel="noopener">Vimeo</a>, <a href="https://www.facebook.com/privacy/policy" target="_blank" rel="noopener">Meta</a>.</p>

<h2>3. Zarządzanie zgodą</h2>
<p>Przy pierwszej wizycie możesz wybrać „Akceptuj wszystkie”, „Odrzuć wszystkie” lub „Ustawienia”. Przed dokonaniem wyboru nie są zapisywane żadne opcjonalne pliki cookie, a odrzucenie jest równie proste jak akceptacja. Twój wybór obowiązuje we wszystkich wersjach językowych strony. Zgodę możesz w każdej chwili zmienić lub wycofać za pomocą linku „Ustawienia cookies” w stopce każdej strony; po wycofaniu zgody na analitykę powiązane pliki cookie są usuwane.</p>
<p>Możesz również blokować lub usuwać pliki cookie w ustawieniach przeglądarki. Zablokowanie niezbędnych plików cookie może uniemożliwić działanie części strony.</p>
