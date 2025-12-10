async function getJSON(url) {
  const res = await fetch(url);
  return await res.json();
}
function escapeHtml(str) {
  if (!str) return "";
  return String(str).replace(/[&<>"']/g, (s) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
  }[s]));
}
async function loadChartKategori() {
  const res = await getJSON("../api/stats-data.php");
  if (!res.success) return;
  new Chart(document.getElementById("chartKategori"), {
    type: "bar",
    data: {
      labels: res.kategori.map(x => x.kategori),
      datasets: [{
        label: "Jumlah Laporan",
        data: res.kategori.map(x => x.total),
        backgroundColor: "rgba(54,162,235,0.6)"
      }]
    }
  });
}
async function loadMap() {
  const map = L.map("map").setView([-5.435, 105.266], 13);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "Ã‚Â© OpenStreetMap"
  }).addTo(map);
  const res = await getJSON("../api/map-data.php");
  if (!res.success) return;
  res.data.forEach(item => {
    const lat = parseFloat(item.lat);
    const lng = parseFloat(item.lng);
    const marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(`
      <b>${escapeHtml(item.judul)}</b><br>
      Status: ${item.status}<br>
      Kategori: ${item.kategori}
    `);
  });
}
async function loadSummary() {
  const res = await getJSON("../api/map-data.php");
  if (!res.success) return;
  document.getElementById("totalReports").innerText = res.data.length;
  document.getElementById("newReports").innerText =
    res.data.filter(x => x.status === "baru").length;
  document.getElementById("doneReports").innerText =
    res.data.filter(x => x.status === "selesai").length;
}
window.onload = () => {
  loadChartKategori();
  loadMap();
  loadSummary();
};