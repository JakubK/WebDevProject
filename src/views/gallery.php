<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Moje Hobby</title>
        <link rel="stylesheet" href="static/css/reset.css"/>
        <link rel="stylesheet" href="static/css/common.css"/>
        <link rel="stylesheet" href="static/css/gallery.css"/>
    </head>
    <body>
        <?php include 'partial/navbar.php' ?>
        <header>
            <h1>Moje Hobby - Programowanie</h1>
        </header>
        <p>
            Poniżej znajdują się zdjęcia moich projektów
        </p>
        <form class="image-upload" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="upload"/>

            <input name="file" type="file"/>
            <label for="#watermark">Tekst znaku wodnego</label>
            <input id="watermark" name="watermark" type="text"/><br/>

            <input name="author" placeholder="Autor" type="text"/>
            <input name="title" placeholder="Tytuł" type="text"/>
            <input type="submit"/>
        </form>
        <p>
            <?=$uploadInfo ?? '' ?>
        </p>
        <div class="gallery">
            <?php if($images): ?>
                <?php foreach ($images as $image): ?>
                    <div class="item">
                        <div class="item-description">
                            <?=$image->author?>
                            <?=$image->title?>
                        </div>
                        <a href="<?=$image->watermark?>">
                            <img alt="gallery-image" src="<?=$image->thumbnail?>"/>
                        </a>
                    </div>
                <?php endforeach?>
            <?php endif?>
        </div>
        <div class="gallery-nav">
            <?php if($maxPage > 0): ?>
                <p>
                    <?=$page?>/<?=$maxPage?><br/>
                    <a
                    <?php if($page > 1): ?> 
                        href="?page=<?=$page-1?>"
                    <?php endif?>
                >Previous</a>     
                <a
                    <?php if($page < $maxPage): ?> 
                        href="?page=<?=$page+1?>"
                    <?php endif?>
                >Next</a>
                </p>
            <?php endif?>
            <?php if($maxPage <= 0):?>
                <p>Brak zdjęć w galerii</p>
            <?php endif?>
        </div>
        <div id="modal">
            <span class="close-btn">&times;</span>
            <img src="" id="modal-img"/>
        </div>
        <?php include 'partial/footer.php' ?>
        <script src="static/js/gallery.js"></script>
    </body>
</html>