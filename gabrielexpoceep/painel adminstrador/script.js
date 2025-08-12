document.getElementById('editCard').addEventListener('click', () => showForm('Edição de Produto'));
document.getElementById('addCard').addEventListener('click', () => showForm('Cadastro de Produto'));
document.getElementById('deleteCard').addEventListener('click', () => showForm('Exclusão de Produto'));

function showForm(title) {
    document.getElementById('formContainer').style.display = 'flex';
    document.querySelector('.form').style.opacity = 1;
    document.getElementById('formTitle').textContent = title;
}

document.getElementById('productForm').addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Produto salvo com sucesso!');
    document.getElementById('productForm').reset();
});
