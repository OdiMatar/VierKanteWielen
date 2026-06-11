<x-layout title="Home">
    <div class="home-dashboard">
        <section class="home-hero">
            <div class="home-hero-grid">
                <div class="home-hero-copy">
                    <p class="home-kicker">Dashboard</p>
                    <h1>Welkom bij Rijschool Vierkante Wielen</h1>
                    <p class="home-subtitle">
                        Alles wat je nodig hebt voor planning, voertuigen en lespakketten in een helder en krachtig overzicht.
                    </p>
                    <div class="home-hero-actions">
                        <a class="btn btn-home-primary" href="{{ route('lesrijpakketten.index') }}">Bekijk Lespakketten</a>
                        <a class="btn btn-home-ghost" href="{{ route('instructeurs.index') }}">Ga Naar Instructeurs</a>
                        @if (auth()->user()->canManagePayments())
                            <a class="btn btn-home-ghost" href="{{ route('betalingen.index') }}">Betalingen</a>
                        @endif
                    </div>
                </div>
                <div class="home-hero-panel">
                    <p class="home-panel-title">Jouw Toegang</p>
                    <h2>{{ ucfirst(auth()->user()->role) }}</h2>
                    <p class="mb-0">
                        @if (auth()->user()->canManageVehicles())
                            Je kunt voertuigen beheren, koppelingen wijzigen en accountoverzichten bekijken.
                        @else
                            Je werkt met kijkrechten en ziet planning en voertuigtoewijzingen.
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <section class="home-stats">
            <article class="home-stat-card">
                <span>Modules</span>
                <strong>4</strong>
                <p>Lespakketten, voertuigen, betalingen en accounts</p>
            </article>
            <article class="home-stat-card">
                <span>Status</span>
                <strong>Actief</strong>
                <p>Omgeving klaar voor dagelijks beheer</p>
            </article>
            <article class="home-stat-card">
                <span>Gebruiker</span>
                <strong>{{ auth()->user()->name }}</strong>
                <p>Ingelogd en gereed om te starten</p>
            </article>
        </section>

        <section class="home-feature-grid">
            <article class="home-feature-card">
                <h2>Lesrijpakketten</h2>
                <p>Vergelijk prijzen, categorieen en lesaantallen op een overzichtelijke plek.</p>
                <a href="{{ route('lesrijpakketten.index') }}">Open overzicht</a>
            </article>

            <article class="home-feature-card">
                <h2>Instructeurs & Voertuigen</h2>
                <p>Bekijk per instructeur welke voertuigen al gekoppeld zijn en welke beschikbaar zijn.</p>
                <a href="{{ route('instructeurs.index') }}">Bekijk instructeurs</a>
            </article>

            <article class="home-feature-card">
                <h2>Betalingen</h2>
                <p>Registreer betalingen en bekijk bedragen, betaalmethodes, statussen en redenen.</p>
                <a href="{{ route('betalingen.index') }}">Open betalingen</a>
            </article>

            <article class="home-feature-card">
                <h2>Jouw rolrechten</h2>
                <p>
                    @if (auth()->user()->canManageVehicles())
                        Als administrator kun je direct voertuiggegevens aanpassen.
                    @else
                        Als instructeur heb je leesrechten voor de operationele data.
                    @endif
                </p>
                <span class="home-feature-chip">{{ ucfirst(auth()->user()->role) }}</span>
            </article>
        </section>

        <section class="home-story">
            <div class="home-story-image">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1200&q=80" alt="Leerling en instructeur tijdens rijles">
            </div>
            <div class="home-story-content">
                <h2>Waarom Vierkante Wielen?</h2>
                <p>
                    Bij Rijschool Vierkante Wielen combineren we structuur met persoonlijke aandacht. Iedere leerling
                    volgt een traject dat past bij zijn of haar tempo. Zo bouwen we stap voor stap aan zelfvertrouwen
                    op de weg, van de eerste les in rustige woonwijken tot het rijden op drukkere verkeerspunten.
                </p>
                <p>
                    Onze instructeurs kijken niet alleen naar techniek, maar ook naar rust, inzicht en verkeersgedrag.
                    Daardoor zijn leerlingen beter voorbereid op het examen en rijden ze daarna ook veiliger zelfstandig.
                    Met heldere lesdoelen en regelmatige feedback weet je altijd waar je staat.
                </p>
                <p class="mb-0">
                    Of je nu net begint of extra lessen nodig hebt voor het examen, ons team helpt je met een plan dat
                    werkt. Praktisch, duidelijk en zonder onnodige druk.
                </p>
            </div>
        </section>

        <section class="home-reviews">
            <div class="home-section-head">
                <p class="home-kicker mb-2">Reviews</p>
                <h2>Wat leerlingen over ons zeggen</h2>
            </div>
            <div class="home-review-grid">
                <article class="home-review-card">
                    <p>"Super fijne uitleg, rustig tempo en altijd duidelijke feedback. In 1 keer geslaagd."</p>
                    <div><strong>Sanne K.</strong> <span>- Praktijkexamen B</span></div>
                </article>
                <article class="home-review-card">
                    <p>"De planning was flexibel en de lessen sloten perfect aan op wat ik lastig vond."</p>
                    <div><strong>Yassin M.</strong> <span>- Herintreder</span></div>
                </article>
                <article class="home-review-card">
                    <p>"Goede sfeer in de auto en veel aandacht voor verkeersinzicht. Echt een aanrader."</p>
                    <div><strong>Jade R.</strong> <span>- Spoedtraject</span></div>
                </article>
            </div>
        </section>
    </div>
</x-layout>
