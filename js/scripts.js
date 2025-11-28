// js/scripts.js

// --- 1. Menu Responsivo ---
const navToggle = document.getElementById('nav-toggle');
const siteNav = document.getElementById('site-nav');
if (navToggle) {
    navToggle.addEventListener('click', () => siteNav.classList.toggle('open'));
}

// --- 2. Funções do Carrinho ---

// Atualiza o número no ícone do carrinho
function updateCartCounter() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cartCount.textContent = cart.length;
    }
}

// Adiciona produto (salva no LocalStorage)
function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push(product);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCounter();
    showModal(`"${product.nome}" adicionado ao carrinho!`);
}

///
// 1. A função de remover 
function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1); 
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
    updateCartCounter();
}

// 2. A função de renderizar 
function renderCart() {
    const cartContainer = document.getElementById('cart-items');
    const cartSummary = document.getElementById('cart-summary');
    const cartTotal = document.getElementById('cart-total');

    if (!cartContainer) return;

    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p>Seu carrinho está vazio. <a href="index.html">Voltar para a loja</a>.</p>';
        if (cartSummary) cartSummary.style.display = 'none';
        return;
    }

    let total = 0;
    cartContainer.innerHTML = cart.map((item, index) => {
        total += item.preco;
        return `
            <div class="cart-item">
                <img src="${item.imagem}" alt="${item.nome}" class="cart-thumb" onerror="this.src='https://placehold.co/80x80?text=Foto'">
                <div class="cart-info">
                    <h3>${item.nome}</h3>
                    <span class="price">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                </div>
                <button class="btn-remove" data-index="${index}" aria-label="Remover item">Remover 🗑️</button>
            </div>
        `;
    }).join('');

   
    if (cartTotal) cartTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
    if (cartSummary) cartSummary.style.display = 'block';

    // Adiciona os eventos de clique DEPOIS de criar o HTML
    cartContainer.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', (e) => {
            const indexToRemove = e.target.getAttribute('data-index');
            removeFromCart(indexToRemove);
        });
    });
}

// --- 3. Carregamento de Produtos (Página Inicial) ---
async function loadProducts() {
    const cardsContainer = document.getElementById('cards');
    if (!cardsContainer) return;

    try {
        const response = await fetch('data/products.json');
        const products = await response.json();

        cardsContainer.innerHTML = products.map(product => `
            <article class="card">
                <img src="${product.imagem}" alt="${product.nome}" onerror="this.src='https://via.placeholder.com/250?text=Imagem'">
                <h3>${product.nome}</h3>
                <p>${product.descricao}</p>
                <span class="price">R$ ${product.preco.toFixed(2).replace('.', ',')}</span>
                <button class="btn-buy" data-id="${product.id}">Comprar</button>
            </article>
        `).join('');

        // Adiciona eventos de clique aos botões "Comprar"
        document.querySelectorAll('.btn-buy').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                const product = products.find(p => p.id === id);
                addToCart(product);
            });
        });
    } catch (error) {
        console.error('Erro:', error);
        cardsContainer.innerHTML = '<p>Erro ao carregar produtos.</p>';
    }
}

// --- 4. Formulário de Checkout (contato.html) ---
export function initContactForm() {
    const form = document.querySelector('#contact-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        // ... (sua validação existente aqui se quiser manter) ...
        
        // Simulação de envio
        showModal('Pedido finalizado com sucesso! Obrigado.');
        localStorage.removeItem('cart'); // Limpa o carrinho
        updateCartCounter();
        form.reset();
        // Opcional: redirecionar para home após 2 segundos
        setTimeout(() => window.location.href = 'index.html', 2000);
    });
}

// --- 5. Modal Genérico ---
function showModal(text) {
    let modal = document.getElementById('app-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'app-modal';
        modal.className = 'modal';
        modal.innerHTML = `<div class="modal-content"><p></p><button id="close-modal">Fechar</button></div>`;
        document.body.appendChild(modal);
        modal.querySelector('#close-modal').addEventListener('click', () => modal.remove());
    }
    modal.querySelector('p').textContent = text;
    modal.style.display = 'block';
}

// Inicialização Geral
window.addEventListener('DOMContentLoaded', () => {
    updateCartCounter();
    loadProducts();
    renderCart();     // <-- Importante: Chama a renderização do carrinho
    initContactForm();
});