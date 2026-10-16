# CLAUDE.md — tema figlio gugpiemonte

Tema figlio di **Blocksy** per gugpiemonte.it (Gruppo Ufficiali Gara FIN Piemonte e Valle d'Aosta).
Redesign online dal 12/10/2026, pulizia della versione vecchia il 16/10/2026 (tag git `pre-pulizia-20261016`).

## Regole
- **Prima il nativo:** stili e impostazioni dal Customizer di Blocksy e dalle impostazioni dei blocchi
  (anche stili responsive di WordPress 7.1). Nel SASS solo le eccezioni, ognuna commentata.
- Solo blocchi nativi Gutenberg/Blocksy nelle pagine. Shortcode nuovi con nomi parlanti in italiano.
- Testi e meta SEO: settori sempre con l'articolo ("designazioni **del** nuoto").
- Comunicazione con l'utente solo in italiano.

## Struttura
```
functions.php            carica tutti i moduli di /modules
modules/
  theme-assets.php       CSS (css/gug.min.css, stampa giuria), font locali + preload, classi body,
                         categorie pattern, blocco anteprima Customizer per i visitatori
  page-options.php       opzioni Blocksy delle pagine con hero GUG (riconosciute da "gug-page-hero")
  pre-get-posts.php      archivio settore: news pubblicate della stagione in corso (loop ereditato)
  seo-schema.php         JSON-LD di Rank Math senza autore personale
  shortcode-elenco-designazioni.php    [elenco-designazioni settore=""]
  shortcode-manifestazioni-settimana.php [manifestazioni-settimana] (Home)
  shortcode-risorse-settore.php        [risorse-settore] (archivio settore)
  shortcode-icone-social.php           [icone-social] (footer)
  register-post-types.php / register-taxonomies.php  designazioni, ufficiali-gara / settori, stagioni
  add-admin-column.php / add-admin-filters.php       colonne e filtri in bacheca
  mime-types.php / utils.php           upload SVG/WebP / redirect della scheda Ufficiale Gara
single-designazioni.php  "Composizione della Giuria" (parts/single-designazione/composizione-giuria.php)
taxonomy-settori.php     archivio settore dal pattern sincronizzato "Archivio settore"
patterns/                pattern di pagina (Home, Presentazione, footer…)
sass/                    sorgenti → css/gug.min.css e css/gug-print.min.css
assets/                  font Barlow locali, immagini del tema
docs/                    PIANO.md, GOLIVE.md, handoff grafico (docs/wordpress)
```

## Compilare il CSS
```bash
sass --no-source-map --style=compressed sass/gug.scss css/gug.min.css
sass --no-source-map --style=compressed sass/gug-print.scss css/gug-print.min.css
```
La versione dei file è `filemtime()`: dopo il caricamento non serve svuotare la cache del browser.

## Da sapere
- Produzione su Aruba Windows/IIS: deploy via FTP (lftp), niente cache pagine di LiteSpeed (solo ottimizzazione).
  LiteSpeed "Rimuovi query string" deve restare spento.
- Header, footer, menu mobile, news singole: tutto nel Customizer di Blocksy. Prima di salvare il Customizer
  ricaricarlo, altrimenti riscrive l'header con valori vecchi.
- Footer: 3 widget a blocchi (block-52/53/54) + riga loghi (pattern sincronizzato "Loghi federazioni").
- `dettagli_data` delle designazioni è numerico Ymd (query `BETWEEN` sulla settimana).
- Verifiche mobile sempre da visitatore con User-Agent Android (LiteSpeed ottimizza diversamente).
