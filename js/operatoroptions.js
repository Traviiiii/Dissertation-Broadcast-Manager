const query = 'https://r6-game-data-api.onrender.com/operator/get/all';

async function getOperatorOptions() {
  try {
    const response = await fetch(query);
    const data = await response.json();
    return data.map(op => `<option value="${op.id}">${op.name}</option>`).join('');
  } catch (error) {
    console.error('Error fetching operators:', error);
    return '';
  }
}

(async () => {
  const options = await getOperatorOptions();
  document.getElementById('operator').innerHTML = options;
})();
