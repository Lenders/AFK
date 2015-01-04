<section id="contents">
    <article>
        <h1 class="title">Mon calendrier</h1>
        <div class="calendar">
            <div class="calendar_col">
                <div class="calendar_row">Heure</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"><?php echo $i?></div>
                <?php endfor?>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Lundi</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
                    
                <div style="position: relative;top: -1000px;background: red;height: 150px;">...</div>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Mardi</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Mercredi</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Jeudi</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Vendredi</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Samedi</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
            </div>
            <div class="calendar_col">
                <div class="calendar_row">Dimanche</div>
                <?php for($i = 0; $i <= 24; ++$i):?>
                    <div class="calendar_row"></div>
                <?php endfor?>
            </div>
        </div>
    </article>
</section>