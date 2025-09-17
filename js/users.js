document.addEventListener('DOMContentLoaded', () => {
  const textSearch = document.getElementById('text-search')
  let searchTimeout = null

  if (textSearch) {
    textSearch.addEventListener('input', (event) => {
      clearTimeout(searchTimeout)
      const searchTerm = event.currentTarget.value.toLowerCase().trim()

      searchTimeout = setTimeout(() => {
        const users = JSON.parse(sessionStorage.getItem('users'))

        if (users && users.length > 0) {
          const filteredUsers = users.filter((item) => {
            return (
              item.first_name.toLowerCase().includes(searchTerm) ||
              item.last_name.toLowerCase().includes(searchTerm) ||
              item.email.toLowerCase().includes(searchTerm)
            )
          })

          if (filteredUsers.length > 0) {
            tableContents.innerHTML = generateTableRowHtml(filteredUsers)
          } else {
            tableContents.innerHTML =
              '<tr><td colspan="6">No users found</td></tr>'
          }
        } else {
          tableContents.innerHTML =
            '<tr><td colspan="6">No users found</td></tr>'
        }
      }, 150)
    })
  }

  const tableContents = document.getElementById('table-contents')
  if (tableContents) {
    const url = '/lib/classUsers.php'
    const formData = new FormData()
    formData.append('class', 'Users')
    formData.append('function', 'getAllUsers')
    sessionStorage.removeItem('users')

    fetch(url, {
      method: 'POST',
      body: formData,
    })
      .then((res) => res.json())
      .then((response) => {
        if (response.error) {
          alert(`Error while fetching occurred: ${response.error}`)
        } else if (response.data.length > 0) {
          tableContents.innerHTML = generateTableRowHtml(response.data)
          sessionStorage.setItem('users', JSON.stringify(response.data))
        } else {
          tableContents.innerHTML =
            '<tr><td colspan="6">No users found</td></tr>'
        }
      })
      .catch((error) => {
        console.error('Error:', error)
        alert(`Unexpected error. Try again later.`)
      })
  }
})

function generateTableRowHtml(data) {
  let html = ''

  data.map((item) => {
    html += '<tr>'
    html += `<td>${escapeHtml(item.first_name)}</td>`
    html += `<td>${escapeHtml(item.last_name)}</td>`
    html += `<td>${escapeHtml(item.email)}</td>`
    html += `<td>${escapeHtml(item.phone)}</td>`
    html += `<td>${escapeHtml(item.created_at)}</td>`
    html += `<td><a href="/users/edit?id=${item.id}"><button type="button">Edit User</button></a></td>`
    html += '</tr>'
  })

  return html
}

function debounce(func, delay) {
  let timeout
  return function (...args) {
    const context = this
    clearTimeout(timeout)
    timeout = setTimeout(() => func.apply(context, args), delay)
  }
}

function escapeHtml(str) {
  return str.replace(/[&<>"']/g, (char) => {
    switch (char) {
      case '&':
        return '&amp;'
      case '<':
        return '&lt;'
      case '>':
        return '&gt;'
      case '"':
        return '&quot;'
      case "'":
        return '&#39;'
      default:
        return char
    }
  })
}
