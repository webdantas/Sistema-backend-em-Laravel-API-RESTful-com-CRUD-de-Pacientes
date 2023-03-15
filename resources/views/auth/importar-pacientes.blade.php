<template>
    <div class="container">
      <h1>Importar Planilha de Pacientes</h1>
      <form @submit.prevent="importCsv" enctype="multipart/form-data">
        <div class="form-group">
          <label for="csv">Selecione um arquivo CSV:</label>
          <input type="file" id="csv" ref="csv" accept=".csv">
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Importar</button>
        </div>
      </form>
      <div v-if="showMessage" class="alert alert-success" role="alert">
        {{ message }}
      </div>
      <div v-if="showError" class="alert alert-danger" role="alert">
        {{ error }}
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        showMessage: false,
        showEror: false,
        message: '',
        error: ''
      }
    },
    methods: {
      importCsv() {
        const formData = new FormData()
        formData.append('csv', this.$refs.csv.files[0])

        axios.post('/api/pacientes/import', formData)
          .then(response => {
            this.showMessage = true
            this.showEror = false
            this.message = 'Planilha importada com sucesso!'
          })
          .catch(error => {
            this.showMessage = false
            this.showError = true
            this.error = 'Erro ao importar a planilha: ' + error.response.data.message
          })
      }
    }
  }
  </script>
