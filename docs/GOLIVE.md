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
- [ ] Export impostazioni Customizer di Blocksy (backup header/footer attuali).
- [ ] Tutte le pagine nuove controllate in anteprima: desktop, tablet (< 1000px), mobile (< 690px), stampa giuria.
- [ ] Nessun avviso "Blocco con contenuto non valido" nell'editor sulle pagine nuove.
- [ ] Link documenti segnaposto (`/wp-content/uploads/...`) sostituiti con i file reali.
- [ ] Scegliere un momento di basso traffico (non a ridosso dell'uscita delle designazioni).

### Durante
- [ ] Rinominare lo slug di ogni pagina vecchia (es. `calendari` → `calendari-old`) **prima** di metterla in bozza e pubblicare la nuova: altrimenti WordPress pubblica la nuova come `calendari-2`.
- [ ] Mettere in bozza le pagine vecchie, pubblicare le nuove con titolo e slug corretti.
- [ ] Impostazioni → Lettura: homepage = nuova pagina Home.
- [ ] Menu: ricollegare le voci alle pagine nuove (i menu puntano all'ID) e impostare le classi `gug-sector--xx`.
- [ ] Header e footer: configurarli nel Customizer di Blocksy come li vedono oggi i loggati (struttura in `modules/redesign-header-footer.php`), poi rimuovere quel modulo. Gli stili in `sass-gug/_blocksy-adapt.scss` restano validi.
  - Footer: 3 colonne (marchio, contatti, "Seguici"). Nei social del footer solo Facebook e Instagram: email e telefono sono già nei contatti (oggi lo shortcode `[icone-social]` li esclude in automatico).
- [ ] Attivare il redesign per tutti (togliere la condizione "solo utenti loggati" per CSS e template).
- [ ] Svuotare **LiteSpeed Cache** e **Fast Velocity Minify** (e la cache del browser).

### Subito dopo
- [ ] Navigazione da utente **non loggato** (finestra anonima): home, calendari, ogni settore, designazioni, singola designazione + stampa, regolamenti, modulistica, formazione, presentazione, 404.
- [ ] Console del browser senza errori JS; nessun asset 404.
- [ ] Vecchi URL ancora raggiungibili (slug invariati) e link interni funzionanti.

### Rollback
1. Ripristinare la condizione "solo utenti loggati".
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
