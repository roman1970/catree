<div class="masthead">

</div>
<div class="row">

    <div class="col-sm-8 blog-main">

        <h3 class="text-muted">Древовидное меню</h3>
        <ul>
            <?php foreach ($cats as $cat) : ?>
                <?php if(!$cat->haveChild($cat->level)) : ?>
                    <li><a href="#"><?=$cat->title?></a></li>
                <?php else : ?>

                    <li>
                        <a href="#">
                            <?=$cat->title?>
                        </a>
                        <?= $cat->getChild($cat->id,$cat->level); ?>

                    </li>

                <?php endif; ?>
            <?php endforeach; ?>
        </ul>


    </div>

    <div class="col-sm-3 col-sm-offset-1 blog-sidebar">

        <div class="sidebar-module">
            <h4>Пункт меню (категория)</h4>
            <ol class="list-unstyled">

                <li><a href="add">Добавить</a></li>
                <li><a href="edit">Редактировать/Удалить</a></li>
            </ol>
        </div>

    </div>

</div>



