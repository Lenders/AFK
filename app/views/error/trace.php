<article id="trace">
    <table>
        <tr>
            <th>#</th><th>Fichier</th><th>Ligne</th><th>Fonction</th>
        </tr>
        <?php 
        $view = $this->newView('error/trace_step.php');
        
        foreach($trace as $key => $step){
            $step['key'] = $key;
            echo $view->render($step);
        }
        ?>
    </table>
</article>