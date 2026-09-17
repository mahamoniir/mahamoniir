/**
 * Short Circuit Company — NFC Profile Card
 *
 * Data source:
 *   Currently reads from /data/profile.json (static, works on GitHub Pages —
 *   GitHub Pages cannot run PHP/MySQL, so there is no live backend here).
 *
 *   When this is later deployed to a real PHP + MySQL host, change
 *   DATA_ENDPOINT below to "backend/api/get_profile.php" and nothing else
 *   in this file needs to change — the PHP endpoint returns the same JSON
 *   shape. See /backend/README inside backend/ for the switch-over steps.
 */

const DATA_ENDPOINT = "data/profile.json";
// const DATA_ENDPOINT = "backend/api/get_profile.php"; // <-- use this once a real PHP/MySQL host is live

async function loadProfile() {
  const root = document.getElementById("app");
  try {
    const res = await fetch(DATA_ENDPOINT, { cache: "no-store" });
    if (!res.ok) throw new Error(`Request failed: ${res.status}`);
    const data = await res.json();
    render(data);
  } catch (err) {
    root.innerHTML = `<div class="state">Couldn't load profile data. (${err.message})</div>`;
    console.error(err);
  }
}

function render(data) {
  const { person, contact, links } = data;
  const root = document.getElementById("app");

  // Linktree-style stack: avatar, name/title, then every action as one
  // full-width link-button, in a single vertical list.
  const buttons = [
    { label: "Call", href: `tel:${stripSpaces(contact.phone)}` },
    { label: "WhatsApp", href: `https://wa.me/${stripPlusAndSpaces(contact.whatsapp)}` },
    { label: "Email", href: `mailto:${contact.email}` },
    ...links.map(l => ({ label: l.label, href: l.url, external: true })),
  ];

  root.innerHTML = `
    <div class="wrap">
      <img class="logo" src="https://shortcircuit.company/assets/img/logo-dark.svg" alt="Short Circuit Company">

      <div class="hero">
        <img class="hero__photo" src="${escapeAttr(person.photo)}" alt="${escapeAttr(person.name)}"
             onerror="this.src='assets/img/photo-placeholder.svg'">
        <h1 class="hero__name">${escapeHtml(person.name)}</h1>
        <p class="hero__title">${escapeHtml(person.title)}</p>
      </div>

      <div class="linklist">
        ${buttons.map(b => `
          <a class="linkbtn" href="${escapeAttr(b.href)}" ${b.external ? 'target="_blank" rel="noopener"' : ""}>
            ${escapeHtml(b.label)}
          </a>
        `).join("")}
        <button class="linkbtn linkbtn--primary" id="save-contact-btn" type="button">Save Contact</button>
      </div>

      <p class="footer">SHORT CIRCUIT COMPANY</p>
    </div>
  `;

  document.getElementById("save-contact-btn").addEventListener("click", () => downloadVCard(person, contact));
}

function downloadVCard(person, contact) {
  const vcard = [
    "BEGIN:VCARD",
    "VERSION:3.0",
    `N:${person.name};;;`,
    `FN:${person.name}`,
    `ORG:${person.company}`,
    `TITLE:${person.title}`,
    `TEL;TYPE=CELL:${contact.phone}`,
    `EMAIL:${contact.email}`,
    "END:VCARD"
  ].join("\n");

  const blob = new Blob([vcard], { type: "text/vcard" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = `${person.name.replace(/\s+/g, "_")}.vcf`;
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

function stripSpaces(s) { return (s || "").replace(/\s+/g, ""); }
function stripPlusAndSpaces(s) { return (s || "").replace(/[\s+]/g, ""); }

function escapeHtml(str) {
  return String(str ?? "").replace(/[&<>"']/g, ch => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
  }[ch]));
}
function escapeAttr(str) { return escapeHtml(str); }

document.addEventListener("DOMContentLoaded", loadProfile);
