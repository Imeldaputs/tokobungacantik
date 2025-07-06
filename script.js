function scrollMenu(direction) {
  const container = document.getElementById('menuScroll');
  const scrollAmount = 220; // Sesuaikan dengan lebar menu-item

  container.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });
}

// Ambil tanggal hari ini
const tanggal = new Date();
const tanggalFormatted = tanggal.toLocaleDateString('id-ID', {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
});

// Isi ke struk
document.getElementById('r_tanggal').textContent = tanggalFormatted;

// Chatbot logika tambahan
function handleKey(e) {
  if (e.key === 'Enter') sendMessage();
}

function sendMessage() {
  const input = document.getElementById("chat-input");
  const log = document.getElementById("chat-log");
  const userMsg = input.value.trim();
  if (!userMsg) return;

  log.innerHTML += `<div><strong>Anda:</strong> ${userMsg}</div>`;
  input.value = "";

  let reply = "Maaf, saya belum mengerti pertanyaan itu.";

  if (userMsg.toLowerCase().includes("buka") || userMsg.toLowerCase().includes("jam")) {
    reply = "Toko kami buka setiap hari dari jam 08.00 - 20.00 WIB 🌸";
  } else if (userMsg.toLowerCase().includes("alamat")) {
    reply = "Alamat kami: Jl. Mawar No. 123, Jakarta.";
  } else if (userMsg.toLowerCase().includes("harga")) {
    reply = "Harga bervariasi, mulai dari Rp50.000 tergantung jenis bunga.";
  } else if (userMsg.toLowerCase().includes("pengiriman")) {
    reply = "Kami menyediakan pengiriman same-day untuk area Jabodetabek.";
  } else if (
    userMsg.toLowerCase().includes("beli") ||
    userMsg.toLowerCase().includes("pesan") ||
    userMsg.toLowerCase().includes("order") ||
    userMsg.toLowerCase().includes("cara memesan") ||
    userMsg.toLowerCase().includes("mau beli")
  ) {
    reply = "Untuk membeli bunga, silakan pilih dari menu, lalu klik tombol 'Pesan Sekarang'. Kami akan proses segera!";
  }

  setTimeout(() => {
    log.innerHTML += `<div><strong>Bot:</strong> ${reply}</div>`;
    log.scrollTop = log.scrollHeight;
  }, 500);
}

