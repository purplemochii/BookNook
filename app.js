//app.js has all js functions, whether they work or not. hopefully they all work like theyre supposed to💀🙏🏾
//i rly tried my best to not basically use a js framework but lookie what happened in the end lol

//fetched books variable
let BOOKS = [];
  
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
  
    // common: wire up nav active state
    const path = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav-links a').forEach(a=>{
        if(a.getAttribute('href') === path || (path==='' && a.getAttribute('href')==='index.html')){
            a.classList.add('active');
        } else a.classList.remove('active');
    });
  
    if(page==='home' || path==='home.html'){
        setupIndex();
    } else if(page==='browse' || path==='browse.html'){
        setupBrowse();
    } else if(page==='readlist' || path==='readlist.html'){
        setupReadlist();
    } else if(page==='bookshelf' || path==='bookshelf.html'){
        setupBookshelf();
    } else if(page==='index' || path==='index.html' || path===''){
        setupLogin(); 
    } else if(page==='payment' || path==='payment.html'){
        setupPayment();
    }
});
  
/*home page*/
function setupIndex(){
   //nothing to see here...*tumbleweed*
}
  
/*browse page*/
function setupBrowse(){
    const grid = document.getElementById('booksGrid');
    const search = document.getElementById('searchInput');
    const genre = document.getElementById('genreFilter');
    const author = document.getElementById('authorFilter');

    // fetch book data using php
    async function fetchBooks(){
        const response = await fetch('browse.php');
        BOOKS = await response.json();
        applyFilters();
    }
  
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
  
    // initial render
    applyFilters();
    // listeners
    if(search) search.addEventListener('input', applyFilters);
    if(genre) genre.addEventListener('change', applyFilters);
    if(author) author.addEventListener('change', applyFilters);

    fetchBooks();
}
  
/*readlist page*/
function setupReadlist(){
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
      const books = ids.map(id=> BOOKS.find(b=>b.id===id)).filter(Boolean);
      books.forEach(b=>{
        const card = createCard(b, {
            label: 'Buy Now',
            onClick: (book)=>{
                localStorage.setItem('selectedBook', JSON.stringify(book));
                localStorage.setItem("fromReadList", "true");
                window.location.href = 'payment.html';                
            }
        });
        grid.appendChild(card);
      });
      if(countEl) countEl.textContent = `${books.length} books total`;
    }
    render();
}
  
/*bookshelf page*/
function setupBookshelf(){
    const grid = document.getElementById('bookshelf-container') || document.getElementById('booksGrid');
    const countEl = document.getElementById('book-count');
  
    grid.innerHTML = '';
    const ids = storage.get('bookshelf');
    if(!ids.length){
        grid.innerHTML = '<div class="empty">Your bookshelf is empty — add books from Readlist.</div>';
        if(countEl) countEl.textContent = '0 books total';
        return;
    }
    const books = ids.map(id=> BOOKS.find(b=>b.id===id)).filter(Boolean);
    books.forEach(b=>{
        const card = createCard(b, {
            label: 'Owned',
            onClick: ()=>{}
        });
        grid.appendChild(card);
    });
    if(countEl) countEl.textContent = `${books.length} books total`;
}
  
/*payment page*/
function setupPayment() {
    const book = JSON.parse(localStorage.getItem("selectedBook"));
    if (book) {
        document.getElementById("payCover").src = book.img;
        document.getElementById("payTitle").textContent = book.title;
        document.getElementById("payAuthor").textContent = book.author;
        document.getElementById("payGenre").textContent = book.genre;
        document.getElementById("payPages").textContent = book.pages;
        document.getElementById("payYear").textContent = "Published " + book.year;

        const subtotal = book.price;
        const tax = +(subtotal * 0.10).toFixed(2);
        const total = +(subtotal + tax).toFixed(2);

        document.getElementById("subtotal").textContent = "$" + subtotal;
        document.getElementById("tax").textContent = "$" + tax;
        document.getElementById("total").textContent = "$" + total;
    }

        // On purchase → add to bookshelf
    document.getElementById("completePurchase").onclick = () => {
        storage.pushUnique('bookshelf', book.id);
        window.location.href = "bookshelf.html";
    }

    localStorage.removeItem("fromReadList");
    localStorage.removeItem("selectedBook");

}


/*login and signup page*/
function setupLogin() {
    debugger;
    console.log("WORK PLEASE");
    const loginTab = document.getElementById("loginTab");
    const signupTab = document.getElementById("signupTab");
    const loginForm = document.getElementById("loginForm");
    const signupForm = document.getElementById("signupForm");

    loginTab.onclick = () => {
        loginTab.classList.add("active");
        signupTab.classList.remove("active");
        loginForm.classList.add("active");
        signupForm.classList.remove("active");
    }

    signupTab.onclick = () => {
        signupTab.classList.add("active");
        loginTab.classList.remove("active");
        signupForm.classList.add("active");
        loginForm.classList.remove("active");
    }
}