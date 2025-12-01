// app.js — where all my hopes, dreams, and js spaghetti live 🍝😭
// pls work… pls 🧎🏾‍♀️
// (i avoided frameworks like a champ but at what cost)

// Run setup depending on which page we’re on
document.addEventListener('DOMContentLoaded', () => {
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

/* BROWSE PAGE */
function setupBrowse() {
    const grid = document.getElementById('booksGrid');
    const search = document.getElementById('searchInput');
    const genre = document.getElementById('genreFilter');
    const author = document.getElementById('authorFilter');

    let books = [];

    async function loadBooks() {
        const response = await fetch('browse.php');
        books = await response.json();
        applyFilters();
    }

    // create book card
    function createBrowseCard(book) {
        const el = document.createElement('article');
        el.className = 'card';
        el.dataset.id = book.book_id;

        el.innerHTML = `
            <img class="card__cover" src="images/book${book.book_id}.jpg" alt="">
            <div class="card__body">
                <div>
                    <div class="title">${book.title}</div>
                    <div class="author">${book.authors}</div>
                    <div class="meta-row">
                        <span class="badge">${book.genre_name}</span>
                        <span class="price">$${book.price}</span>
                    </div>
                </div>
                <button class="action">Add to Readlist</button>
            </div>
        `;

        // add-to-readlist button
        el.querySelector(".action").onclick = async () => {
            await fetch('add-to-readlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ book_id: book.book_id }),
            });
            alert(`Added "${book.title}" to your Readlist!`);
        };

        return el;
    }

    function render(list) {
        grid.innerHTML = '';
        if (list.length === 0) {
            grid.innerHTML = '<div class="empty">No books found</div>';
            return;
        }
        list.forEach(book => grid.appendChild(createBrowseCard(book)));
    }

    function applyFilters() {
        let filtered = [...books];
        const q = (search.value || '').toLowerCase();
        const g = genre.value.toLowerCase();
        const a = author.value.toLowerCase();

        if (q) filtered = filtered.filter(b => (b.title + b.authors).toLowerCase().includes(q));
        if (g) filtered = filtered.filter(b => b.genre_name.toLowerCase().includes(g));
        if (a) filtered = filtered.filter(b => b.authors.toLowerCase().includes(a));

        render(filtered);
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
            grid.innerHTML = '<div class="empty">Your readlist is empty — go browse — be an intellectual</div>';
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
                    <button class="action owned">Owned</button>
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
