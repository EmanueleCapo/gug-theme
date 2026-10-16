# Redesign GUG Piemonte — Piano d'attacco

Regole: vedi `GOLIVE.md`. Riferimento grafico: `docs/wordpress/` (handoff Claude Design, Blocksy free + Companion free).

## Metodo per ogni passo

1. Codice scritto **a partire dai file dell'handoff** (CSS, pattern, HTML shortcode copiati 1:1, si adatta solo il necessario).
2. `php -l` su ogni PHP toccato, commit su `main`.
3. Upload FTP **solo dei file toccati**, purge cache LiteSpeed/FVM se cambia CSS.
4. Verifica con Chrome DevTools:
   - da **anonimo**: il sito pubblico è identico a prima;
   - da **loggato**: screenshot della bozza affiancato all'anteprima `preview/index.html`, a 1280px e 390px (+ stampa dove serve).
5. OK dell'utente → passo successivo. Un passo = una cosa verificabile.

## Fasi

### 0. Accesso REST (sblocca la creazione delle bozze da qui)
IIS di Aruba intercetta l'header `Authorization`. Mu-plugin `gug-rest-auth.php`: legge un header alternativo (`X-WP-Auth`) e lo passa all'autenticazione nativa delle application password. Solo lettura/scrittura via utenti con application password.

### 1. Fondamenta (nessun effetto visibile da anonimo)
- `modules/redesign-setup.php`: helper `gug_redesign_is_active()` (utente loggato), enqueue Google Fonts Barlow, `css/gug.min.css`, `css/gug-print.css` (solo `is_singular('designazioni')`), categorie pattern `gug-pagine` / `gug-layout`.
- `sass-gug/`: `gug.css` dell'handoff spezzato in partial per componente → compilato in `css/gug.min.css`.
- `patterns/`: copiati dall'handoff (registrati in automatico da WP ≥ 6.0, invisibili al pubblico).
- `assets/img/`: loghi e banner.

### 2. Header e footer (solo loggati)
- Menu nuovi creati in admin (non assegnati = invisibili), assegnati via filtro solo agli utenti loggati; classi `gug-sector--xx` sulle voci.
- `gug-blocksy.css` verificato sui selettori della versione Blocksy installata.
- Footer: loghi + pattern `footer-info` stampati tramite hook Blocksy per i loggati, footer attuale nascosto solo per loro.

### 3. Pagine statiche (una alla volta, bozze)
Presentazione → Diventa Ufficiale Gara → Calendari → Regolamenti / Modulistica / Formazione (primo settore, poi duplicazione per gli altri cambiando `gug-sector--xx`).
Contenuti e link documenti presi **dalle pagine attuali**, inseriti nella struttura del pattern. Solo blocchi core.

### 4. Parti dinamiche (shortcode nuovi, nomi parlanti)
| Nuovo | Sostituisce | Output atteso |
|---|---|---|
| `[manifestazioni-settimana]` | `[designazioni-home]` | `gug_manifestazioni.html` |
| `[elenco-designazioni settore="nu"]` | `[listing-designazioni]` | `gug_designazioni-nu/pn.html` |
| `[loghi-federazioni]` | `[partner-grid]` / `[partner-carousel]` | `gug_loghi.html` |
| blocco core Query Loop | `[home-news]` | `gug-newslist.html` |
| `single-designazioni.php` (markup nuovo solo loggati) | box attuali | `giuria-nu/pn.html` + stampa |

Logica PHP/ACF identica a quella esistente, cambia solo il markup. Nessun `wp_is_mobile()`: il responsive lo fa il CSS.

### 5. Home e pagine Settore
Pattern `home.php` e `settore-*.php` con gli shortcode/query della fase 4.

### 6. Go-live
Checklist `GOLIVE.md`.

### 7. Pulizia
Moduli, shortcode, SASS vecchi, Owl Carousel, Essential Blocks/Qubely; rinomina dei file nuovi.

### 8. Hero nativo di Blocksy per le pagine — FATTO 16/10/2026
Fatto: Customizer Pagine = Articoli singoli (padding 44/52, mobile 26/32), gruppo hero tolto da 21 pagine
(Home esclusa), confronto screenshot: desktop identico, mobile identico salvo antialiasing.

Obiettivo: niente più blocco hero dentro il contenuto; l'hero lo genera Blocksy dal titolo della pagina,
come già per le news (Personalizza → Articoli singoli). Stile modificabile da un solo punto.
1. **Prima:** foto degli stili calcolati e screenshot (desktop 1280, S20 360) di tutte le 22 pagine, da visitatore con User-Agent Android.
2. **Customizer → Pagine:** hero tipo 2, a tutta larghezza, titolo a sinistra, Barlow Condensed 800 maiuscolo (72/56/42 come l'H1),
   padding 44/52 (mobile 26/32), sfondo sfumato blu (quello di Calendari/Presentazione) come predefinito.
3. **Colore per settore:** nelle opzioni di pagina di Blocksy (editor → Blocksy → Hero) sfondo sfumato del settore
   (nuoto verde, pallanuoto arancio, salvamento giallo, artistico rosso, tuffi viola: stessi gradienti degli hero attuali).
4. **Contenuti:** togliere il gruppo `gug-page-hero` da ogni pagina dall'editor (blocchi validi); il titolo della pagina diventa l'H1.
   Attenzione: `gug_redesign_is_pattern_page()` riconosce le pagine dal marcatore `gug-page-hero` → sostituirlo prima
   (es. con un controllo sul template o su un meta) o le pagine perdono la struttura a tutta larghezza.
5. **Dopo:** stesso confronto del punto 1 (differenze solo dove previste), controllo H1 unico, Lighthouse a campione.
6. Togliere dal SASS le regole `.gug-page-hero` non più usate.

## Inventario produzione (2026-10-08)

WordPress 7.1.3 · Blocksy 2.1.58 · Companion 2.1.58 (free) · Classic Widgets attivo · LiteSpeed attivo (FVM inattivo).
Accesso REST: mu-plugin `wp-content/mu-plugins/gug-rest-auth.php` (header `X-WP-Auth: Basic …`).
Menu: `main` (id 2, menu_1), `settori` (id 3, menu_2), `mobile` (id 29). Homepage = pagina 1147.

| Pagina | ID attuale |
|---|---|
| Home | 1147 |
| Presentazione | 2 |
| Diventa un Ufficiale Gara | 35 |
| Calendari 2025-2026 | 1509 |
| Designazioni nu / pn / sa / sy / tu | 1188 / 1221 / 1222 / 1223 / 1224 |
| Regolamenti nu / pn / sa / sy / tu | 1345 / 1705 / 1744 / 1762 / 1794 |
| Modulistica nu / pn / sa / sy / tu | 1567 / 1736 / 1750 / 1785 / 1799 |
| Formazione UG nu / pn / sy | 1553 / 1730 / 1768 |

Pagine Settore (`/settori/xx/`) = archivio tassonomia `settori`, non pagine.

## Bozze create (fase 3+)

| Pagina | Vecchia | Bozza nuova | Stato |
|---|---|---|---|
| Presentazione | 2 | 5818 | verificata desktop/mobile, blocchi validi |
| Diventa un Ufficiale Gara | 35 | 5819 | verificata desktop, PDF reali collegati, blocchi validi |
| Calendari 2026-2027 | 1509 | 5820 | verificata desktop/mobile, link attuali (anni stagione da aggiornare), blocchi validi |
| Regolamenti nu / pn / sa / sy / tu | 1345 / 1705 / 1744 / 1762 / 1794 | 5821 / 5824 / 5827 / 5829 / 5832 | convertite con `docs/tools/convert-doc-page.php` |
| Modulistica nu / pn / sa / sy / tu | 1567 / 1736 / 1750 / 1785 / 1799 | 5823 / 5826 / 5828 / 5831 / 5833 | idem |
| Formazione UG nu / pn / sy | 1553 / 1730 / 1768 | 5822 / 5825 / 5830 | idem |

Conversione documenti: 111 documenti (= pagine attuali), 99 URL verificati. Due link Federnuoto già rotti sulle pagine attuali (Trofeo delle Regioni PN 2025, 404). Tutte le 13 bozze: blocchi validi.
| Designazioni nu / pn / sa / sy / tu | 1188 / 1221 / 1222 / 1223 / 1224 | 5834 / 5835 / 5836 / 5837 / 5838 | shortcode [elenco-designazioni], verificata nu con ?gug_settimana=20260717 |
| Composizione della Giuria (single-designazioni) | template vecchio | parts/single-designazione/redesign.php (solo loggati) | verificata desktop + stampa A4 nu e pn |
| Home | 1147 | 5845 | [manifestazioni-settimana] + Query Loop news (6, senza filtro stagione) + promo; slider Essential Blocks non riportato |
