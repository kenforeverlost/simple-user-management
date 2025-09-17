document.addEventListener('DOMContentLoaded', () => {
  const deleteBtn = document.getElementById('delete-btn')
  if (deleteBtn) {
    deleteBtn.addEventListener('click', (event) => {
      const currentTarget = event.currentTarget
      deleteUser(currentTarget.dataset.userId, currentTarget.dataset.csrfToken)
    })
  }
})

function deleteUser(id, csrf_token) {
  const confirm = window.confirm(
    'Are you sure you want to delete this user? This action cannot be undone.',
  )

  if (confirm) {
    const url = '/lib/classUsers.php'
    const formData = new FormData()
    formData.append('class', 'Users')
    formData.append('function', 'deleteUserById')
    formData.append('arguments', JSON.stringify([id, csrf_token]))

    fetch(url, {
      method: 'POST',
      body: formData,
    })
      .then((res) => res.json())
      .then((response) => {
        if (response.error) {
          alert(response.error)
        } else {
          window.location.href = '/users'
        }
      })
      .catch((error) => {
        console.error('Error:', error)
        alert(`Unexpected error. Try again later.`)
      })
  }
}
