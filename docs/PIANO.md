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
