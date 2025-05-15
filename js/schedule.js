let scheduleEvents = []

function addEvent (title = '', time = '', next = '') {
  let eventId = `${scheduleEvents.length}`

  let eventCard = `
    <div class="card p-2">
      <div id="${eventId}" class="d-flex gap-2">
        <div class="form-control border-0 p-0">
          <input type="text" name="title[]" placeholder="Event Name" class="form-control" value="${title}">
        </div>
        <div class="form-control border-0 p-0">
          <input type="time" name="time[]" placeholder="Time" class="form-control" value="${time}">
        </div>
        <div class="form-control border-0 d-flex align-items-center justify-content-center p-0">
          <input type="radio" name="next" ${next ? 'checked' : ''}>
        </div>
        <div class="form-control border-0 p-0">
          <button type="button" class="btn btn-danger" onclick="removeEvent('${eventId}')">Remove</button>
        </div>
      </div>
    </div>
  `

  document
    .getElementById('scheduleContainer')
    .insertAdjacentHTML('beforeend', eventCard)
  scheduleEvents.push({ eventId, title, time, next })
}

function removeEvent (eventId) {
  document.getElementById(eventId)?.remove()
  scheduleEvents = scheduleEvents.filter(event => event.eventId !== eventId)
}

function saveSchedule () {
  let formData = new FormData(document.getElementById('vetoForm'))
  let title = formData.getAll('title[]')
  let time = formData.getAll('time[]')
  let next = []

  document.querySelectorAll('[name="next"]').forEach(input => {
    next.push(input.checked ? input.value : '')
  })

  let eventData = []

  for (let i = 0; i < title.length; i++) {
    eventData.push({
      title: title[i],
      time: time[i],
      next: next[i]
    })
  }

  fetch(window.location.href, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ eventData })
  })
}
