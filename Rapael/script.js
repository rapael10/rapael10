// Typing animation in Hero
const phrases = [
  "whoami --rafael",
  "nmap -sV smk-bangun-nusa-bangsa.lab",
  "systemctl status network-security",
  "echo 'Ethical Hacker & Server Specialist'"
];

const typingEl = document.getElementById("typingText");
let phraseIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeLoop() {
  if (!typingEl) return;
  const current = phrases[phraseIndex];

  if (isDeleting) {
    typingEl.textContent = current.substring(0, charIndex - 1);
    charIndex--;
  } else {
    typingEl.textContent = current.substring(0, charIndex + 1);
    charIndex++;
  }

  let speed = isDeleting ? 35 : 65;

  if (!isDeleting && charIndex === current.length) {
    speed = 1600;
    isDeleting = true;
  } else if (isDeleting && charIndex === 0) {
    isDeleting = false;
    phraseIndex = (phraseIndex + 1) % phrases.length;
    speed = 350;
  }

  setTimeout(typeLoop, speed);
}

typeLoop();

// Mobile menu toggle
const menuToggle = document.getElementById("menuToggle");
const navLinks = document.querySelector(".nav-links");

if (menuToggle && navLinks) {
  menuToggle.addEventListener("click", () => {
    navLinks.classList.toggle("open");
  });

  navLinks.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => navLinks.classList.remove("open"));
  });
}

// Interactive Terminal
const terminalForm = document.getElementById("terminalForm");
const terminalInput = document.getElementById("terminalInput");
const terminalOutput = document.getElementById("terminalOutput");

const commands = {
  help: [
    "Daftar perintah yang tersedia:",
    "  whoami   - Informasi singkat tentang Rafael",
    "  school   - Informasi sekolah",
    "  skills   - Daftar keahlian utama",
    "  projects - Ringkasan proyek & lab",
    "  clear    - Bersihkan layar terminal"
  ],
  whoami: [
    "Rafael | Ethical Hacker, Network Engineer & Server Administrator",
    "Fokus: Mengamankan jaringan, mengelola server Linux, dan audit sistem."
  ],
  school: [
    "Sekolah: SMK Bangun Nusa Bangsa",
    "Bidang : Teknik Jaringan Komputer, Server & Keamanan Siber"
  ],
  skills: [
    "[+] Jaringan : MikroTik, Cisco, VLAN, OSPF, Firewall, VPN",
    "[+] Server   : Ubuntu/Debian, Nginx, Docker, SSH Hardening",
    "[+] Security : Nmap, Wireshark, Burp Suite, Metasploit, OWASP",
    "[+] Script   : Bash, Python, PowerShell"
  ],
  projects: [
    "1. Segmentasi VLAN & Firewall Berlapis (Lab Jaringan)",
    "2. Hardened Linux Web Server & Reverse Proxy",
    "3. Audit Keamanan & Vulnerability Scanning Report"
  ]
};

function appendLine(text, className = "") {
  const div = document.createElement("div");
  div.className = `term-line ${className}`.trim();
  div.textContent = text;
  terminalOutput.appendChild(div);
}

if (terminalForm && terminalInput && terminalOutput) {
  terminalForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const raw = terminalInput.value.trim();
    if (!raw) return;

    const cmd = raw.toLowerCase();
    appendLine(`guest@rafael:~$ ${raw}`, "accent");

    if (cmd === "clear") {
      terminalOutput.innerHTML = "";
    } else if (commands[cmd]) {
      commands[cmd].forEach((line) => appendLine(line));
    } else {
      appendLine(`Perintah tidak dikenal: '${raw}'. Ketik 'help' untuk bantuan.`, "muted");
    }

    terminalInput.value = "";
    terminalOutput.scrollTop = terminalOutput.scrollHeight;
  });
}

// Footer year
const yearEl = document.getElementById("year");
if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}
