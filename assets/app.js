
const body = document.body;
const cards = document.querySelectorAll('.movie-card, .category-card, .floating-poster, .movie-detail-poster');
const previewStage = document.getElementById('previewStage');
const mobileToggle = document.querySelector('.mobile-nav-toggle');
const mainNav = document.querySelector('.main-nav');
const teaserModal = document.getElementById('teaserModal');
const teaserFrame = teaserModal?.querySelector('.teaser-modal__frame');
const teaserClose = teaserModal?.querySelector('.teaser-modal__close');
const teaserBackdrop = teaserModal?.querySelector('.teaser-modal__backdrop');

function applyTilt(card, x, y) {
  const rect = card.getBoundingClientRect();
  const centerX = rect.left + rect.width / 2;
  const centerY = rect.top + rect.height / 2;
  const rotateX = (y - centerY) / 18;
  const rotateY = (centerX - x) / 18;
  card.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
}

cards.forEach((card) => {
  card.addEventListener('mousemove', (event) => applyTilt(card, event.clientX, event.clientY));
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
  });
  card.addEventListener('mouseenter', () => {
    const backdrop = card.dataset.backdrop;
    const title = card.dataset.title;
    const tagline = card.dataset.tagline;
    const teaser = card.dataset.teaser;
    if (backdrop) {
      body.style.backgroundImage = `radial-gradient(circle at top left, rgba(76, 201, 240, 0.12), transparent 25%), radial-gradient(circle at top right, rgba(139, 92, 246, 0.16), transparent 22%), linear-gradient(rgba(5,8,22,.7), rgba(5,8,22,.92)), url(${backdrop})`;
      body.style.backgroundSize = 'auto, auto, cover';
      body.style.backgroundAttachment = 'fixed';
    }
    if (previewStage) {
      previewStage.style.backgroundImage = `url(${backdrop})`;
      previewStage.querySelector('h4').textContent = title || 'MovieVerse AI';
      previewStage.querySelector('p').textContent = tagline || 'Hover movie cards to preview details here.';
      const btn = previewStage.querySelector('.teaser-launch');
      if (btn && teaser) btn.dataset.teaser = teaser;
    }
  });
});

function openTeaser(url) {
  if (!teaserModal || !teaserFrame || !url) return;
  teaserFrame.innerHTML = `<iframe src="${url}" title="Movie teaser" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
  teaserModal.classList.add('open');
}

function closeTeaser() {
  if (!teaserModal || !teaserFrame) return;
  teaserModal.classList.remove('open');
  teaserFrame.innerHTML = '';
}

document.querySelectorAll('.teaser-launch').forEach((button) => {
  button.addEventListener('click', () => openTeaser(button.dataset.teaser));
});
teaserClose?.addEventListener('click', closeTeaser);
teaserBackdrop?.addEventListener('click', closeTeaser);
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeTeaser(); });
mobileToggle?.addEventListener('click', () => mainNav?.classList.toggle('open'));

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) entry.target.classList.add('visible');
  });
}, { threshold: 0.16 });

document.querySelectorAll('.section-block, .page-banner, .auth-card, .table-section, .stats-grid article, .movie-card, .category-card').forEach((el) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(30px)';
  el.style.transition = 'opacity .7s ease, transform .7s ease';
  observer.observe(el);
});

document.querySelectorAll('.visible').forEach((el) => {
  el.style.opacity = '1';
  el.style.transform = 'translateY(0)';
});

const visibilityObserver = new MutationObserver(() => {
  document.querySelectorAll('.visible').forEach((el) => {
    el.style.opacity = '1';
    el.style.transform = 'translateY(0)';
  });
});
visibilityObserver.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['class'] });
