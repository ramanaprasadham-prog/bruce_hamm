# Bruce Hamm Site — Session Notes (June 23, 2026)

Summary of work, decisions, and deployment details from today's session.

---

## Project location

`C:\Users\Raman\Projects\path_of_raga`

Static HTML/CSS/JS revamp of [brucehamm.com](https://brucehamm.com). Local files remain the source of truth; Netlify hosts the public copy.

---

## Site pages

| Page | File |
|------|------|
| Home | `index.html` |
| Biography | `biography.html` |
| Gallery | `gallery.html` |
| Music | `music.html` |
| Videos | `videos.html` |
| Events | `events.html` |
| Lessons | `lessons.html` |
| Contact | `contact.html` |

---

## Design & theme

- **Current theme:** Professional dark charcoal (`#101215`) with gold accents (`#c9a227`)
- **Fonts:** Cormorant Garamond (headings), Source Sans 3 (body)
- **Removed:** Rainbow/cyan/purple gradients, Syne/Outfit fonts
- **Reverted:** Lighter palette experiment — back to original professional colors

### Homepage

- Full hero restored: eyebrow, **“The Art of Sarod and Sitar”**, intro, CTA buttons, stat cards (1974, 40+, ∞)
- Hero photo: `images/homepage-hero.png` (Bruce on sarod with sitar player)
- Briefly reverted to WordPress-style simple intro — user asked to bring everything back

---

## Events page

Two sections:

1. **Upcoming Events** — “Coming soon” placeholder with link to contact
2. **Past Events** — two posters in a side-by-side row:
   - **Path of Raga** — June 13, 2026 · Sri Krishna Balaram Mandir, Milpitas (`images/events/path-of-raga.png`)
   - **Joyful Summer Evening of Raga** — May 16, 2026 · Blacow, Fremont (`images/events/joyful-summer-evening-of-raga.png`)

---

## Music (reference)

**Morning Ragas:** 3 playable tracks in `audio/`

**Other Tracks:** 5 playable MP3s

**Evening Ragas:** “Coming soon” text only

Only one audio track plays at a time (`initExclusiveAudio` in `js/main.js` / `js/app.js`).

---

## Contact form (Formspree)

- **Service:** [Formspree](https://formspree.io)
- **Form endpoint:** `https://formspree.io/f/maqgqnee`
- **Config file:** `js/site-config.js` → `formspreeFormId: 'maqgqnee'`
- **Notification email:** Set in Formspree dashboard (ramanaprasadham@gmail.com)
- **Behavior:** AJAX submit, success/error messages on page, honeypot spam field
- **Note:** Legacy email URL (`formspree.io/email@...`) does not work — must use form ID

After redeploying, test the contact form on the **live HTTPS URL**.

---

## Public deployment (Netlify)

### Live site

- **Platform:** Netlify Drop
- **Site name:** `fascinating-shortbread-0b58bb`
- **URL:** `https://fascinating-shortbread-0b58bb.netlify.app`
- **Deploy method:** Drag `deploy/` folder to [app.netlify.com/drop](https://app.netlify.com/drop) or site Deploys page

### Update the live site

```powershell
powershell -File C:\Users\Raman\Projects\path_of_raga\scripts\prepare-deploy.ps1
```

Then drag `C:\Users\Raman\Projects\path_of_raga\deploy` onto Netlify.

### Claim & manage

Create a free Netlify account and claim the site so it isn’t lost.

### Change domain

**Nicer Netlify URL:** Domain management → Edit site name (e.g. `brucehamm.netlify.app`)

**Custom domain (brucehamm.com):** Domain management → Add domain → update DNS at registrar (A record + CNAME for www) → enable HTTPS in Netlify

### Take site offline

Delete the site in Netlify dashboard (see steps below). Local project files are unchanged.

**To unpublish (June 23, 2026):**

1. Open [Netlify site settings](https://app.netlify.com/sites/fascinating-shortbread-0b58bb/settings/general)
2. Scroll to **Danger zone**
3. Click **Delete site** → confirm

The URL `https://fascinating-shortbread-0b58bb.netlify.app` will stop working. Redeploy anytime by dragging `deploy/` to Netlify Drop again.

---

## Git & alternate deploy (not used yet)

- Git repo initialized on `main` with initial commit
- GitHub Pages workflow: `.github/workflows/pages.yml`
- GitHub CLI login was started but not completed
- To publish via GitHub later:

```powershell
gh auth login
powershell -File scripts/publish-github.ps1
```

---

## Local preview

```powershell
cd C:\Users\Raman\Projects\path_of_raga
python -m http.server 8765
```

Open `http://localhost:8765`. YouTube embeds and Formspree work best over HTTP(S), not `file://`.

---

## Tests

```powershell
powershell -File scripts/run-tests.ps1
```

Last run: **89 tests passing** (Vitest + jsdom).

---

## Key files

| Purpose | Path |
|---------|------|
| Styles | `css/styles.css` |
| App logic (tests) | `js/app.js` |
| Browser script | `js/main.js` |
| Formspree config | `js/site-config.js` |
| Deploy bundle script | `scripts/prepare-deploy.ps1` |
| Netlify config | `netlify.toml` |
| Deploy output (gitignored) | `deploy/` |

---

## Not done / future

- [ ] Claim Netlify site under an account
- [ ] Rename Netlify subdomain or point **brucehamm.com** DNS to Netlify
- [ ] Add real upcoming events when scheduled
- [ ] Evening Ragas audio when available
- [ ] Migrate any remaining WordPress content
- [ ] GitHub Pages deploy (optional alternative to Netlify)

---

## Q&A from today

**Can the site be public and still work as local files?**  
Yes. Publishing uploads a copy. Local folder stays editable; redeploy when ready; unpublish on Netlify anytime.

**How does the owner get contact form notifications?**  
Formspree emails submissions to the address configured in the Formspree dashboard.

**Surge / GitHub deploy attempts:**  
Surge needed interactive login. GitHub device login timed out. Netlify Drop succeeded.
