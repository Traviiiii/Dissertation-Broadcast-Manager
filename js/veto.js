let vetoStages = []
let maps = []

const query = 'https://r6-game-data-api.onrender.com/map/get/all'

fetch(query)
  .then(response => response.json())
  .then(data => {
    maps = data;
    loadVeto();
  })
  .catch(error => console.error('Error fetching maps:', error));

  function addStage(map = '', team = '', action = '', status = '') {
    if (maps.length === 0) {
      return;
    }
  
    let stageId = `${vetoStages.length}`;
  
    let stageCard = `
      <div class="card p-2">
          <div id="${stageId}" class="d-flex gap-2">
              <select id="map" name="map[]" class="form-select">
                  ${maps
                    .map(m => `<option value="${m.img}" ${m.img === map ? 'selected' : ''}>${m.name}</option>`)
                    .join('')}
              </select>
              <select id="team" name="team[]" class="form-select">
                  <option value="teama" ${team === 'teama' ? 'selected' : ''}>Team A</option>
                  <option value="teamb" ${team === 'teamb' ? 'selected' : ''}>Team B</option>
                  <option value="server" ${team === 'server' ? 'selected' : ''}>Server</option>
              </select>
              <select id="action" name="action[]" class="form-select">
                  <option value="ban" ${action === 'ban' ? 'selected' : ''}>Ban</option>
                  <option value="pick" ${action === 'pick' ? 'selected' : ''}>Pick</option>    
                  <option value="decider" ${action === 'decider' ? 'selected' : ''}>Decider</option>
              </select>
            <input type="text" name="status[]" placeholder="Status (Ignore if map not used)" class="form-control" value="${status}">
            <button type="button" class="btn btn-danger" onclick="removeStage('${stageId}')">Remove</button>
          </div>
      </div>
    `;
  
    document
      .getElementById('vetoContainer')
      .insertAdjacentHTML('beforeend', stageCard);
  
    vetoStages.push({ stageId, map, team, action, status });
  }
  
function removeStage (stageId) {
  document.getElementById(stageId).remove()
  vetoStages = vetoStages.filter(stage => stage.stageId !== stageId)
}

function saveVeto () {
  let formData = new FormData(document.getElementById('vetoForm'))
  let vetoData = []
  let maps = formData.getAll('map[]')
  let teams = formData.getAll('team[]')
  let actions = formData.getAll('action[]')
  let status = formData.getAll('status[]')

  for (let i = 0; i < maps.length; i++) {
    vetoData.push({ map: maps[i], team: teams[i], action: actions[i], status: status[i] })
  }

  fetch(window.location.href, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      vetoData: vetoData
    })
  })
}