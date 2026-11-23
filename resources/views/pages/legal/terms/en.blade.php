<p>These Terms of Use govern the use of the website of <strong>{{ $mosqueName }}</strong>@if($mosqueAddress), {{ $mosqueAddress }}@endif. By using the website you accept these terms.</p>

<h2>1. Services</h2>
<p>The website provides information about the mosque and its community, including prayer times, events, announcements, news, sermons, educational content and a contact form. Use of the website is free of charge and does not require registration.</p>

<h2>2. Technical requirements</h2>
<p>To use the website you need a device with internet access and an up-to-date web browser with JavaScript enabled. Some features depend on cookies as described in the <a href="{{ route('legal.cookies') }}">Cookie Policy</a>.</p>

<h2>3. Rules of use</h2>
<ul>
    <li>Do not submit unlawful, offensive or misleading content, or content that infringes the rights of others.</li>
    <li>Do not attempt to disrupt the website, gain unauthorised access or send unsolicited commercial messages through the contact form.</li>
    <li>Prayer times and calendar dates are provided for guidance. Please confirm important times with the mosque.</li>
</ul>

<h2>4. Intellectual property</h2>
<p>Texts, images, logos and other content on the website belong to the mosque or are used with permission. They may be shared for personal, non-commercial purposes with attribution; any other use requires our prior consent. Qur'anic texts, recitations and third-party content remain subject to the rights of their respective owners.</p>

<h2>5. External links and content</h2>
<p>The website may contain links to and embedded content from third parties. We are not responsible for the content or privacy practices of those websites.</p>

<h2>6. Liability</h2>
<p>We take care to keep the information accurate and up to date but cannot guarantee that it is always complete or free of errors, or that the website is available without interruption. To the extent permitted by law, we are not liable for damages resulting from the use of the information provided. Nothing in these terms limits the rights of consumers under mandatory law.</p>

<h2>7. Complaints and contact</h2>
<p>Comments and complaints about the website can be sent @if($mosqueEmail)to <a href="mailto:{{ $mosqueEmail }}">{{ $mosqueEmail }}</a> or @endif via the <a href="{{ route('contact.index') }}">contact page</a>. We will reply within 14 days.</p>

<h2>8. Personal data</h2>
<p>Personal data is processed in accordance with our <a href="{{ route('legal.privacy') }}">Privacy Policy</a>.</p>

<h2>9. Final provisions</h2>
<p>These terms are governed by Polish law. We may amend these terms; changes take effect when published on this page.</p>
