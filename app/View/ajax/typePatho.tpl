<h2 class="titre">
        Types de pathologies
</h2>

{foreach from=$typesPatho item=typePatho}
    <div class="category__container" data-id="{$typePatho->idT}">
        <div class="category_title__container">
            <div class="category__title">
                {$typePatho->name}
            </div>
            <a href="#" class="drop"><i class="icon icon-down-open"></i></a>
        </div>
        <ul class="pathologies__container"></ul>
    </div>
{/foreach}