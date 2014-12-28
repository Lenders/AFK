<section id="contents">
    <h1>Statistiques</h1>
    <article>
        <h2 class="title">Comptes</h2>
        <div data-chart-type="PieChart" data-chart-options='{"title":"Homme / Femme"}' data-data-url="<?php echo $this->url('adminjson/malefemale.json')?>"></div>
    </article>
    <article>
        <h2 class="title">Pages</h2>
        <div data-chart-type="BarChart" data-chart-options='{"title":"Pages vus"}' style="height: 400px;" data-data-url="<?php echo $this->url('adminjson/pagecounts.json')?>"></div>
    </article>
    <article>
        <h2 class="title">Navigateurs</h2>
        <div data-chart-type="PieChart" data-chart-options='{"title":"Navigateurs"}' data-data-url="<?php echo $this->url('adminjson/browsercounts.json')?>"></div>
    </article>
</section>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php echo $this->js('admin_stats')?>