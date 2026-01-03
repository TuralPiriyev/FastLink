(() => {
  const navLinks = document.querySelector('.nav-links');
  const burger = document.getElementById('burger');

  burger?.addEventListener('click', () => {
    navLinks?.classList.toggle('active');
  });

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href')?.slice(1);
      const target = targetId ? document.getElementById(targetId) : null;
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
        navLinks?.classList.remove('active');
      }
    });
  });

  // Contact form stub submit
  const contactForm = document.getElementById('contactForm');
  const contactMsg = document.getElementById('contactMsg');
  contactForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    contactMsg.textContent = '';
    const formData = new FormData(contactForm);
    try {
      const res = await fetch(contactForm.action, { method: 'POST', body: formData });
      if (!res.ok) throw new Error('Network');
      const text = await res.text();
      contactMsg.textContent = text.trim() || 'OK';
    } catch (err) {
      contactMsg.textContent = 'Göndərmək alınmadı. Yenidən cəhd edin.';
    }
  });
})();
