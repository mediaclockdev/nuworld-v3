function tableToCSV(tableId) {
  const table = document.getElementById(tableId);
  const rows = Array.from(table.querySelectorAll('tr'));
  const csv = [];

  rows.forEach(row => {
    const cells = Array.from(row.querySelectorAll('th, td'));
    const rowData = cells.map(cell => cell.innerText);
    csv.push(rowData.join(','));
  });

  return csv.join('\n');
}

function exportTable(tableId, filename) {
  const csvData = tableToCSV(tableId);
  const blob = new Blob([csvData], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);

  const a = document.createElement('a');
  a.href = url;
  a.download = filename + '.csv';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}
