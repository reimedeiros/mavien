class MavienSite {
  constructor() {
    this.header = document.querySelector(".site-header");
    this.contactForm = document.getElementById("contactForm");
    this.vagaFormBtn = document.getElementById("btn-candidatar");
    this.vagaForm = document.getElementById("form-candidatar");

    this.init();
  }

  init() {
    this.handleMenuToggle();
    this.handleSmoothScroll();
    this.handleScrollHeader();
    this.handleFormValidation();
    this.handleCandidatarForm();
  }

  handleMenuToggle() {
    const toggle = document.getElementById("nav-toggle");
    const nav = document.getElementById("main-nav");
    if (toggle && nav) {
      toggle.addEventListener("click", () => {
        toggle.classList.toggle("active");
        nav.classList.toggle("active");
      });
    }
  }

  handleSmoothScroll() {
    const headerHeight = this.header ? this.header.offsetHeight : 0;
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", (e) => {
        const targetId = anchor.getAttribute("href");
        const target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          const offsetTop = target.offsetTop - headerHeight;
          window.scrollTo({
            top: offsetTop,
            behavior: "smooth",
          });
        }
      });
    });
  }

  handleScrollHeader() {
    window.addEventListener("scroll", () => {
      if (!this.header) return;
      if (window.scrollY > 50) {
        this.header.classList.add("scrolled");
      } else {
        this.header.classList.remove("scrolled");
      }
    });
  }

  handleFormValidation() {
    if (!this.contactForm) return;

    this.contactForm.addEventListener("submit", (e) => {
      const cpfCnpj = document.getElementById("cpfCnpj")?.value.trim() || "";
      const whatsapp = document.getElementById("whatsapp")?.value.trim() || "";
      const humano = document.getElementById("humano")?.checked;

      if (!humano) {
        alert("Por favor, confirme que você é humano 😅");
        e.preventDefault();
        return;
      }

      const clean = cpfCnpj.replace(/\D/g, "");
      if (clean.length !== 11 && clean.length !== 14) {
        alert("Por favor, insira um CPF ou CNPJ válido.");
        e.preventDefault();
        return;
      }

      const whatsappRegex = /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/;
      if (!whatsappRegex.test(whatsapp)) {
        alert("Por favor, insira um número de WhatsApp válido.");
        e.preventDefault();
      }
    });
  }

  handleCandidatarForm() {
    if (!this.vagaFormBtn || !this.vagaForm) return;

    this.vagaFormBtn.addEventListener("click", () => {
      this.vagaForm.classList.toggle("hidden");
      this.vagaForm.scrollIntoView({ behavior: "smooth" });
    });
  }
}

document.addEventListener("DOMContentLoaded", () => new MavienSite());

class MavienForm {
  constructor(formId) {
    this.form = document.getElementById(formId);
    if (!this.form) return;

    this.cpfCnpjInput = this.form.querySelector('input[name="cpf_cnpj"]');
    this.whatsappInput = this.form.querySelector('input[name="whatsapp"]');
    this.humanoCheck = this.form.querySelector("#humano");

    this.init();
  }

  init() {
    this.bindMaskEvents();
    this.bindValidation();
  }

  // === Máscaras em tempo real ===
  bindMaskEvents() {
    if (this.cpfCnpjInput) {
      this.cpfCnpjInput.addEventListener("input", (e) => {
        e.target.value = this.formatCpfCnpj(e.target.value);
      });
    }

    if (this.whatsappInput) {
      this.whatsappInput.addEventListener("input", (e) => {
        e.target.value = this.formatWhatsapp(e.target.value);
      });
    }
  }

  // === Máscara CPF / CNPJ ===
  formatCpfCnpj(value) {
    value = value.replace(/\D/g, "");
    if (value.length <= 11) {
      // CPF: 000.000.000-00
      value = value
        .replace(/(\d{3})(\d)/, "$1.$2")
        .replace(/(\d{3})(\d)/, "$1.$2")
        .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    } else {
      // CNPJ: 00.000.000/0000-00
      value = value
        .replace(/^(\d{2})(\d)/, "$1.$2")
        .replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
        .replace(/\.(\d{3})(\d)/, ".$1/$2")
        .replace(/(\d{4})(\d)/, "$1-$2");
    }
    return value;
  }

  // === Máscara WhatsApp ===
  formatWhatsapp(value) {
    value = value.replace(/\D/g, "");
    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 10) {
      value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
    } else if (value.length > 6) {
      value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
    } else if (value.length > 2) {
      value = value.replace(/(\d{2})(\d{0,5})/, "($1) $2");
    } else if (value.length > 0) {
      value = value.replace(/(\d{0,2})/, "($1");
    }
    return value;
  }

  // === Validação do formulário ===
  bindValidation() {
    this.form.addEventListener("submit", (e) => {
      if (!this.validateForm()) {
        e.preventDefault();
      }
    });
  }

  validateForm() {
    const cpfCnpj = this.cpfCnpjInput?.value.trim() || "";
    const whatsapp = this.whatsappInput?.value.trim() || "";
    const humano = this.humanoCheck?.checked;

    // Confirmação humana
    if (!humano) {
      alert("Por favor, confirme que você é humano 😅");
      return false;
    }

    // Validação CPF/CNPJ (somente formato)
    const clean = cpfCnpj.replace(/\D/g, "");
    if (clean.length !== 11 && clean.length !== 14) {
      alert("Por favor, insira um CPF ou CNPJ válido.");
      return false;
    }

    // Validação WhatsApp
    const whatsappRegex = /^\(\d{2}\)\s?\d{4,5}-?\d{4}$/;
    if (!whatsappRegex.test(whatsapp)) {
      alert("Por favor, insira um número de WhatsApp válido.");
      return false;
    }

    return true;
  }
}

// Inicializa automaticamente ao carregar o DOM
document.addEventListener("DOMContentLoaded", () => {
  new MavienForm("contactForm");
  new MavienForm("form-candidatar");
});
