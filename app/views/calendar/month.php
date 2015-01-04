<section id="contents">
    <article>
        <h1 class="title">Calendrier - <?php echo $monthName?></h1>
        <table id="month_calendar">
            <tr>
                <?php foreach($days as $day):?>
                <th><?php echo $day?>
                <?php endforeach?>
            </tr>
            <?php $currentTime = $firstTimeOfWeek?>
            <?php while($weeksInMonth-- > 0):?>
            <tr>
            <?php for($d = 0; $d < 7; ++$d, $currentTime += 24 * 3600):?>
                <?php if($currentTime < $firstTimeOfMonth || $currentTime > $lastTimeOfMonth):?>
                <td class="disable"></td>
                <?php else:?>
                <td <?php if(!empty($agenda[$currentTime])) echo 'class="active"'?>>
                    <?php echo date('j', $currentTime)?>
                    <?php if(!empty($agenda[$currentTime])):?>
                    <ul>
                        <?php foreach($agenda[$currentTime] as $event):?>
                        <li><a href="<?php echo $this->secureUrl('events', 'show', $event['EVENT_ID'])?>"><?php echo $event['EVENT_NAME']?></a></li>
                        <?php endforeach?>
                    </ul>
                    <?php endif?>
                </td>
                <?php endif?>
            <?php endfor?>
            </tr>
            <?php endwhile?>
        </table>
        <div class="button_container">
            <a href="<?php echo $this->secureUrl('calendar', 'month', ($month == 1 ? $year - 1 : $year), ($month == 1 ? 12 : $month - 1))?>" class="button big">&lt;</a>
            <a href="<?php echo $this->secureUrl('calendar', 'month', ($month == 12 ? $year + 1 : $year), ($month == 12 ? 1 : $month + 1))?>" class="button big">&gt;</a>
        </div>
    </article>
</section>