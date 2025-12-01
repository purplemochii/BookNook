// app.js — where all my hopes, dreams, and js spaghetti live 🍝😭
// pls work… pls 🧎🏾‍♀️
// (i avoided frameworks like a champ but at what cost)

//fetched books variable
let BOOKS = [];

async function fetchBooks(){
    if(BOOKS.length == 0){
        const response = await fetch('browse.php');
        BOOKS = await response.json();
    }
}
  
  // saving all to local storage for now 
const storage = {
    get(k){ try{ return JSON.parse(localStorage.getItem(k)) || []; }catch{ return [] } },
    set(k,v){ localStorage.setItem(k, JSON.stringify(v)) },
    pushUnique(k, id){
      const arr = storage.get(k);
      if(!arr.includes(id)) { arr.push(id); storage.set(k,arr) }
    },
    remove(k,id){
      const arr = storage.get(k).filter(i=>i!==id);
      storage.set(k,arr);
    }
}
  // render helpers
function createCard(book, opts={}){
    const el = document.createElement('article');
    el.className = 'card';
    el.dataset.id = book.id;
    el.innerHTML = `
      <img class="card__cover" src="${book.img}" alt="${escapeHtml(book.title)} cover" onerror="this.style.background='#ddd'; this.src=''">
      <div class="card__body">
        <div>
            <div class="title">${escapeHtml(book.title)}</div>
            <div class="author">${escapeHtml(book.author)}</div>
            <div class="meta-row">
                <span class="badge">${escapeHtml(book.genre)}</span>
                <span class="kv">${book.year}</span>
            </div>
        </div>
        <button class="action">${escapeHtml(opts.label || 'Add')}</button>
      </div>
    `;
    // action handler
    const btn = el.querySelector('.action');
    btn.addEventListener('click', ()=>{
        if(opts.onClick) opts.onClick(book);
    });
    return el;
}
  
function escapeHtml(s){ 
    return String(s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;') 
}
  
  // page-specific setups
document.addEventListener('DOMContentLoaded', ()=>{
    const page = document.body.dataset.page || document.body.id || '';
    const path = window.location.pathname.split('/').pop();

    // highlight active nav link (very aesthetic, very necessary ✨)
    document.querySelectorAll('.nav-links a').forEach(a => {
        const href = a.getAttribute('href');
        if (href === path || (path === '' && href === 'index.html')) {
            a.classList.add('active');
        }
    });

    // page routing because i'm basically building a micro-framework at this point 😭
    if (page === 'home' || path === 'home.html') setupIndex();
    else if (page === 'browse' || path === 'browse.html') setupBrowse();
    else if (page === 'readlist' || path === 'readlist.html') setupReadlist();
    else if (page === 'bookshelf' || path === 'bookshelf.html') setupBookshelf();
    else if (page === 'index' || path === 'index.html' || path === '') setupLogin();
    else if (page === 'payment' || path === 'payment.html') setupPayment();
});

/* HOME PAGE */
function setupIndex() {
    // literally nothing here… like my energy at 3AM 😭
    // *tumbleweed*
}
  
/*browse page*/
async function setupBrowse(){
    const grid = document.getElementById('booksGrid');
    const search = document.getElementById('searchInput');
    const genre = document.getElementById('genreFilter');
    const author = document.getElementById('authorFilter');
    
    function render(filtered){
        grid.innerHTML = '';
        if(filtered.length===0){
            grid.innerHTML = '<div class="empty">No books found</div>'; return;
        }
        filtered.forEach(b=>{
        const card = createCard(b, {
            label: 'Add to Readlist',
            onClick: (book)=>{
                storage.pushUnique('readlist', book.id);
                alert(`Added "${book.title}" to your Readlist`);
            }
        });
        grid.appendChild(card);
      });
    }
  
    function applyFilters(){
        const q = (search?.value||'').trim().toLowerCase();
        const g = (genre?.value||'').trim();
        const a = (author?.value||'').trim();
        let res = BOOKS.slice();
        if(q) res = res.filter(b=> (b.title+b.author).toLowerCase().includes(q));
        if(g) res = res.filter(b=> b.genre.toLowerCase().includes(g.toLowerCase()));
        if(a) res = res.filter(b=> b.author.toLowerCase().includes(a.toLowerCase()));
        render(res);
    }
    // listeners
    if(search) search.addEventListener('input', applyFilters);
    if(genre) genre.addEventListener('change', applyFilters);
    if(author) author.addEventListener('change', applyFilters);

    await fetchBooks();
    applyFilters();
}
  
/*readlist page*/
async function setupReadlist(){
    await fetchBooks();
    const grid = document.getElementById('booksGrid');
    const countEl = document.getElementById('book-count');
  
    function render(){
      grid.innerHTML = '';
      const ids = storage.get('readlist');
      if(!ids.length){
            grid.innerHTML = '<div class="empty">Your readlist is empty — find books on Browse.</div>';
            if(countEl) countEl.textContent = '0 books total';
            return;
        }
        list.forEach(book => grid.appendChild(createBrowseCard(book)));
    }
    render();
}
  
/*bookshelf page*/
async function setupBookshelf(){
    await fetchBooks();
    const grid = document.getElementById('bookshelf-container') || document.getElementById('booksGrid');
    const countEl = document.getElementById('book-count');
  
    grid.innerHTML = '';
    const ids = storage.get('bookshelf');
    if(!ids.length){
        grid.innerHTML = '<div class="empty">Your bookshelf is empty — add books from Readlist.</div>';
        if(countEl) countEl.textContent = '0 books total';
        return;
    }

    search.addEventListener('input', applyFilters);
    genre.addEventListener('change', applyFilters);
    author.addEventListener('change', applyFilters);

    loadBooks();
}

/* READLIST PAGE */
function setupReadlist() {
    const grid = document.getElementById('booksGrid');

    async function loadReadlist() {
        const response = await fetch('readlist.php');
        readlist = await response.json();
        render();
    }

    function render() {
        grid.innerHTML = '';
        if (readlist.length === 0) {
            grid.innerHTML = '<div class="empty">Your readlist is empty — go browse — be an intellectual </div>';
            return;
        }

        readlist.forEach(book => {
            const card = document.createElement('article');
            card.className = 'card';

            card.innerHTML = `
                <img class="card__cover" src="images/book${book.book_id}.jpg" alt="">
                <div class="card__body">
                    <div class="title">${book.title}</div>
                    <div class="author">${book.authors}</div>
                    <div class="meta-row">
                        <span class="badge">${book.genre_name}</span>
                    </div>
                </div>
                <button class="action">Purchase</button>
            `;

            card.querySelector(".action").onclick = () => {
                window.location.href = `payment.html?book_id=${book.book_id}`;
            };

            grid.appendChild(card);
        });
    }

    loadReadlist();
}

/* BOOKSHELF PAGE */
function setupBookshelf() {
    const grid = document.getElementById('booksGrid');

    async function loadBookshelf() {
        const response = await fetch('bookshelf.php');
        bookshelf = await response.json();
        render();
    }

    function render() {
        grid.innerHTML = '';
        if (bookshelf.length === 0) {
            grid.innerHTML = '<div class="empty">Nothing here yet… buy some books pls</div>';
            return;
        }

        bookshelf.forEach(book => {
            const card = document.createElement('article');
            card.className = 'card';

            card.innerHTML = `
                <img class="card__cover" src="images/book${book.book_id}.jpg" alt="">
                <div class="card__body">
                    <div class="title">${book.title}</div>
                    <div class="author">${book.authors}</div>
                    <div class="meta-row">
                        <span class="badge">${book.genre_name}</span>
                    </div>
                    <button class="action owned">Owned ✔️</button>
                </div>
            `;

            grid.appendChild(card);
        });
    }

    loadBookshelf();
}

/* PAYMENT PAGE */
function setupPayment() {
    const params = new URLSearchParams(window.location.search);
    const book_id = params.get('book_id');

    async function loadBook() {
        const response = await fetch(`details.php?id=${book_id}`);
        const book = await response.json();
        fillUI(book);
    }

    function fillUI(book) {
        document.getElementById("payCover").src = `images/book${book.book_id}.jpg`;
        document.getElementById("payTitle").textContent = book.title;
        document.getElementById("payAuthor").textContent = book.authors;
        document.getElementById("payGenre").textContent = book.genre_name;
        document.getElementById("payYear").textContent = "Published " + book.year;

        document.getElementById("subtotal").textContent = "$" + book.price;
        document.getElementById("tax").textContent = "$" + (book.price * 0.10).toFixed(2);
        document.getElementById("total").textContent = "$" + (book.price * 1.10).toFixed(2);

        document.getElementById("completePurchase").onclick = async () => {
            const response = await fetch('purchase.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ book_id }),
            });

            const output = await response.json();
            if (output.success) {
                window.location.href = 'bookshelf.html';
            } else {
                alert('Purchase failed — try again?');
            }
        };
    }

    loadBook();
}

/* LOGIN / SIGNUP PAGE */
function setupLogin() {
    console.log("login setup loading… pls behave 😭");

    const loginTab = document.getElementById("loginTab");
    const signupTab = document.getElementById("signupTab");
    const loginForm = document.getElementById("loginForm");
    const signupForm = document.getElementById("signupForm");

    loginTab.onclick = () => {
        loginTab.classList.add("active");
        signupTab.classList.remove("active");
        loginForm.classList.add("active");
        signupForm.classList.remove("active");
    };

    signupTab.onclick = () => {
        signupTab.classList.add("active");
        loginTab.classList.remove("active");
        signupForm.classList.add("active");
        loginForm.classList.remove("active");
    };
}
