# G.U.G. Piemonte e Valle d'Aosta — handoff WordPress

Ridisegno grafico del sito esistente. **Struttura e contenuti restano quelli attuali: cambia solo la grafica.**
Stack: WordPress + Blocksy Free + tema figlio esistente + editor a blocchi (Gutenberg). Niente page builder.

Anteprima: `preview/index.html` (desktop, mobile e stampa per ogni pagina, generati dai file del tema).

## Struttura

```
gug-child/
  assets/css/gug.css          componenti BEM (prefisso gug-) — sempre caricato
  assets/css/gug-blocksy.css  solo stile su header/footer di Blocksy
  assets/css/gug-print.css    stampa A4 "Composizione della Giuria" (media="print")
  assets/img/                 logo GUG, banner reclutamento, loghi federazioni
  patterns/*.php              block pattern registrati dal tema figlio
  shortcodes/*.html           OUTPUT HTML atteso dagli shortcode/template dinamici
preview/                      solo anteprima, NON va in produzione
```

## Da fare in functions.php (tema figlio)

1. Google Fonts: `Barlow` 400/500/600/700 e `Barlow Condensed` 700/800 (o self-hosted).
2. Enqueue `gug.css` e `gug-blocksy.css` dopo lo stile di Blocksy; `gug-print.css` con media `print`, solo su `is_singular('designazioni')`.
3. Categorie pattern: `gug-pagine`, `gug-layout`. I file in `/patterns` vengono registrati automaticamente da WP ≥ 6.0.
4. **Shortcode/template esistenti: va cambiato solo il markup che stampano**, per farlo coincidere 1:1 con `shortcodes/*.html` (classi, gerarchia, attributi `data-label`). La logica PHP resta quella attuale. I nomi `gug_manifestazioni`, `gug_designazioni`, `gug_loghi` sono provvisori: vanno mappati su quelli reali.
5. Dopo l'adattamento si possono rimuovere i plugin di blocchi non più usati (Essential Blocks, Qubely).

## Mappa pagine → file

| Pagina | Pattern | Parti dinamiche |
|---|---|---|
| Home | `home.php` | `[gug_manifestazioni]` → `gug_manifestazioni.html` · core/query news → `gug-newslist.html` |
| Calendari 2025-2026 | `calendari.php` | — |
| Settore (es. /settori/nu/) | `settore-nuoto.php` | core/query filtrata per categoria del settore (`taxQuery.category`: inserire l'ID reale) |
| Designazioni settore | `designazioni-nuoto.php`, `designazioni-pallanuoto.php` | `[gug_designazioni settore="xx"]` → `gug_designazioni-nu.html` / `-pn.html` |
| Composizione della Giuria | `single-designazioni.php` (template CPT) | → `giuria-nu.html` / `giuria-pn.html` (stessa marcatura per schermo e stampa) |
| Regolamenti / Modulistica / Formazione | `regolamenti-nuoto.php`, `modulistica-nuoto.php`, `formazione-nuoto.php` | — (una pagina per settore: duplicare e cambiare `gug-sector--xx`) |
| Presentazione | `presentazione.php` | — |
| Diventa un Ufficiale Gara | `diventa-ufficiale-gara.php` | — |
| Footer | `footer-info.php` | da inserire come widget nel Footer Builder |

## Convenzioni CSS

- BEM: `gug-blocco__elemento--modificatore`. Il layout è tutto nel CSS: nei pattern i gruppi usano `layout: default` e non ci sono stili inline (unica eccezione un margin-top nel box contatti).
- Colori di settore (nazionali FIN) tramite modificatore riusabile: `gug-sector--nu|pn|sa|sy|tu` imposta `--gug-sector`, `--gug-sector-ink` (variante scura, per testo su bianco), `--gug-sector-deep`, `--gug-sector-light`. Va su hero, card, tabelle e **sulle voci del menu settori** (campo "Classi CSS" del menu WP).
- Icone documento automatiche dall'estensione del link: `.pdf` rosso, `.xls/.xlsx` verde, link YouTube icona video.
- Breakpoint allineati a Blocksy: tablet `< 1000px`, mobile `< 690px`.
- Titoli display: Barlow Condensed 800 in maiuscolo; testo Barlow.

## Impostazioni Blocksy (Customizer)

- **Header** riga top: Menu 2 = settori (ogni voce con classe `gug-sector--xx`, sottomenu Designazioni / Regolamenti / Formazione Ufficiali Gara / Modulistica). Riga middle: Logo + titolo/descrizione sito, Menu 1 (Home, Calendari, Regolamenti, Link), Button "Gestionale Federnuoto". Mobile: Logo + Trigger → Offcanvas con Mobile Menu.
- **Footer**: riga top = widget con `[gug_loghi]`, riga middle = widget con pattern `gug/footer-info`, riga bottom = Copyright. Niente menu, social, privacy/cookie nel mockup (valutare informativa privacy prima del go-live).
- **Pagine**: struttura a larghezza piena (stretched), titolo pagina di Blocksy disattivato (il titolo è nell'hero del pattern), spaziatura area contenuto a zero.
- Font e colori globali come in `gug-blocksy.css` (le variabili `--theme-*` sono impostate anche da CSS come fallback).

## Da verificare in sviluppo

- I selettori di `gug-blocksy.css` sono basati sul markup standard di Blocksy 2.x: controllarli sulla versione installata.
- Il markup dei pattern è serializzazione Gutenberg valida: dopo l'inserimento, aprirli nell'editor e verificare che non compaia "Blocco con contenuto non valido".
- I link dei documenti nei pattern sono segnaposto `/wp-content/uploads/...`: sostituirli con i file reali.
