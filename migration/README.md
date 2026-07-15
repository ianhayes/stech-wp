# STECH migration — redirects & IA

Authoritative source: `STECH-Migration-Audit-v3.csv` (client-approved 2026-05-20).
Regenerate these files with `scratchpad/gen_redirects.py`.

## Files
| File | Use |
|---|---|
| `redirects.csv` | Import into the **Redirection** plugin (Tools → Import). 270 × 301, `match=url`. |
| `redirect-map.php` | Theme safety-net map consumed by `inc/redirects.php` (fires only if the Redirection plugin is inactive). |
| `retired-no-target.txt` | Old URLs retired with no destination — decide 410 vs. redirect-to-parent. |

## Counts
- **270** old URLs change path → 301 to the new URL.
- **9** keep the same URL → no redirect needed.
- Retired-with-no-target: see the txt file (0 in v3 — every retire has a parent target).

> The audit summary cited ~202 redirects after collapsing prefix-based moves into
> wildcard rules (`/admissions/*`→`/admissions-aid/*`, `/students/*`→`/student-resources/*`,
> `/highschool/*`→`/high-school/*`, `/programs/health/*`→ health-sciences/first-responders).
> This export is the safe, explicit per-URL superset (270). If you prefer fewer
> rules, replace the prefix families with regex redirects in the plugin and keep
> the listed exceptions.

## New IA (nav)
Primary: Programs · Admissions & Aid · Student Resources · Community · About · Get Started (CTA).
High School is top-level but not in the 6-item main nav (flag: nav still labels the
section "Student Life" in some comps — confirm with client).

## Open items to confirm with client
- "Student Life" vs "Student Resources" label.
- Tuition tables: Google-Sheet/iframe embed vs. native price-table block.
- Retire vs. 410 policy for dead-end URLs.
