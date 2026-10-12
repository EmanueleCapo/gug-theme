# Redesign GUG Piemonte — Regole d'oro e checklist go-live

Non c'è un ambiente di stage: il redesign si costruisce **direttamente in produzione**, invisibile ai visitatori.

## Regole d'oro durante lo sviluppo

1. **Niente upload automatici.** In `.vscode/sftp.json` `uploadOnSave` e `watcher.autoUpload` restano a `false`. Ogni upload FTP è un'azione esplicita.
2. **Il sito pubblico non deve cambiare.** Tutto ciò che è nuovo è visibile solo agli utenti loggati:
   - pagine nuove = **bozze** (o private), viste in anteprima;
   - CSS/font del redesign caricati solo per utenti loggati;
   - header e footer Blocksy nuovi solo per utenti loggati;
   - template condivisi (es. `single-designazioni.php`) stampano il markup nuovo solo per utenti loggati.
3. **Il vecchio non si tocca.** Moduli, shortcode e SASS esistenti restano invariati fino alla pulizia post go-live. Il nuovo vive in file separati.
4. **Shortcode nuovi con nomi parlanti** (in italiano, kebab-case, come gli originali: `[listing-designazioni]`, `[home-news]`…), diversi da quelli esistenti per non interferire con le pagine live.
5. **Solo blocchi nativi** Gutenberg (core) e Blocksy nelle pagine nuove. Niente Essential Blocks, Qubely o altri editor esterni.
6. **Ogni modifica committata** su `main` (https://github.com/EmanueleCapo/gug-theme) prima di essere caricata via FTP.
7. **Prima di caricare un file PHP** verificarlo (sintassi `php -l`): un errore fatale in un modulo blocca tutto il sito, perché `functions.php` li carica tutti.

## Checklist go-live

### Prima
- [ ] Backup completo **file + database** (pannello Aruba o plugin) e verifica che il backup sia scaricabile.
- [x] Export impostazioni Customizer di Blocksy (backup header/footer attuali).
- [x] Tutte le pagine nuove controllate in anteprima: desktop, tablet (< 1000px), mobile (< 690px), stampa giuria.
- [x] Nessun avviso "Blocco con contenuto non valido" nell'editor sulle pagine nuove.
- [ ] Link documenti segnaposto (`/wp-content/uploads/...`) sostituiti con i file reali.
- [x] Scegliere un momento di basso traffico (non a ridosso dell'uscita delle designazioni).

### Durante
- [x] Rinominare lo slug di ogni pagina vecchia (es. `calendari` → `calendari-old`) **prima** di metterla in bozza e pubblicare la nuova: altrimenti WordPress pubblica la nuova come `calendari-2`.
- [x] Mettere in bozza le pagine vecchie, pubblicare le nuove con titolo e slug corretti.
- [x] Impostazioni → Lettura: homepage = nuova pagina Home.
- [x] Menu: ricollegare le voci alle pagine nuove (i menu puntano all'ID) e impostare le classi `gug-sector--xx`.
- [x] Customizer: pubblicare la bozza (changeset `09d956e9-8d6c-4300-a708-147a9e851fc7`). Contiene:
  - tipografia Barlow/Barlow Condensed, colore testo e link (la palette è già pubblicata);
  - header (riga settori, logo con titolo e tagline linkata, menu, pulsante Gestionale, niente header trasparente) e footer (riga loghi, colonna unica, copyright);
  - widget footer: `block-50` (pattern sincronizzato "Loghi federazioni") e `block-51` (marchio, contatti, Seguici); i vecchi widget spostati tra gli inattivi;
  - menu: classi `gug-sector--xx` sulle voci settore, voce "Gestionale Federnuoto" eliminata dal menu principale.
  Anteprima della versione nativa, senza il modulo: aggiungere `&gug_hf_nativo=1` all'URL di anteprima.
- [x] Subito dopo la pubblicazione: rimuovere `modules/redesign-header-footer.php` (i moduli sono caricati in automatico dalla cartella: basta eliminare il file, anche via FTP). Gli stili in `sass-gug/_blocksy-adapt.scss` restano validi.
- [x] Font: il filtro `blocksy:typography:google:use-remote` deve restituire false per tutti (non solo per i loggati), così Barlow resta servito in locale dal tema figlio.
- [x] Attivare il redesign per tutti (togliere la condizione "solo utenti loggati" per CSS e template).
- [x] Svuotare **LiteSpeed Cache** e **Fast Velocity Minify** (e la cache del browser).

- [x] Rank Math: controllare in *Titoli e Meta* che le pagine nuove abbiano titolo SEO, description e parola chiave (già impostati sulle bozze); rigenerare llms.txt e sitemap salvando le impostazioni.
- [ ] Sostituire Page Links To (usato solo dalla news "Diventa U.G.!") con un reindirizzamento del modulo Reindirizzamenti di Rank Math, poi eliminare il plugin.

### Subito dopo
- [x] Navigazione da utente **non loggato** (finestra anonima): home, calendari, ogni settore, designazioni, singola designazione + stampa, regolamenti, modulistica, formazione, presentazione, 404.
- [x] Console del browser senza errori JS; nessun asset 404.
- [x] Vecchi URL ancora raggiungibili (slug invariati) e link interni funzionanti.

### Esito go-live (12/10/2026)
- Snapshot per il rollback in `backup-golive-20261012/` nella root del sito (fuori dal repo): pagine, impostazioni, menu, widget, valori del Customizer prima della pubblicazione, coppie vecchia→nuova pagina (`pairs.json`).
- Pagine vecchie in bozza con slug `xxx-old`; home vecchia = ID 1147 (`home-old`).
- Corretto dopo il controllo da non loggato: percorsi assoluti per i font (il "combina CSS" di LiteSpeed riscriveva male quelli relativi) e logo del footer (ordine del CSS diverso per i visitatori).
- Permalink risalvati: sitemap e llms.txt di Rank Math rispondevano 404.
- Da fare: Page Links To → reindirizzamento Rank Math; backup Aruba non eseguito prima (fatto solo lo snapshot).

### Rollback
1. Ripristinare la condizione "solo utenti loggati": `GUG_REDESIGN_LIVE = false` in `modules/redesign-setup.php` e rimettere `modules/redesign-header-footer.php` (da git).
2. Rimettere in bozza le pagine nuove, ripubblicare le vecchie riportando lo slug originale.
3. Ripristinare homepage, menu e impostazioni Customizer dall'export.
4. Svuotare le cache.

### Dopo qualche giorno senza problemi
- [ ] Eliminare le pagine vecchie.
- [ ] Rimuovere moduli e shortcode vecchi, SASS e CSS vecchi, Owl Carousel se non più usato.
- [ ] Rimuovere la logica "solo utenti loggati" (`gug_redesign_is_active()`) e rinominare i file nuovi con nomi definitivi:
  - `modules/redesign-setup.php` → `modules/theme-assets.php`
  - `modules/redesign-pages.php` → `modules/page-options.php`
  - `modules/redesign-header-footer.php` → rimosso (header/footer configurati nel Customizer)
  - `sass-gug/` → `sass/` (dopo aver eliminato il SASS vecchio), `_blocksy-adapt.scss` → `_blocksy.scss` unito
  - prefisso funzioni `gug_redesign_*` → `gug_*`
- [ ] Disattivare/eliminare Essential Blocks e Qubely se non più usati da nessun contenuto.
- [ ] Valutare informativa privacy/cookie (non prevista nel mockup).
