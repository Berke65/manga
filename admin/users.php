<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manga Okuyucu</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .manga, .chapter, .page {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .page img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Manga Okuyucu</h1>
    </div>

    <div class="content">
        <h2>Tüm Mangalar</h2>
        <div id="mangaList"></div>
        <div id="chapterList" style="display:none;"></div>
        <div id="pageList" style="display:none;"></div>
    </div>

    <script>
        // Mangaları yükle
        fetch('get_mangas.php') // Mangaları çekecek bir API dosyası oluşturun
            .then(response => response.json())
            .then(data => {
                let mangaList = document.getElementById('mangaList');
                data.forEach(manga => {
                    let mangaDiv = document.createElement('div');
                    mangaDiv.classList.add('manga');
                    mangaDiv.innerHTML = `<strong>${manga.title}</strong> <button onclick="loadChapters(${manga.id})">Bölümleri Görüntüle</button>`;
                    mangaList.appendChild(mangaDiv);
                });
            });

        // Bölümleri yükle
        function loadChapters(mangaId) {
            fetch(`get_chapters.php?manga_id=${mangaId}`)
                .then(response => response.json())
                .then(data => {
                    let chapterList = document.getElementById('chapterList');
                    chapterList.style.display = 'block';
                    chapterList.innerHTML = '';
                    data.forEach(chapter => {
                        let chapterDiv = document.createElement('div');
                        chapterDiv.classList.add('chapter');
                        chapterDiv.innerHTML = `<strong>${chapter.title}</strong> <button onclick="loadPages(${chapter.id})">Sayfaları Görüntüle</button>`;
                        chapterList.appendChild(chapterDiv);
                    });
                });
        }

        // Sayfaları yükle
        function loadPages(chapterId) {
            fetch(`get_pages.php?chapter_id=${chapterId}`)
                .then(response => response.json())
                .then(data => {
                    let pageList = document.getElementById('pageList');
                    pageList.style.display = 'block';
                    pageList.innerHTML = '';
                    data.forEach(page => {
                        let pageDiv = document.createElement('div');
                        pageDiv.classList.add('page');
                        pageDiv.innerHTML = `<strong>Sayfa ${page.page_number}</strong><br><img src="${page.imagee}" alt="Sayfa Görseli">`;
                        pageList.appendChild(pageDiv);
                    });
                });
        }
    </script>

<div id="pageContainer"></div> <!-- Sayfaların yükleneceği alan -->

<script>
function loadPages(chapterId) {
    fetch('get_pages.php?chapter_id=' + chapterId)
        .then(response => response.json())
        .then(pages => {
            const pageContainer = document.getElementById('pageContainer');
            pageContainer.innerHTML = ''; // Önceki içerikleri temizle

            pages.forEach(page => {
                const imgElement = document.createElement('img');
                imgElement.src = page.image_url; // Görsel URL'sini ayarla
                imgElement.alt = 'Sayfa ' + page.page_number;
                pageContainer.appendChild(imgElement);
                
                // Sayfa numarasını göster
                const pageNumElement = document.createElement('p');
                pageNumElement.innerText = 'Sayfa Numarası: ' + page.page_number;
                pageContainer.appendChild(pageNumElement);
            });
        });
}

// Örneğin, manga bölümüne tıklandığında aşağıdaki fonksiyonu çağırabilirsin
// loadPages(chapterId); // chapterId, tıklanan bölümün ID'si
</script>


</body>
</html>
